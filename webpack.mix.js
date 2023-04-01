let mix = require('laravel-mix');

mix
    .setResourceRoot('/vendor/statamic-phone-number-fieldtype')
    .setPublicPath('public')
    .js('node_modules/intl-tel-input/build/js/utils.js', 'public/js/utils.js')
    .js('src/resources/js/addon.js', 'public/js/addon.js')
    .vue();