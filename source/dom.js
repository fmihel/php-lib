/**
 * Возвращает объект DOM с использованием селектора
 * Более быстрый аналог jquery $('xxx')[0];
 * @param {string} selector - строка запроса '#xxx'  '.xxx'  'xxx'
 * @param {DOM|false} parentDOM - родительский элемент в котором будет поиск
 * @return DOM or null
 */
function DOM(selector, parentDOM = false) {
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
function DOMS(selector, parentDOM = false) {
    const own = parentDOM || document;
    try {
        return own.querySelectorAll(selector);
    } catch (e) {
        return [];
    }
}

module.exports = {
    DOM,DOMS
};

