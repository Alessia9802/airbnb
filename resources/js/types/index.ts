import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    flash: { success?: string | null };
    ziggy: {
        location: string;
        url: string;
        port: null | number;
        defaults: Record<string, unknown>;
        routes: Record<string, string>;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Service {
    id: number;
    name: string;
    icon: string;
}

export interface Host {
    id: number;
    name: string;
    created_at: string;
}

export interface Apartment {
    id: number;
    user_id: number;
    title: string;
    slug: string;
    description: string;
    rooms: number;
    beds: number;
    bathrooms: number;
    square_meters: number;
    address: string;
    city: string;
    latitude: number | null;
    longitude: number | null;
    price_per_night: string;
    cover_image: string | null;
    cover_url: string | null;
    is_visible: boolean;
    created_at: string;
    updated_at: string;
    services?: Service[];
    host?: Host;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
    current_page: number;
    last_page: number;
    total: number;
}
