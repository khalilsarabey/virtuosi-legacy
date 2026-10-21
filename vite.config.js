import { defineConfig } from 'vite';

// The layouts load the bundle as classic scripts (/js/app.js, /css/app.css) in a
// fixed order next to vendored jQuery plugins, so build a single IIFE with stable
// file names instead of hashed ES modules.
export default defineConfig({
    publicDir: false,
    build: {
        outDir: 'public',
        emptyOutDir: false,
        cssCodeSplit: false,
        rollupOptions: {
            input: 'resources/js/app.js',
            output: {
                format: 'iife',
                entryFileNames: 'js/app.js',
                assetFileNames: (asset) => (asset.names?.[0] ?? asset.name ?? '').endsWith('.css') ? 'css/app.css' : 'assets/[name][extname]',
            },
        },
    },
});
