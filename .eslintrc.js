module.exports = {
    "env": {
        "browser": true,
        "commonjs": true,
        "es6": true
    },
    "extends": "airbnb/base",
    "parserOptions": {
        "ecmaFeatures": {
            "jsx": true
        },
        "ecmaVersion": 2018,
        "sourceType": "module"
    },
    "plugins": [
        "react"
    ],
    "globals": {
        "$": true
        
    },
    "rules": {
        'no-console': 'off',
        'no-bitwise':'off',
        'no-undef':'warn',
        "max-len":0,
        'no-underscore-dangle':'off',
        'no-plusplus':'off',
        'import/no-extraneous-dependencies':'off',
        "indent": [
            "error",
            4
        ],
        "linebreak-style": [
            "error",
            "windows"
        ],
        "quotes": [
            "error",
            "single"
        ],
        "semi": [
            "error",
            "always"
        ]
    }
};