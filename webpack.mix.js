let mix = require('laravel-mix');

mix.ts('assets/scripts/frontend/frontend.ts', 'dist/stoic-wp.js')
    .sass('assets/styles/frontend/frontend.scss', 'stoic-wp.css')
    .sass('assets/styles/frontend/critical.scss', '')
    .options({
        processCssUrls: false
    })
    .copyDirectory('assets/fonts', 'dist/fonts')
    .version()
    .setPublicPath('dist');