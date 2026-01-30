import {User} from "@/types";
import {money} from "@/helpers";

export default function UserLoyaltyStats({user}: {user: User}) {
    return (
        <div className="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <div className="bg-white p-6 rounded-lg">
                <h4 className="text-xl font-semibold text-center">{user.achievements_count ?? 0}</h4>
                <span className="text-xs block text-center">Tot. Achievements</span>
            </div>

            <div className="bg-white p-6 rounded-lg">
                <h4 className="text-xl font-semibold text-center">{user.badges_count ?? 0}</h4>
                <span className="text-xs block text-center">Badges</span>
            </div>

            <div className="bg-white p-6 rounded-lg">
                <h4 className="text-xl font-semibold text-center">{user.loyalty_info?.purchase_count ?? 0}</h4>
                <span className="text-xs block text-center">Purchases</span>
            </div>

            <div className="bg-white p-6 rounded-lg">
                <h4 className="text-xl font-semibold text-center">{money(user.loyalty_info?.total_spent ?? 0)}</h4>
                <span className="text-xs block text-center">Tot. Spent</span>
            </div>

            <div className="bg-white p-6 rounded-lg">
                <h4 className="text-2xl font-semibold text-center">{money(user.loyalty_info?.payout_balance ?? 0)}</h4>
                <span className="text-xs block text-center">Loyalty Balance</span>
            </div>
        </div>
    )
}