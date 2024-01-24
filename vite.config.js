import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        https: false,
        host: true,
        port: 5173,
        hmr: {host: 'localhost', protocol: 'ws'},
    },
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/webhooks.js',
            ],
            refresh: true,
        }),
    ],
});
