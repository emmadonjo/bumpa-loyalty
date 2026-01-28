"use client";

import { BreadcrumbsItemProps } from "@/types";
import { ReactNode, useEffect } from "react";
import { usePathname, useRouter } from "next/navigation";
import useAuth from "@/hooks/auth.hook";

interface AuthLayoutProps {
    children: ReactNode;
    title?: string;
    breadcrumbs?: BreadcrumbsItemProps[];
}

export default function GuestLayout({
     children,
 }: AuthLayoutProps) {
    const router = useRouter();
    const pathname = usePathname();
    const { token, hydrated, isLoading } = useAuth();

    useEffect(() => {
        if (!hydrated || isLoading) return;
        if (token) {
            router.replace("/");
            return;
        }
    }, [token, hydrated, isLoading, pathname, router]);

    if (!hydrated || isLoading) return null;
    if (token) return null;

    return (
        <div className="bg-default-50/30 min-h-screen">
            <main>
                {children}
            </main>
        </div>
    );
}