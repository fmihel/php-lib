import * as dom from './dom';
import * as react from './react';
import JX from './jx';
import ut from './ut';

export default {
    DOM: dom.DOM,
    DOMS: dom.DOMS,
    childDOM: dom.childDOM,
    $D: dom.$D,
    parentDOM: dom.parentDOM,

    binds: react.binds,
    flex: react.flex,
    flexChild: react.flexChild,
    propsToState: react.propsToState,
    defaultProps: react.defaultProps,

    JX,
    ut,
};
