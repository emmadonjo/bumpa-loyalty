import { BreadcrumbsProps } from "@heroui/react";

export interface LoginData {
    email?: string;
    password?: string;
}

export type ApiResponseProps<T = unknown> = {
    message: string;
    status: boolean;
    data: T;
}

export type ApiErrorProps<T = unknown> = {
    status: number;
    message?: string;
    data?: ApiResponseProps<T>
}

export type ApiSuccessProps<T = unknown> = {
    status: number;
    message?: string;
    data: ApiResponseProps<T>
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    role: 'admin' | 'customer';
    created_at?: string;
    updated_at?: string;
}


export interface BreadcrumbsItemProps extends BreadcrumbsProps {
    title: string;
    href: string;
}

// export type SideMenuLinkProps = {
//     name: string;
//     icon: IconType;
//     href: string;
//     activeRoute: string;
//     permissions?: string[] | string | undefined;
//     submenus?: SideMenuLinkProps[];
// }


export type QueryParams = Record<string, string | number | null | Record<string, string | number | boolean | null>>;

export type PaginationProps = {
    current_page: number;
    from?: number;
    last_page: number;
    per_page: number;
    to?: number;
    total: number;
    path?: string;
    links?:
        { url: string | null, label: string, active: boolean }[];
}

export type ApiPaginatedResponseProps<T = unknown> = {
    status: number;
    message?: string;
    data: {
        data: Record<T>;
        meta: PaginationProps;
        unread_notifications_count?: number;
    };
}

export type PaginatedResponseProps<T = unknown> = {
    data: Record<T>;
    meta: PaginationProps;
}

export type TableHeaderColumnProps = {
    name: string;
    key: string;
    sortable: boolean;
}