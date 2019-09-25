const dom = require('./source/dom');
const react = require('./source/react');
const JX = require('./source/jx');
const ut = require('./source/ut');

module.exports = {
    ...dom,
    ...react,
    JX,
    ut,
};
