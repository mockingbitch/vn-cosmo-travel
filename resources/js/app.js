import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import { mainSiteNav } from './siteNav';

window.Alpine = Alpine;
Alpine.plugin(focus);

document.addEventListener('alpine:init', () => {
    Alpine.store('scrollLock', {
        count: 0,
        lock() {
            this.count++;
            if (this.count === 1) {
                document.documentElement.classList.add('overflow-hidden');
            }
        },
        unlock() {
            if (this.count > 0) {
                this.count--;
            }
            if (this.count === 0) {
                document.documentElement.classList.remove('overflow-hidden');
            }
        },
    });
});

Alpine.data('mainSiteNav', mainSiteNav);
Alpine.data('currencyPriceInput', (initial) => ({
    raw:
        initial === null || initial === undefined || initial === ''
            ? null
            : Math.max(0, parseInt(String(initial), 10)),
    format(n) {
        if (n === null || n === undefined || n === '' || Number.isNaN(n)) {
            return '';
        }
        const num = Math.max(0, Math.floor(Number(n)));
        return num.toLocaleString('en-US');
    },
    init() {
        this.$nextTick(() => {
            const el = this.$refs.vis;
            if (el) {
                el.value = this.format(this.raw);
            }
        });
    },
    onInput(e) {
        const digits = e.target.value.replace(/\D/g, '');
        if (digits === '') {
            this.raw = null;
            e.target.value = '';
            return;
        }
        this.raw = Math.max(0, parseInt(digits, 10));
        e.target.value = this.format(this.raw);
    },
}));
Alpine.data('tourPicker', ({ selected, max, pickerUrl }) => ({
    items: Array.isArray(selected) ? selected : [],
    max: max ?? 8,
    openModal: false,
    q: '',
    results: [],
    nextPageUrl: null,
    loading: false,
    searchTimer: null,

    get atMax() {
        return this.items.length >= this.max;
    },

    isPicked(id) {
        return this.items.some((item) => item.id === id);
    },

    open() {
        this.openModal = true;
        if (this.results.length === 0) {
            this.reload();
        }
        this.$nextTick(() => {
            try { this.$refs.search?.focus(); } catch (e) {}
        });
    },

    searchDebounced() {
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => this.reload(), 250);
    },

    async reload() {
        this.loading = true;
        try {
            const url = new URL(pickerUrl, window.location.origin);
            if (this.q) url.searchParams.set('q', this.q);
            const res = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
            const json = await res.json();
            this.results = json.data ?? [];
            this.nextPageUrl = json.next_page_url ?? null;
        } finally {
            this.loading = false;
        }
    },

    async loadMore() {
        if (!this.nextPageUrl || this.loading) return;
        this.loading = true;
        try {
            const res = await fetch(this.nextPageUrl, { headers: { Accept: 'application/json' } });
            const json = await res.json();
            this.results = [...this.results, ...(json.data ?? [])];
            this.nextPageUrl = json.next_page_url ?? null;
        } finally {
            this.loading = false;
        }
    },

    toggle(tour) {
        if (this.isPicked(tour.id)) {
            this.remove(tour.id);
            return;
        }
        if (this.atMax) return;
        this.items = [...this.items, tour];
    },

    remove(id) {
        this.items = this.items.filter((item) => item.id !== id);
    },

    move(index, delta) {
        const target = index + delta;
        if (target < 0 || target >= this.items.length) return;
        const next = [...this.items];
        [next[index], next[target]] = [next[target], next[index]];
        this.items = next;
    },
}));
Alpine.start();
