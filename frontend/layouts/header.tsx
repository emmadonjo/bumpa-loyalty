import Link from "next/link";
import UserProfileMenu from "@/layouts/user-menu";
import {HiOutlineBars3BottomRight} from "react-icons/hi2";
import {HiOutlineX} from "react-icons/hi";

export default function Header({
    toggleMenu,
    showMenu,
}: {
    toggleMenu: () => void;
    showMenu: boolean;
}) {
    return (
        <div className="w-full fixed top-0 left-0 bg-white z-40 py-4 px-6 border-b border-primary-600 flex items-center justify-between">
            <div className="text-2xl font-semibold flex items-center gap-x-3">
                <Link href="/" className="hover:text-primary">
                    Bumpa Loyalty
                </Link>

                <button type="button" onClick={toggleMenu} className="sm:hidden">
                    {showMenu ? <HiOutlineX size={24} /> : <HiOutlineBars3BottomRight size={24} />}
                </button>
            </div>
            <div className="inline-flex self-end">
                <UserProfileMenu  />
            </div>
        </div>
    )
}