const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


mix.js('resources/js/limitless/app.js', 'public/js')
    .sass('resources/sass/limitless/app.scss', 'public/css')
    .setPublicPath('public')
    .setResourceRoot('../')
    .styles([
                'node_modules/sweetalert2/dist/sweetalert2.css'
            ], 
        'public/css/plugins.bundle.css'
    )
    .scripts([
                'resources/js/limitless/plugins/ui/moment/moment.min.js',
                'node_modules/sweetalert2/dist/sweetalert2.all.js',
                'resources/js/limitless/plugins/tables/datatables/datatables.min.js',
                'resources/js/limitless/plugins/tables/datatables/extensions/fixed_columns.min.js',
                'resources/js/limitless/plugins/forms/selects/select2.min.js',
                'resources/js/limitless/plugins/extensions/jquery_ui/interactions.min.js',
                'resources/js/limitless/plugins/forms/styling/uniform.min.js',
                'resources/js/limitless/plugins/pickers/daterangepicker.js'
            ], 
        'public/js/plugins.bundle.js'
    );