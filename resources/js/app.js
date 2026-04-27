import Alpine from 'alpinejs';
import { mainSiteNav } from './siteNav';

window.Alpine = Alpine;
Alpine.data('mainSiteNav', mainSiteNav);
Alpine.start();
