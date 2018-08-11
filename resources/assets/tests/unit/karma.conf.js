/* eslint-env node */
const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const mixWebpackConfig = require('laravel-mix/setup/webpack.config');

const webpackConfig = merge.smart(mixWebpackConfig, {
  devtool: 'inline-source-map',
  plugins: [
    new webpack.DefinePlugin({ 'process.env': '"test"' }),
  ],
});
delete webpackConfig.entry;
const commonsChunkPluginIndex = webpackConfig.plugins.findIndex(plugin => plugin.chunkNames);
webpackConfig.plugins.splice(commonsChunkPluginIndex, 1);

module.exports = function(config) {
  config.set({
    concurrency: 1,
    frameworks: ['mocha', 'power-assert', 'sinon'],
    files: ['./index.js'],
    preprocessors: { './index.js': ['webpack', 'sourcemap'] },
    reporters: ['spec', 'html', 'coverage'],
    browsers: [
      'ChromiumHeadless',
      'FirefoxHeadless',
      path.resolve(__dirname, './vagrant-ie.sh'),
      path.resolve(__dirname, './vagrant-edge.sh'),
    ],
    webpack: webpackConfig,
    htmlReporter: {
      outputDir: path.resolve(__dirname, '../unit-result'),
      namedFiles: true,
      urlFriendlyName: true,
    },
    coverageReporter: {
      dir: path.resolve(__dirname, '../unit-result'),
      reporters: [{
        type: 'html',
        subdir: browserName => 'cov-' + browserName,
      }],
    },
  });
};
