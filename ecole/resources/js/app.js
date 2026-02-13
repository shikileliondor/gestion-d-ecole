import './bootstrap';
import Alpine from 'alpinejs';
import { initAllFilterForms } from './filters/core';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initAllFilterForms();
});
