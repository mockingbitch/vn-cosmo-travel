/**
 * Premium nav: desktop hover+intent delay; mobile tap. ESC closes.
 */
export function mainSiteNav() {
    return {
        /** @type {string|null} */
        panel: null,
        /** mobile drawer (hamburger) */
        mobileOpen: false,
        /** expanded accordion section key on mobile (mega-daily | dropdown panel id) */
        mobileSection: null,
        /** @type {ReturnType<typeof setTimeout>|null} */
        tOpen: null,
        /** @type {ReturnType<typeof setTimeout>|null} */
        tClose: null,
        openDelay: 180,
        closeDelay: 240,
        mql: null,
        isDesktop: false,
        init() {
            this.mql = window.matchMedia('(min-width: 1024px)');
            this.isDesktop = this.mql.matches;
            this._onMql = () => {
                this.isDesktop = this.mql.matches;
                if (!this.isDesktop) {
                    this.clearTimers();
                }
            };
            this.mql.addEventListener('change', this._onMql);
        },
        destroy() {
            if (this.mql) {
                this.mql.removeEventListener('change', this._onMql);
            }
        },
        clearTimers() {
            if (this.tOpen) {
                clearTimeout(this.tOpen);
                this.tOpen = null;
            }
            if (this.tClose) {
                clearTimeout(this.tClose);
                this.tClose = null;
            }
        },
        onTriggerEnter(id) {
            if (!this.isDesktop) {
                return;
            }
            this.clearTimers();
            this.tOpen = setTimeout(() => {
                this.panel = id;
            }, this.openDelay);
        },
        onTriggerLeave() {
            if (!this.isDesktop) {
                return;
            }
            this.clearTimers();
            this.tClose = setTimeout(() => {
                this.panel = null;
            }, this.closeDelay);
        },
        onPanelEnter() {
            if (!this.isDesktop) {
                return;
            }
            this.clearTimers();
        },
        onPanelLeave() {
            if (!this.isDesktop) {
                return;
            }
            this.clearTimers();
            this.tClose = setTimeout(() => {
                this.panel = null;
            }, this.closeDelay);
        },
        toggle(id) {
            this.panel = this.panel === id ? null : id;
        },
        toggleMobileSection(key) {
            this.mobileSection = this.mobileSection === key ? null : key;
        },
        close() {
            this.clearTimers();
            this.panel = null;
        },
        closeAll() {
            this.clearTimers();
            this.panel = null;
            this.mobileOpen = false;
            this.mobileSection = null;
        },
    };
}
