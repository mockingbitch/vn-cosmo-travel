import Alpine from 'alpinejs';
import { mainSiteNav } from './siteNav';

window.Alpine = Alpine;
Alpine.data('mainSiteNav', mainSiteNav);
Alpine.data('vndPriceInput', (initial) => ({
    raw:
        initial === null || initial === undefined || initial === ''
            ? null
            : Math.max(0, parseInt(String(initial), 10)),
    format(n) {
        if (n === null || n === undefined || n === '' || Number.isNaN(n)) {
            return '';
        }
        const num = Math.max(0, Math.floor(Number(n)));
        return num.toLocaleString('vi-VN');
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
Alpine.start();
