let mix = require('laravel-mix');

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

mix.js('resources/assets/js/vueapp.js', 'public/js');
mix.js('resources/assets/js/vueadmin.js', 'public/js');
//mix.js('resources/assets/js/vueapp.js', 'public/js').extract(['vue', 'axios', 'jquery']);

//mix.sass('resources/assets/sass/app.scss', 'public/css');
