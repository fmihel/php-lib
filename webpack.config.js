const path = require('path');
const webpack = require('webpack');
// const HtmlWebPackPlugin = require('html-webpack-plugin');
// const CopyWebpackPlugin = require('copy-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { defArg } = require('fmihel-server-lib');


const toProduction = defArg('prod');

const SOURCE_PATH = './source/';
const PUBLIC_PATH = './dist/';
const PORT = 3000;
module.exports = {
    mode: toProduction ? 'production' : 'development',
    devtool: toProduction ? false : 'inline-source-map',
    devServer: {
        // contentBase: path.join(__dirname, 'public'),
        // watchContentBase: true,

        port: PORT,
    },

    entry: `${SOURCE_PATH}index.js`,
    output: {
        path: path.resolve(__dirname, PUBLIC_PATH),
        filename: `fmihel-lib${toProduction ? '.min.' : '.'}js`,
        libraryTarget: 'umd',
        globalObject: 'this',
        library: 'fmihel-lib',
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
        // new CleanWebpackPlugin(),
    ],
};
