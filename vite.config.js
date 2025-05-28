import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";

export default defineConfig(({ mode }) => {
    // Ambil env variable dari process.env, fallback ke 0.0.0.0 jika kosong
    const devServerUrl =
        process.env.VITE_DEV_SERVER_URL || "http://0.0.0.0:5173";

    // Extract host dan port dari URL supaya bisa dipakai di server config Vite
    const url = new URL(devServerUrl);

    return {
        plugins: [
            laravel({
                input: ["resources/js/app.js"],
                refresh: true,
                // Inject dev server URL supaya Laravel Vite plugin tahu URL yang benar
                devServer: devServerUrl,
            }),
            vue(),
        ],
        server: {
            host: url.hostname, // misal '202.10.47.115' atau '0.0.0.0'
            port: parseInt(url.port, 10) || 5173,
        },
    };
});
