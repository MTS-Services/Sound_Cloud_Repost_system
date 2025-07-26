import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
export default defineConfig({
    // server: {
    //     host: '0.0.0.0',
    //     port: 3333,
    //     strictPort: true,
    //     hmr: {
    //         host: '192.168.10.59',
    //     }
    // },
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/dashboard.css',
                'resources/css/user-dashboard.css',
                'resources/css/frontend.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
