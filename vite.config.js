import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  build: {
    assetsDir: ".",
    emptyOutDir: true,
    manifest: true,
    outDir: "dist",
    sourcemap: true,
    rollupOptions: {
      input: ["src/js/main.js"],
    },
  },
  plugins: [
    {
      name: "php-full-reload",
      handleHotUpdate({ file, server }) {
        if (file.endsWith(".php")) {
          server.ws.send({ type: "full-reload", path: "*" });
        }
      },
    },
    tailwindcss(),
  ],
});
