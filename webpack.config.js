const path = require('path');
const { defArg } = require('fmihel-server-lib');

const toRemotePath = defArg('path');
const toProduction = !toRemotePath && defArg('prod');

const SOURCE_PATH = './source/';
// const PUBLIC_PATH = toRemotePath ? 'C:/work/admin/node_modules/fmihel-browser-lib/dist/' : './dist/';
// const PUBLIC_PATH = toRemotePath ? 'C:/work/fmihel/windeco-components/node_modules/fmihel-browser-lib/dist/' : './dist/';
const PUBLIC_PATH = toRemotePath ? 'C:/work/windeco/order/node_modules/fmihel-browser-lib/dist/' : './dist/';

module.exports = {
    mode: toProduction ? 'production' : 'development',
    devtool: toProduction ? false : 'inline-source-map',

    entry: `${SOURCE_PATH}index.js`,
    output: {
        path: path.resolve(__dirname, PUBLIC_PATH),
        filename: `fmihel-browser-lib${(toProduction || toRemotePath) ? '.min' : ''}.js`,
        libraryTarget: 'umd',
        globalObject: 'this',
        library: 'fmihel-browser-lib',
    },
    externals: {
        'fmihel-lib': 'fmihel-lib',
        jquery: 'jquery',
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
