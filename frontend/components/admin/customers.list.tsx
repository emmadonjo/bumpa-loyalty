import useCustomer from "@/hooks/customer.hook";
import {TableHeaderColumnProps, User} from "@/types";
import SearchForm from "@/components/forms/search.form";
import Table from "@/components/ui/table.ui";
import Pagination from "@/components/ui/pagination.ui";
import {TableBody, TableCell, TableColumn, TableHeader, TableRow} from "@heroui/react";
import {formatDate} from "@/helpers";

export default function CustomersList(){
    const {
        isLoading,
        customers,
        meta,
        setPage,
        setSearch,
    } = useCustomer(true);
    const tableHeaders: TableHeaderColumnProps[] = [
        {name: 'Name', key: 'name', sortable: false},
        {name: 'Email Address', key: 'email', sortable: false},
        {name: 'Tot. Achievements', key: 'achievements_count', sortable: false},
        {name: 'Current Badge', key: 'current_badge', sortable: false},
        {name: 'Date Joined', key: 'created_at', sortable: false},
    ];

    return (
        <div className="w-full bg-white p-6">
            <h2 className="text-2xl font-semibold px-4 my-8">Customers</h2>
            <div>
                <div className="w-full sm:max-w-md pl-4">
                    <SearchForm placeholder="Search customers" handleSearch={(searchTerm: string) => setSearch(searchTerm)} />
                </div>
            </div>
            <Table
                aria-label="Customers"
                bottomContent={<Pagination
                    page={meta.current_page}
                    total={meta.last_page as number}
                    onChange={(page) => setPage(page)}
                />}
                className="customers-list"
            >
                <TableHeader>
                    {tableHeaders.map((header: TableHeaderColumnProps) => (
                        <TableColumn key={header.key}>{ header.name }</TableColumn>
                    ))}
                </TableHeader>
                <TableBody
                    emptyContent="No customer was found"
                    isLoading={isLoading}
                >
                    {
                        customers.map((datum: User) => (
                            <TableRow key={datum.id}>
                                <TableCell className="whitespace-nowrap">{datum.name}</TableCell>
                                <TableCell className="whitespace-nowrap">{datum.email}</TableCell>
                                <TableCell className="whitespace-nowrap text-center">{ datum.achievements_count ?? 0 }</TableCell>
                                <TableCell className="whitespace-nowrap">{ datum.loyalty_info?.current_badge ? datum.loyalty_info.current_badge.name : '' }</TableCell>
                                <TableCell className="whitespace-nowrap">{ formatDate(datum.created_at, 'DD')}</TableCell>
                            </TableRow>
                        ))
                    }
                </TableBody>
            </Table>
        </div>
    )
}