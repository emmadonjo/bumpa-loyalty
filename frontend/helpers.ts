import {DateTime} from 'luxon';

export const clearStoreItem = (key: string) => {
    if (typeof window !== 'undefined') {
        localStorage.removeItem(key);
    }
}

export const saveStoreItem = (key: string, token: string) => {
    if (typeof window !== 'undefined') {
        localStorage.setItem(key, token);
    }
}

export const getStoreItem = (key: string): string | null => {
    return typeof window !== 'undefined'
        ?localStorage.getItem(key) : null;
}

export const formatDate = (date: string|null = null, format:string = 'f') => {
    if (date === null) {
        return '';
    }

    return DateTime.fromISO(date)
        .setZone('Africa/Lagos')
        .toFormat(format);
}