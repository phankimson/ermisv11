const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/socket.js', 'public/mix')
   //.js('resources/js/app.js', 'public/js')
   //.sass('resources/sass/app.scss', 'public/css');
   const WebpackShellPluginNext = require('webpack-shell-plugin-next');

   // Add shell command plugin configured to create JavaScript language file
   mix.webpackConfig({
       plugins:
       [
           new WebpackShellPluginNext({onBuildStart:['php artisan lang:js --quiet'], onBuildEnd:[]})
       ]
   });
