let mix = require('laravel-mix');

mix.ts('assets/scripts/frontend/frontend.ts', 'dist/stoicwp.js')
    .sass('assets/styles/frontend/frontend.scss', 'stoicwp.css')
    .sass('assets/styles/frontend/critical.scss', '')
    .options({
        processCssUrls: false
    })
    .copyDirectory('assets/fonts', 'dist/fonts')
    .version()
    .setPublicPath('dist');