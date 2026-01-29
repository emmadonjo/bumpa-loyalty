import {Badge} from "@/types";
import {HiOutlineBadgeCheck} from "react-icons/hi";

export default function UserBadges({badges}: {badges: Badge[]}) {
    return (
        <div className="bg-white p-6">
            <h4 className="text-2xl font-semibold">Your Badges</h4>

            {badges.length < 1 && (<p className="mt-4 text-sm">
                You have no badges currently. Purchase frequently to acquire badges and earn loyalties
            </p>)}

            {badges.length > 0 && (
                <ul className="mt-5">
                    {badges.map((badge: Badge) => (
                        <li key={badge.id} className="relative pl-10 pt-3">
                            <HiOutlineBadgeCheck size={30} className="text-primary absolute left-0 top-3" />
                            <div className="font-semibold">{badge.name}</div>
                            <div className="text-xs text-default-600">{badge.pivot ? badge.pivot.awarded_at.substring(0, 10) : ''}</div>
                        </li>
                    ))}
                </ul>
            )}
        </div>
    )
}