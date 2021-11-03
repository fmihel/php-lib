import $ from 'jquery';
import { ut } from 'fmihel-lib';
import { parentDOM } from './dom';

const JX = {
    _params: {
        screen: {
            x: 0, y: 0, w: 0, h: 0,
        },
        mouse: {
            x: 0, y: 0,
        },
        $list: {},
        styles: {},
        styleAttrName: `_styleAttr_${ut.random_str(5)}`, // имя атрибута, в котором храниться расчитанное style
    },
    /** проверка актуальности объекта jQuery
     *  если, хотябы один объект dom содержащийся в коллекции
     *  $obj не содержится в дереве документа, то считаем
     *  что $obj неактулен, и скорее всего его нужно перестроить
    */
    relevance($obj) {
        try {
            if ($obj.length === 0) return false;

            for (let i = 0; i < $obj.length; i++) {
                if (!document.body.contains($obj[i])) return false;
            }
        } catch (e) {
            return false;
        }
        return true;
    },
    /** коллекция объектов JQ, если нет объекта, будет создан
     * при запросе пыьается найти уже созданный, если его не существует или,
     * ранее созданный length = 0 , пыьается его создать.
     * Объекты можно группировать посредством указания группы param.group
     * без указания группы объекты помещаются в группу `common`
     * Ex:
     * JX.$('body');    аналогично JX.$('body',{group:'common'}); )
     * JX.$('#txt'); не аналогично JX.$('#txt',{group:'anyGroup'}),
     * хотя возвращаемые объекты  будет ссылаться на один и тот же DOM
    */
    $(selector, param = {}) {
        const t = JX;
        const { $list } = t._params;
        const p = {
            refresh: false,
            group: 'common',
            $parent: undefined,
            ...param,
        };
        let res;
        try {
            if (p.refresh
                || !(p.group in $list)
                || !(selector in $list[p.group])
                || (!t.relevance($list[p.group][selector]))
            ) {
                // console.info('recreate');
                res = p.$parent ? p.$parent.find(selector) : $(selector);
                if (!(p.group in $list)) {
                    $list[p.group] = {};
                }
                $list[p.group][selector] = res;
            } else {
                // console.info('from buffer');
                res = $list[p.group][selector];
            }
        } catch (e) {
            console.error(e);
        }

        return res;
    },
    window: $(window),
    // eslint-disable-next-line no-underscore-dangle
    _initialize() {
        JX.window.on('mousemove', (e) => {
            JX._params.mouse.x = e.originalEvent.clientX;
            JX._params.mouse.y = e.originalEvent.clientY;
        });

        const updateScreenSize = () => {
            JX._params.screen.w = JX.window.width();
            JX._params.screen.h = JX.window.height();
        };

        updateScreenSize();
        JX.window.on('resize', updateScreenSize);
    },
    /** возвращает computedStyle, */
    getStyle(dom, param = {}) {
        const t = JX;
        const p = {
            refresh: false,
            ...param,
        };
        let style;

        if (!(t._params.styleAttrName in dom) || (p.refresh)) {
            style = t.computedStyle(dom);
            // eslint-disable-next-line no-param-reassign
            dom[t._params.styleAttrName] = style;
        } else {
            style = dom[t._params.styleAttrName];
        }

        return style;
    },
    computedStyle(dom) {
        let view = dom.ownerDocument.defaultView;

        if (!view) {
            view = window;
        }

        return view.getComputedStyle(dom);
    },
    /**
     * координаты мыши
     * @return {x:int,y:int}
     */
    mouse() {
        return { x: JX._params.mouse.x, y: JX._params.mouse.y };
    },
    /**
     * размер экрана браузера
     * @return {x:0,y:0,w:int,h:int}
     */
    screen() {
        return {
            x: 0,
            y: 0,
            w: JX._params.screen.w,
            h: JX._params.screen.h,
        };
    },
    pos(dom, bound = undefined) {
        if (bound === undefined) {
            return {
                x: dom.offsetLeft + 1,
                y: dom.offsetTop + 1,
                w: dom.offsetWidth,
                h: dom.offsetHeight,
            };
        }
        // console.warn('pos(dom,bound) is not released !!!');
        return undefined;
    },
    abs(dom, bound = undefined) {
        if (bound === undefined) {
            const w = dom.getBoundingClientRect();
            return {
                x: w.left + window.pageXOffset,
                y: w.top + window.pageYOffset,
                w: w.width,
                h: w.height,
            };
        }
        // console.warn('abs(dom,bound) is not released !!!');
        return undefined;
    },

    visible(dom, param = undefined, visibleMean = 'block') {
        const display = () => {
            if ((dom.style === undefined) || (dom.style.display === undefined) || (dom.style.display === '')) {
                return JX.computedStyle(dom).display;
            }
            return dom.style.display;
        };

        if (param === undefined) {
            return (display() !== 'none');
        }
        if (param === 'deep') {
            if (display() === 'none') {
                return false;
            }
            if (dom.tagName === 'BODY') {
                return true;
            }
            return JX.visible(parentDOM(dom), 'deep');
        }

        // eslint-disable-next-line no-param-reassign
        dom.style.display = param ? visibleMean : 'none';
        return !!param;
    },
    /** длина текста в пикселях */
    textSize(text, param = {}) {
        const t = JX;
        const p = {
            parentDom: t.$('body')[0],
            fontFamily: undefined,
            fontSize: undefined,
            refresh: false,
            width: 0,
            ...param,
        };
        const style = (p.fontFamily === undefined || p.fontSize === undefined) ? t.getStyle(p.parentDom, { refresh: p.refresh }) : undefined;

        if (p.fontFamily === undefined) {
            p.fontFamily = style.fontFamily;
        }
        if (p.fontSize === undefined) {
            p.fontSize = style.fontSize;
        }

        const str = document.createTextNode(text);
        const obj = document.createElement('div');

        obj.style.fontSize = Number.isInteger(p.fontSize) ? `${p.fontSize}px` : p.fontSize;
        obj.style.fontFamily = p.fontFamily;
        obj.style.margin = `${0}px`;
        obj.style.padding = `${0}px`;
        obj.style.position = 'absolute';
        if (p.width === 0) {
            obj.style.whiteSpace = 'nowrap';
        } else {
            const w = Number.isInteger(p.width) ? `${p.width}px` : p.width;
            obj.style.width = w;
            obj.style.minWidth = w;
            obj.style.maxWidth = w;
        }
        obj.appendChild(str);

        document.body.appendChild(obj);
        const res = { w: obj.offsetWidth, h: obj.offsetHeight };
        document.body.removeChild(obj);

        return res;
    },
    /** скролирует объект scroll:DOM до момента, пока to:DOM не окажется в области видимости */
    scroll(scroll, to, param = {}) {
        const a = {
            animate: 0,
            off: 0,
            alg: 'simple', /* reach */
            ...param,
        };
        const posTar = JX.abs(to);
        const posScr = JX.abs(scroll);
        const $scroll = $(scroll);
        let delta;

        if (a.alg === 'reach') {
            if ((posTar.h > posScr.h) || (posTar.y < posScr.y)) {
                delta = posTar.y - posScr.y + $scroll.scrollTop() - a.off;
            } else {
                delta = posTar.y - (posScr.y + posScr.h - posTar.h) + $scroll.scrollTop() + a.off;
            }
        } else {
            delta = posTar.y - posScr.y + $scroll.scrollTop() - a.off;
        }

        if (a.animate === 0) {
            $scroll.scrollTop(delta);
        } else {
            $scroll.animate({ scrollTop: delta }, a.animate);
        }
    },
};

// eslint-disable-next-line func-names
(function () {
    JX._initialize();
}());

export default JX;
