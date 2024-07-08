import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/tailwind.css',
                'resources/js/tailwind.js', // Ensure JavaScript is included
            ],
            refresh: true,
        }),
    ],
});
