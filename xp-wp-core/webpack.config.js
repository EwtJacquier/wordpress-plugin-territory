const glob = require( 'glob' )
const path = require( 'path' )
//const MiniCssExtractPlugin = require('mini-css-extract-plugin');
//const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
//const TerserPlugin = require("terser-webpack-plugin");

module.exports = {
  mode: 'none',
  entry: glob.sync('./templates/**/assets/js/src/**.js').reduce(function(obj, el) {

      const name = path.parse(el).name
      const entry = el.replace('/src/'+name+'.js', '/build/'+name )

      obj[entry] = el

      return obj
    },{}),
  output: {
    filename: '[name].bundle.js',
    path: path.resolve( __dirname )
  }
}

// Deixar aqui comentado por enquanto apenas para referencia, ate ver se implantaremos isso dessa forma msm

//module.exports = {
  /*
  stats: {
    warnings: false
  },
  watch: true,
  watchOptions: {
    ignored: /node_modules/
  },
  entry: {
    /* JS */
    //'admin/build/admin.min': './assets/js/admin/src/admin.js',    
    //'build/compliance.min': './assets/js/src/compliance.js',
    
    /* CSS */
    //'admin/build/admin': './assets/css/admin/src/admin.scss',
    //'build/coronavirus': './assets/css/src/coronavirus.scss',
  /*},
  output: {
    filename: (pathData) => {
      return pathData.chunk.name.indexOf('.min') > 0 ? './assets/js/[name].js' : './tmp/[name].js'
    },
    path: path.resolve(__dirname, './')
  },
  module: {
    rules: [
      {
        test: /.s?css$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader'
        ],
      },
    ],
  },
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        terserOptions: {}
      }),
      new CssMinimizerPlugin(),
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: './assets/css/[name].css',
      ignoreOrder: true
    })
  ],*/
//};