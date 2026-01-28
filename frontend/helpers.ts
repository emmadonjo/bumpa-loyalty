export const clearToken = () => {
    if (typeof window !== 'undefined') {
        localStorage.removeItem('auth');
    }
}

export const saveToken = (token: string) => {
    if (typeof window !== 'undefined') {
        localStorage.setItem('auth', token);
    }
}

export const getToken = (): string | null => {
    return typeof window !== 'undefined'
        ?localStorage.getItem('auth') : null;
}