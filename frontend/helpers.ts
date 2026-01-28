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