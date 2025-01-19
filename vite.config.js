import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                // 'resources/css/filament/knowledge-base/theme.css', // Add your custom styles here
                // 'resources/js/filament/knowledge-base/taiwind.config.js',  // Add your custom scripts here, if needed
            ],
            refresh: ["app/Livewire/**"],
        }),
        // filament({
        //     plugins: [
        //         // Define a custom theme for the Knowledge Base panel
        //         {
        //             name: 'knowledge-base',
        //             input: [
        //                 'resources/css/filament/knowledge-base/theme.css', // Add your custom styles here
        //                 'resources/js/filament/knowledge-base/taiwind.config.js',  // Add your custom scripts here, if needed
        //             ],
        //         },
        //     ],
        // }),
    ],
});
