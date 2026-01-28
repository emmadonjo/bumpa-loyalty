import PortalLayout from "@/layouts/portal.layout";
import UsersAchievementsList from "@/components/admin/users-achievements.list";

export default function Achievements() {
    return (
        <PortalLayout
            title="Achievements"
        >
            <main className="min-h-screen">
                <UsersAchievementsList />
            </main>
        </PortalLayout>
    )
}