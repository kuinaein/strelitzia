/* eslint-env node */
const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const mixWebpackConfig = require('laravel-mix/setup/webpack.config');

const webpackConfig = merge.smart(mixWebpackConfig, {
  devtool: 'inline-source-map',
  plugins: [
    new webpack.DefinePlugin({
      'process.env': '"test"'
    })
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, '../../js'),
    },
  },
});
delete webpackConfig.entry;
const commonsChunkPluginIndex = webpackConfig.plugins.findIndex(plugin => plugin.chunkNames);
webpackConfig.plugins.splice(commonsChunkPluginIndex, 1);

module.exports = function(config) {
  config.set({
    frameworks: ['mocha', 'power-assert', 'sinon'],
    files: ['./index.js'],
    preprocessors: { './index.js': ['webpack', 'sourcemap'] },
    reporters: ['spec', 'html', 'coverage'],
    browsers: ['ChromiumHeadless'],
    webpack: webpackConfig,
    htmlReporter: {
      outputDir: path.resolve(__dirname, '..'),
      reportName: 'unit-result',
    },
    coverageReporter: {
      dir: path.resolve(__dirname, '../unit-result/coverage'),
      reporters: [{type: 'html', subdir: '.'}],
    },
  });
};
