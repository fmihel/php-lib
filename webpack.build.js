const path = require('path');
// const webpack = require('webpack');
// const HtmlWebPackPlugin = require('html-webpack-plugin');
// const CopyWebpackPlugin = require('copy-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');


const SOURCE_PATH = './source/';
const PUBLIC_PATH = './dist/';

module.exports = {
    mode: 'production',
    // devtool: 'source-map',
    entry: `${SOURCE_PATH}index.js`,
    output: {
        path: path.resolve(__dirname, PUBLIC_PATH),
        filename: 'index.js',
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                },
            },
        ],
    },
    plugins: [
        new CleanWebpackPlugin(),
        // new webpack.ProvidePlugin({
        //    $: 'jquery',
        //    jQuery: 'jquery',
        // }),

    ],
};
