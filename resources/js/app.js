import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

const THEME_STORAGE_KEY = 'signature-pos-theme';

function resolveInitialTheme() {
    try {
        const stored = window.localStorage.getItem(THEME_STORAGE_KEY);
        if (stored === 'light' || stored === 'dark') {
            return stored;
        }
    } catch {
        // Storage may be unavailable in restricted contexts; fall back to dark glam.
    }

    return document.documentElement.classList.contains('dark') ? 'dark' : 'dark';
}

function applyThemeToDocument(mode) {
    const isDark = mode === 'dark';
    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.dataset.theme = mode;

    try {
        window.localStorage.setItem(THEME_STORAGE_KEY, mode);
    } catch {
        // Ignore storage write errors.
    }

    window.dispatchEvent(new CustomEvent('signature:theme-changed', { detail: { mode, isDark } }));
}

window.signatureThemePalette = () => {
    const isDark = document.documentElement.classList.contains('dark');

    return isDark
        ? {
              mode: 'dark',
              gold: '#d9b77a',
              champagne: '#f0dcb1',
              surface: '#16161a',
              grid: 'rgba(217, 183, 122, 0.10)',
              tick: '#a5a096',
              legend: '#f5f1ea',
              emerald: '#34d399',
              rose: '#f87171',
          }
        : {
              mode: 'light',
              gold: '#a97f38',
              champagne: '#d9b77a',
              surface: '#ffffff',
              grid: 'rgba(140, 102, 40, 0.12)',
              tick: '#585249',
              legend: '#16161a',
              emerald: '#0f766e',
              rose: '#be123c',
          };
};

Alpine.store('theme', {
    mode: resolveInitialTheme(),

    get isDark() {
        return this.mode === 'dark';
    },

    set(mode) {
        this.mode = mode === 'light' ? 'light' : 'dark';
        applyThemeToDocument(this.mode);
    },

    toggle() {
        this.set(this.isDark ? 'light' : 'dark');
    },
});

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();
