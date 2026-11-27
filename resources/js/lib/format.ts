const currency = new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });

export function formatPrice(value: string | number): string {
    return currency.format(Number(value));
}
