import Link from "next/link";
import {adminLinks, userLinks} from '@/layouts/links'
import useAuth from "@/hooks/auth.hook";
import {useEffect, useRef} from "react";

export default function Sidebar({
    showMenu,
    hideMenu,
}: {
    hideMenu: () => void;
    showMenu: boolean
}) {
    const { user } = useAuth();
    const elementRef = useRef<HTMLDivElement|null>(null);

    let links = userLinks;
    if (user && user.role === "admin") {
        links = adminLinks;
    }
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (elementRef.current && !elementRef.current.contains(event.target as Node)) {
                hideMenu();
            }
        };

        document.addEventListener('mousedown', handleClickOutside);

        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [hideMenu]);

    return (
        <div className={`w-full max-w-[240px] h-screen bg-white fixed top-0 left-0 ${showMenu ? '' : 'hidden sm:block'}`} ref={elementRef}>
            <nav className="h-screen overflow-y-auto pt-24">
                <ul className="w-full">
                    {
                        links.map((link, index) => (
                            <li key={index} onClick={hideMenu}>
                                <Link href={link.href} className="px-6 flex items-center gap-x-4 py-3 hover:bg-primary hover:text-white w-full text-lg font-medium group">
                                    <link.icon size={24} className="text-primary group-hover:text-white"/>
                                    {link.label}
                                </Link>
                            </li>
                        ))
                    }
                </ul>
            </nav>
        </div>
    )
}