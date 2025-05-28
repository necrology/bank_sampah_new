import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), "");

    const devServerUrl = env.VITE_DEV_SERVER_URL || "http://localhost:5173";
    const url = new URL(devServerUrl);

    return {
        plugins: [
            laravel({
                input: ["resources/js/app.js"],
                refresh: true,
                devServer: devServerUrl,
            }),
            vue(),
        ],
        server: {
            host: url.hostname,
            port: Number(url.port),
        },
    };
});
