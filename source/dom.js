/* global jQuery */
/**
 * Возвращает объект DOM с использованием селектора
 * Более быстрый аналог jquery $('xxx')[0];
 * @param {string} selector - строка запроса '#xxx'  '.xxx'  'xxx'
 * @param {DOM|false} parentDOM - родительский элемент в котором будет поиск
 * @return DOM or null
 */
export function DOM(selector, parentDOM = false) {
    const own = parentDOM || document;
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
 * @param {DOM|false} parentDOM - родительский элемент в котором будет поиск
 * @return [DOM,DOM,DOM...] or []
 */
export function DOMS(selector, parentDOM = false) {
    const own = parentDOM || document;
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

export default {
    DOM,
    DOMS,
    $D,
};
