import resolve from 'rollup-plugin-node-resolve';
import babel from 'rollup-plugin-babel';
import { terser } from 'rollup-plugin-terser';
import commonJs from 'rollup-plugin-commonjs';

export default {
    input: './source/index.js',
    output: {
        file: './dist/fmihel-lib.min.js',
        format: 'iife', // umd cjs iife
        name: 'fmihel_lib',
    },
    plugins: [

        resolve(),
        commonJs(),
        babel({
            exclude: 'node_modules/**', // only transpile our source code
        }),
        terser(),
    ],

};
