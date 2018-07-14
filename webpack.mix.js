const mix = require('laravel-mix');

const path = require('path');

const Vue = require('vue');
const VueI18n = require('vue-i18n');
const i18nExtensions = require('vue-i18n-extensions');
Vue.use(VueI18n);

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

const i18n = new VueI18n({
  locale: 'ja',
  messages: require(path.join(__dirname, 'resources/assets/js/app/strings.json')),
});

mix.extend('vuePug', (webpackConfig, ...args) => {
  for (const rule of webpackConfig.module.rules) {
    if ('vue-loader' !== rule.loader) {
      continue;
    }
    rule.options.loaders.pug = {
      loader: 'pug-html-loader',
      options: { basedir: path.resolve(__dirname, 'resources/assets/js') },
    }
  }
});

mix.extend('vueI18n', (webpackConfig, ...args) => {
  for (const rule of webpackConfig.module.rules) {
    if ('vue-loader' !== rule.loader) {
      continue;
    }
    rule.options.compilerModules = rule.options.compilerModules || [];
    rule.options.compilerModules.push(i18nExtensions.module(i18n));
    console.log('vue-i18n-extensionsモジュールを導入しました');
  }
});

mix.js('resources/assets/js/app.js', 'public/js')
  .webpackConfig({
    resolve: {
      extensions: ['.js', '.json', '.vue'],
      alias: {
        '@': path.resolve(__dirname, 'resources/assets/js'),
      },
    },
  })
  .vuePug()
  .vueI18n()
  .sass('resources/assets/sass/app.scss', 'public/css');
