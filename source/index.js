import * as dom from './dom';
import * as react from './react';
import _JX from './jx';
import _ut from './ut';
import _storage, { Storage } from './storage';
import _dvc from './dvc';
import _scriptLoader from './scriptLoader';
import _Url from './url';

export const DOM = dom.DOM;
export const DOMS= dom.DOMS;
export const childDOM= dom.childDOM;
export const $D= dom.$D;
export const parentDOM= dom.parentDOM;

export const binds= react.binds;
export const flex= react.flex;
export const flexChild= react.flexChild;
export const propsToState= react.propsToState;
export const defaultProps= react.defaultProps;

export const JX=_JX;
export const ut=_ut;
export const storage=_storage;
export const dvc=_dvc;
export const scriptLoader=_scriptLoader;
export const Url = _Url;

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
    storage,
    Storage,
    dvc,
    scriptLoader,
    Url
};
