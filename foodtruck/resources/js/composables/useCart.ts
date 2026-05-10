import { computed, ref } from 'vue';

export interface CartItem {
    id: number;
    name: string | Record<string, string>;
    price: number;
    quantity: number;
    image: string | null;
}

const STORAGE_KEY = 'foodtruck_cart';

function loadFromStorage(): CartItem[] {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
    } catch {
        return [];
    }
}

function saveToStorage(items: CartItem[]) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
}

const items = ref<CartItem[]>(loadFromStorage());

export function useCart() {
    const total = computed(() =>
        items.value.reduce((sum, i) => sum + parseFloat(String(i.price)) * i.quantity, 0),
    );

    const count = computed(() =>
        items.value.reduce((sum, i) => sum + i.quantity, 0),
    );

    function add(product: Omit<CartItem, 'quantity'>, qty = 1) {
        const existing = items.value.find((i) => i.id === product.id);
        if (existing) {
            existing.quantity += qty;
        } else {
            items.value.push({ ...product, price: parseFloat(String(product.price)), quantity: qty });
        }
        saveToStorage(items.value);
    }

    function remove(productId: number) {
        items.value = items.value.filter((i) => i.id !== productId);
        saveToStorage(items.value);
    }

    function updateQty(productId: number, qty: number) {
        if (qty <= 0) {
            remove(productId);
            return;
        }
        const item = items.value.find((i) => i.id === productId);
        if (item) {
            item.quantity = qty;
            saveToStorage(items.value);
        }
    }

    function clear() {
        items.value = [];
        saveToStorage(items.value);
    }

    return { items, total, count, add, remove, updateQty, clear };
}
