<?php

namespace App\Domains\Loyalty\Services;

use App\Domains\Accounts\Services\UserService;
use app\Domains\Loyalty\Enums\AchievementType;
use App\Domains\Loyalty\Persistence\Entities\Achievement;
use App\Domains\Loyalty\Persistence\Entities\Badge;
use App\Infrastructure\Messaging\Contracts\MessageProducerInterface;
use App\Infrastructure\Messaging\Events\BadgeUnlocked;
use App\Presentation\Web\DataTransferObjects\PurchaseDto;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class RewardsService
{
    public function __construct(
        private MessageProducerInterface $messageProducer,
    ){}

    /**
     * @param array $payload
     * @return void
     * @throws Exception
     */
    public function handle( PurchaseDto $payload): void
    {
        $user = app(UserService::class)->find($payload->customerId);
        if (!$user) {
            throw new ModelNotFoundException("No user was found for ID " . $payload['user_id']);
        }

        // retrieve or create a new loyalty tracker records for the user
        $loyaltyInfo = $user->loyaltyInfo()->firstOrCreate();
        $purchaseCount = $loyaltyInfo->purchase_count++;
        $totalSpent = $loyaltyInfo->total_spent + $payload->amount;

        $achievements = $this->getQualifiedAchievements($user->id, $totalSpent, $purchaseCount);
        $rewards = 0;

        foreach ($achievements as $achievement) {
            $rewards += $achievement->reward;
            $user->achievements()->attach($achievement->id, [
                'unlocked_at' => now(),
            ]);
        }

        $achievementsCount = $user->refresh()->achievements->count();
        $badges = $this->getQualifiedBadges($user->id, $achievementsCount);

        $currentBadge = null;
        foreach ($badges as $badge) {
            $currentBadge = $badge;
            $user->badges()->attach($badge->id, [
                'awarded_at' => now(),
            ]);
        }

        $loyaltyInfo->update([
            'current_badge_id' => $currentBadge ? $currentBadge->id : $loyaltyInfo->current_badge_id,
            'purchase_count' => $purchaseCount,
            'total_spent' => $totalSpent,
            'total_achievements' => $achievementsCount,
            'payout_balance' => $rewards + $loyaltyInfo->payout_balance,
        ]);

        if ($currentBadge) {
            // if the user has acquired a new badge,
            // publish the event to the message broker
            $this->messageProducer->publish(BadgeUnlocked::class, [
                'id' => $currentBadge->id,
                'name' => $currentBadge->name,
                'icon_url' => $currentBadge->icon_url,
                'achievements_required' => $currentBadge->achievements_required,
                'unlocked_at' => now(),
            ]);
        }
    }

    /**
     * Retrieves available badges a user has not earned based on their total achievements
     * @param int $userId
     * @param int $achievementsCount
     * @return Collection<Badge>
     */
    private function getQualifiedBadges(int $userId, int $achievementsCount): Collection
    {
        return Badge::where('achievements_required', '>=', $achievementsCount)
            ->whereDoesntHave('users', function (Builder $query) use($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }

    /**
     * Retrieves the achievements that the user has not acquired
     * based on their total spend and number of purchases
     *
     * @param int $userId
     * @param float $totalSpent
     * @param int $purchaseCount
     * @return Collection<Achievement>
     */
    private function getQualifiedAchievements(int $userId, float $totalSpent, int $purchaseCount): Collection
    {
        return Achievement::where(function (Builder $query) use ($purchaseCount, $totalSpent) {
            $query->where(function (Builder $query) use ($purchaseCount) {
                $query->where('type', AchievementType::PURCHASE_COUNT)
                    ->where('threshold', '>=', $purchaseCount);
            })
                ->orWhere(function (Builder $query) use ($totalSpent) {
                    $query->where('type', AchievementType::PURCHASE_COUNT)
                        ->where('threshold', '<=', $totalSpent);
                });
        })
            ->whereDoesntHave('users', function (Builder $query) use($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }
}
