import $ from 'jquery';
/* global jQuery */
/**
 * Возвращает объект DOM с использованием селектора
 * Более быстрый аналог jquery $('xxx')[0];
 * @param {string} selector - строка запроса '#xxx'  '.xxx'  'xxx'
 * @param {DOM|false} _parentDOM - родительский элемент в котором будет поиск
 * @return DOM or null
 */
export function DOM(selector, _parentDOM = false) {
    const own = _parentDOM || document;
    try {
        if (selector[0] === '#') {
            return own.getElementById(selector.substring(1));
        } if (selector[0] === '.') {
            return own.getElementsByClassName(selector.substring(1))[0];
        } return own.getElementsByTagName(selector)[0];
    } catch (e) {
        return null;
    }
}
/**
 * Возвращает массив объектов DOM с использованием селектора
 * @param {string} selector - строка запроса '#xxx'  '.xxx'  'xxx'
 * @param {DOM|false} _parentDOM - родительский элемент в котором будет поиск
 * @return [DOM,DOM,DOM...] or []
 */
export function DOMS(selector, _parentDOM = false) {
    const own = _parentDOM || document;
    try {
        return own.querySelectorAll(selector);
    } catch (e) {
        return [];
    }
}
/**
 * Аналогично вызову $.data(obj,'data') или $.data(obj,'data',newobj)
 * @param  {...any} p - если параметр один. то возвращает значение data если два,
 * то устанавливает в data значение второго параметра
 */
export function $D(...p) {
    if (p.length === 0) { throw new Error('$D must have one or two param'); }
    const o = p[0] instanceof jQuery ? p[0][0] : p[0];
    if (p.length === 1) {
        return $.data(o, 'data');
    }
    $.data(o, 'data', p[1]);
    return undefined;
}

/**
 * Возвращает объект родительский DOM с использованием селектора
 * @param {string|DOM} selector - строка запроса '#xxx'  '.xxx'  'xxx' , или объект DOM от которого ищем родителя
 * @param {DOM|false} parentDOM - родительский элемент в котором будет поиск
 * @return DOM or null
 */
export function parentDOM(selector, _parentDOM = false) {
    try {
        if (typeof selector === 'string') {
            const dom = DOM(selector, _parentDOM);
            if (dom) {
                return dom.parentNode;
            }
        } else {
            return selector.parentNode;
        }
    } catch (e) {
        console.error('parentDOM', e);
    }
    return null;
}

/**
 * Возвращает ссылку на подчиненные объекты DOM
 * @param {string|DOM} selector - строка запроса '#xxx'  '.xxx'  'xxx' , или объект DOM от которого ищем родителя
 * @param {DOM|false} parentDOM - родительский элемент в котором будет поиск
 * @return DOM or null
 */
export function childDOM(selector, _parentDOM = false) {
    try {
        if (typeof selector === 'string') {
            const dom = DOM(selector, _parentDOM);
            if (dom) {
                return dom.children;
            }
        } else {
            return selector.children;
        }
    } catch (e) {
        console.error('childDOM', e);
    }
    return null;
}
