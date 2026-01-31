"use client";

import {TableHeaderColumnProps, UserAchievement} from "@/types";
import SearchForm from "@/components/forms/search.form";
import Table from "@/components/ui/table.ui";
import Pagination from "@/components/ui/pagination.ui";
import {TableBody, TableCell, TableColumn, TableHeader, TableRow} from "@heroui/react";
import {formatDate} from "@/helpers";
import useAchievement from "@/hooks/achievement.hook";

export default function UsersAchievementsList(){
    const {
        isLoading,
        usersAchievements,
        meta,
        setPage,
        setSearch,
    } = useAchievement(true);
    const tableHeaders: TableHeaderColumnProps[] = [
        {name: 'Customer Name', key: 'customer_name', sortable: false},
        {name: 'Achievement Name', key: 'name', sortable: false},
        {name: 'Achievement Type', key: 'type', sortable: false},
        {name: 'Date Unlocked', key: 'unlocked_at', sortable: false},
    ];

    return (
        <div className="w-full bg-white p-6">
            <h2 className="text-2xl font-semibold px-4 my-8">Users Achievements</h2>
            <div>
                <div className="w-full sm:max-w-md pl-4">
                    <SearchForm placeholder="Search..." handleSearch={(searchTerm: string) => setSearch(searchTerm)} />
                </div>
            </div>
            <Table
                aria-label="Customers' Achievements"
                bottomContent={<Pagination
                    page={meta.current_page}
                    total={meta.last_page as number}
                    onChange={(page) => setPage(page)}
                />}
                className="admin-user-achievements-list"
            >
                <TableHeader>
                    {tableHeaders.map((header: TableHeaderColumnProps) => (
                        <TableColumn key={header.key}>{ header.name }</TableColumn>
                    ))}
                </TableHeader>
                <TableBody
                    emptyContent="No user achievement was found"
                    isLoading={isLoading}
                >
                    {
                        usersAchievements.map((datum: UserAchievement) => (
                            <TableRow key={datum.id}>
                                <TableCell className="whitespace-nowrap">{datum.user ? datum.user.name : ''}</TableCell>
                                <TableCell className="whitespace-nowrap">{datum.achievement ? datum.achievement.name : ''}</TableCell>
                                <TableCell className="whitespace-nowrap capitalize">{ datum.achievement ? datum.achievement.type.replace('_', ' ') : ''}</TableCell>
                                <TableCell className="whitespace-nowrap">{ formatDate(datum.unlocked_at, 'DD')}</TableCell>
                            </TableRow>
                        ))
                    }
                </TableBody>
            </Table>
        </div>
    )
}