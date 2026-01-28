"use client";
import PortalLayout from "@/layouts/portal.layout";
import useAuth from "@/hooks/auth.hook";
import CustomersList from "@/components/admin/customers.list";

export default function Home() {
    const {user} = useAuth();
  return (
      <PortalLayout>
        <div className="min-h-screen">
          <main >
              {
                  user && user.role === "admin" && (<CustomersList/>)
              }
          </main>
        </div>
      </PortalLayout>
  );
}
