import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/admin.css',
                'resources/css/restaurant.css',
                'resources/css/menu.css',
                'resources/js/admin.js',
                'resources/js/restaurant.js',
                'resources/js/menu.js',
            ],
            refresh: true,
            publicDirectory: 'public_html',
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
