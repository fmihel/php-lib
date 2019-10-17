const dom = require('./source/dom');
const react = require('./source/react');
const JX = require('./source/jx').default;

module.exports = {
    ...dom,
    ...react,
    JX,
};
