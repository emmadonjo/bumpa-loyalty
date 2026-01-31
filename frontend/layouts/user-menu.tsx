import { BiLogOut } from 'react-icons/bi';
import {
    Dropdown,
    DropdownTrigger,
    DropdownMenu,
    DropdownItem,
    Avatar
} from '@heroui/react';
import { HiOutlineChevronDown } from 'react-icons/hi2';
import useAuth from "@/hooks/auth.hook";

export default function UserProfileMenu() {
    const { logoutUser, user } = useAuth();
    return (
        <Dropdown>
            <DropdownTrigger className="user-menu">
                <div className='inline-flex items-center gap-x-4 hover:cursor-pointer'>
                    <Avatar
                        isBordered
                        as="button"
                        color='primary'
                        src=""
                        size='sm'
                    />
                    <div className='hidden sm:inline-flex flex-col'>
                        <span>{user?.name}</span>
                        <span className='text-default-400 text-xs'>{user?.role}</span>
                    </div>
                    <HiOutlineChevronDown size={16} />
                </div>
            </DropdownTrigger>
            <DropdownMenu
                aria-label="User profile menu"
            >
                <DropdownItem
                    key="logout"
                    startContent={<BiLogOut size={24} className='text-danger-500 group-hover:text-white' />}
                    as="button"
                    onClick={logoutUser}
                    color='danger'
                    className="logout"
                >
                    Log out
                </DropdownItem>
            </DropdownMenu>
        </Dropdown>
    )
}