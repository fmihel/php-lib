const path = require('path');
const { defArg } = require('fmihel-server-lib');


const toProduction = defArg('prod');

const SOURCE_PATH = './source/';
const PUBLIC_PATH = './dist/';
module.exports = {
    mode: toProduction ? 'production' : 'development',
    devtool: toProduction ? false : 'inline-source-map',

    entry: `${SOURCE_PATH}index.js`,
    output: {
        path: path.resolve(__dirname, PUBLIC_PATH),
        filename: `fmihel-browser-lib${toProduction ? '.min.' : '.'}js`,
        libraryTarget: 'umd',
        globalObject: 'this',
        library: 'fmihel-browser-lib',
    },
    externals: {
        'fmihel-lib': 'fm',
        jquery: 'jQuery',
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
