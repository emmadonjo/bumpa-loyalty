import Link from "next/link";
import {adminLinks, userLinks} from '@/layouts/links'
import useAuth from "@/hooks/auth.hook";
import {HiOutlineChartBar} from "react-icons/hi2";
import {HiOutlineBadgeCheck} from "react-icons/hi";

const icons = {
    HiOutlineChartBar: HiOutlineChartBar,
    HiOutlineBadgeCheck: HiOutlineBadgeCheck,
}
export default function Sidebar({showMenu}: {showMenu?: boolean}) {
    const { user } = useAuth();
    let links = userLinks;
    if (user && user.role === "admin") {
        links = adminLinks;
    }
    return (
        <div className={`w-full max-w-[240px] h-screen bg-white fixed top-0 left-0 ${showMenu ? '' : 'hidden sm:block'}`}>
            <nav className="h-screen overflow-y-auto pt-24">
                <ul className="w-full">
                    {
                        links.map((link, index) => (
                            <li key={index}>
                                <Link href={link.href} className="px-6 flex items-center gap-x-4 py-3 hover:bg-primary hover:text-white w-full text-lg font-medium">
                                    {/*{icons[link.icon]}*/}
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