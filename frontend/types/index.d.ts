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
export interface Badge {
    id: number;
    name: string;
    icon_url?: string;
    achievements_required: number;
}
export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    role: 'admin' | 'customer';
    created_at?: string;
    updated_at?: string;
    achievements_count?: number;
    loyalty_info?: LoyaltyInfo;
}

export interface LoyaltyInfo {
    id: number;
    current_badge_id?: number;
    current_badge?: Badge;
    purchase_count: number;
    total_spent: number;
    payout_balance: number;
    total_achievements: number;
    created_at: string;
    updated_at?: string;
}

export interface Achievement {
    id: number;
    name: string;
    type: string;
    threshold: number;
    reward: number;
    description: string;
    created_at: string;
    updated_at?: string;
}

export interface UserAchievement {
    id: number;
    user_id: number;
    achievement_id: number;
    user: User;
    achievement: Achievement;
    unlocked_at: string;
}

export interface BreadcrumbsItemProps extends BreadcrumbsProps {
    title: string;
    href: string;
}


export type QueryParams = {
    page: number;
    per_page?: number;
    search?: string;
    filters?: Record<string, never>;
}

export interface Meta {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

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