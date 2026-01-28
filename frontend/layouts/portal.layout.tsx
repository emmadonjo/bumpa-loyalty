"use client";

import { BreadcrumbsItemProps } from "@/types";
import { ReactNode, useEffect } from "react";
import { BreadcrumbItem, Breadcrumbs } from "@heroui/react";
import { HiHome } from "react-icons/hi2";
import { usePathname, useRouter } from "next/navigation";
import useAuth from "@/hooks/auth.hook";

interface AuthLayoutProps {
    children: ReactNode;
    title?: string;
    breadcrumbs?: BreadcrumbsItemProps[];
}

export default function PortalLayout({
     children,
     title,
     breadcrumbs = [],
 }: AuthLayoutProps) {
    const router = useRouter();
    const pathname = usePathname();
    const { token, hydrated, isLoading } = useAuth();

    useEffect(() => {
        if (!hydrated || isLoading) return;

        if (!token && (pathname !== "/login")) {
            router.replace("/login");
            return;
        }
        if (token && (pathname === "/login")) {
            router.replace("/");
            return;
        }

    }, [token, hydrated, isLoading, pathname, router]);

    if (!hydrated || isLoading) return null;

    if (!token) return null;

    return (
        <div className="bg-default-50/30 min-h-screen">
            {/*<SideMenu />*/}
            {/*<Header />*/}
            <main className="pt-[100px] pl-6 pr-6 sm:ml-[240px]">
                <div className="flex items-center flex-wrap gap-4 justify-between pr-6 mb-10">
                    {title && (
                        <h1 className="text-base font-medium text-default-700">{title}</h1>
                    )}

                    {breadcrumbs.length > 0 && (
                        <div className="flex items-center mb-6">
                            <Breadcrumbs className="flex gap-[10px] justify-end text-xs">
                                <BreadcrumbItem key="home" href="/">
                                    <HiHome size={16} />
                                    <span className="sr-only">Home</span>
                                </BreadcrumbItem>

                                {breadcrumbs.map((breadcrumb, index) => (
                                    <BreadcrumbItem key={index} href={breadcrumb.href}>
                                        {breadcrumb.title}
                                    </BreadcrumbItem>
                                ))}
                            </Breadcrumbs>
                        </div>
                    )}
                </div>
                {children}
            </main>
        </div>
    );
}