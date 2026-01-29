"use client";
import {ReactNode} from "react";

export default function GeneralLayout({ children }: { children: ReactNode }) {
    return (
        <div className="bg-default-50/30 min-h-screen">
            <main className="min-h-screen">
                {children}
            </main>
        </div>
    )
}