const TerserPlugin = require('terser-webpack-plugin');

var path = require('path');

module.exports = {
  
  optimization: {
    minimizer: [new TerserPlugin({
      extractComments: false,
    })],
  },

  performance: {
    hints: false,
    maxEntrypointSize: 512000,
    maxAssetSize: 512000
  },

  mode: "production",
  entry: {
    base: './assets/js/srcs/index.js',
  },
  output: {
    filename: '[name].min.js',
    path: __dirname + '/assets/js/global',
  },
    
};