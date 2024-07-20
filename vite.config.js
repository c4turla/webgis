import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/tailwind.css',
                'resources/css/map.css',
                'resources/js/tailwind.js', // Ensure JavaScript is included
                'resources/js/map.js',
            ],
            refresh: true,
        }),
    ],
});
