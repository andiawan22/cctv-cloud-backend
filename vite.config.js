import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Tambahkan blok server ini agar Vite mengirimkan aset menggunakan IP komputer Anda
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '172.20.22.28' 
        }
    }
});
