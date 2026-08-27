import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'admin-badge-green', 'admin-badge-yellow', 'admin-badge-red', 'admin-badge-blue', 'admin-badge-gray',
        'admin-btn-green', 'admin-btn-blue', 'admin-btn-gray', 'admin-btn-yellow', 'admin-btn-red',
        'admin-action-blue', 'admin-action-green', 'admin-action-yellow', 'admin-action-red',
        'client-badge-green', 'client-badge-yellow', 'client-badge-blue', 'client-badge-gray',
        'client-btn-blue', 'client-btn-gray',
        'analyst-badge-green', 'analyst-badge-yellow', 'analyst-badge-blue', 'analyst-badge-gray',
        'analyst-btn-blue', 'analyst-btn-gray',
        'pastille-verte', 'pastille-jaune', 'pastille-rouge',
    ],

    theme: {
        extend: {
            colors: {
                pq: {
                    'dark': '#0f172a',
                    'dark-2': '#111f3a',
                    'surface': '#1e293b',
                    'green': '#22c55e',
                    'green-light': '#4ade80',
                    'green-dark': '#16a34a',
                    'teal': '#018880',
                    'heading': '#003a36',
                    'text': '#444d4d',
                    'border': '#e2e8f0',
                    'bg': '#f6f9f9',
                },
            },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                heading: ['Raleway', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                pill: '30px',
            },
            boxShadow: {
                'pq-nav': '0 4px 20px rgba(0, 0, 0, 0.25)',
                'pq-card': '0 10px 30px rgba(0, 58, 54, 0.06)',
                'pq-card-hover': '0 16px 40px rgba(0, 58, 54, 0.1)',
                'pq-stat': '0 6px 20px rgba(0, 58, 54, 0.05)',
                'pq-stat-hover': '0 12px 30px rgba(0, 58, 54, 0.1)',
                'pq-table': '0 8px 24px rgba(0, 58, 54, 0.05)',
                'pq-green': '0 6px 16px rgba(34, 197, 94, 0.3)',
                'pq-green-lg': '0 8px 20px rgba(34, 197, 94, 0.35)',
                'pq-login': '0 20px 50px rgba(0, 0, 0, 0.35)',
                'pq-focus': '0 0 0 3px rgba(34, 197, 94, 0.15)',
                'pq-dot-green': '0 0 0 3px rgba(34, 197, 94, 0.18)',
                'pq-dot-yellow': '0 0 0 3px rgba(234, 179, 8, 0.18)',
                'pq-dot-red': '0 0 0 3px rgba(220, 38, 38, 0.18)',
            },
            backgroundImage: {
                'pq-nav': 'linear-gradient(180deg, #0f172a 0%, #111f3a 100%)',
                'pq-header': 'linear-gradient(180deg, #111f3a 0%, #16233f 100%)',
                'pq-login': 'linear-gradient(135deg, #0f172a 0%, #111f3a 55%, #16233f 100%)',
                'pq-underline': 'linear-gradient(90deg, #22c55e, #4ade80)',
            },
        },
    },

    plugins: [forms],
};
