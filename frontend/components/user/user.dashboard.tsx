import UserAchievements from "@/components/user/user.achievements";
import useLoyaltyTracker from "@/hooks/loyalty-tracker.hook";
import useAuth from "@/hooks/auth.hook";
import {Skeleton} from "@heroui/react";
import UserLoyaltyStats from "@/components/user/user.loyalty-stats";
import UserBadges from "@/components/user/user.badges";
import {Badge} from "@/types";
import SimulatePurchase from "@/components/user/user.simulate-purchase";

export default function UserDashboard() {
    const {user} = useAuth();
    const {isLoading, user: loyalty} = useLoyaltyTracker(user?.id as number);
    return (
        <div className="grid md:grid-cols-12 gap-8 content-start">
            <div className="md:col-span-4 lg:col-span-3">
                <div className="mb-4">
                    <SimulatePurchase />
                </div>
                <Skeleton isLoaded={!isLoading}>
                    {
                        loyalty && <UserBadges badges={loyalty.badges as Badge[]} />
                    }
                </Skeleton>
            </div>
            <div className="md:col-span-8 lg:col-span-8">
                <div className="mb-5">
                    <Skeleton isLoaded={!isLoading}>
                        {
                            loyalty && <UserLoyaltyStats user={loyalty} />
                        }
                    </Skeleton>
                </div>
                <div className="bg-white px-6 py-10 rounded-lg mt-10">
                    <UserAchievements />
                </div>
            </div>
        </div>
    )
}