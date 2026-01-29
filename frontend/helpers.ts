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

/**
 *
 * @param date
 * @param format
 */
export const formatDate = (date: string|null = null, format:string = 'f') => {
    if (date === null) {
        return '';
    }

    return DateTime.fromISO(date)
        .setZone('Africa/Lagos')
        .toFormat(format);
}

/**
 *
 * @param amount
 * @param format
 * @param currency
 * @param shorten
 */
export const money = (
    amount: number,
    format: string = 'en-NG',
    currency: string = 'NGN',
    shorten: boolean = false
): string => {
    let unit = '';
    let displayValue = amount;

    if (shorten) {
        if (amount >= 1_000_000_000) {
            unit = 'b';
            displayValue /= 1_000_000_000;
        } else if (amount >= 1_000_000) {
            unit = 'm';
            displayValue /= 1_000_000;
        } else if (amount >= 1_000) {
            unit = 'k';
            displayValue /= 1_000;
        }

        displayValue = parseFloat(displayValue.toFixed(2));
    }

    const formatted = new Intl.NumberFormat(format, {
        style: 'currency',
        currency,
        currencySign: 'accounting',
        minimumFractionDigits: shorten ? 0 : 2,
        maximumFractionDigits: shorten ? 2 : 2,
    }).format(displayValue);

    return shorten ? `${formatted}${unit}` : formatted;
};

/**
 * @param {number} min
 * @param {number} max
 * @returns {number}
 */
export const randomInt = (min: number, max: number): number => {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}