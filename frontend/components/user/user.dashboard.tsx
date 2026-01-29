import UserAchievements from "@/components/user/user.achievements";
import useLoyaltyTracker from "@/hooks/loyalty-tracker.hook";
import useAuth from "@/hooks/auth.hook";
import {Skeleton} from "@heroui/react";
import UserLoyaltyStats from "@/components/user/user.loyalty-stats";
import UserBadges from "@/components/user/user.badges";
import {Badge} from "@/types";

export default function UserDashboard() {
    const {user} = useAuth();
    const {isLoading, user: loyalty} = useLoyaltyTracker(user?.id as number);
    return (
        <div className="grid lg:grid-cols-12 gap-8 content-start">
            <div className="lg:col-span-3">
                <Skeleton isLoaded={!isLoading}>
                    {
                        loyalty && <UserBadges badges={loyalty.badges as Badge[]} />
                    }
                </Skeleton>
            </div>
            <div className="lg:col-span-9">
                <div className="mb-5">
                    <Skeleton isLoaded={!isLoading}>
                        {
                            loyalty && <UserLoyaltyStats user={loyalty} />
                        }
                    </Skeleton>
                </div>
                <div className="bg-white px-6 py-10 rounded-lg">
                    <UserAchievements />
                </div>
            </div>
        </div>
    )
}