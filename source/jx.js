import $ from 'jquery';
import { DOM, parentDOM } from './dom';

const JX = {
    _params: {
        screen: {
            x: 0, y: 0, w: 0, h: 0,
        },
        mouse: {
            x: 0, y: 0,
        },
        $list: {},
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
                || ($list[p.group][selector].length === 0)
            ) {
                res = p.$parent ? p.$parent.find(selector) : $(selector);
                if (!(p.group in $list)) {
                    $list[p.group] = {};
                }
                $list[p.group][selector] = res;
            } else {
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
                x: Math.round(w.left + window.pageXOffset),
                y: Math.round(w.top + window.pageYOffset),
                w: Math.round(w.width),
                h: Math.round(w.height),
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
            $parent: t.$('body'),
            font: undefined,
            size: undefined,
            ...param,
        };

        if (p.font === undefined) {
            p.font = p.$parent.css('font-family');
        }
        if (p.size === undefined) {
            p.size = p.$parent.css('font-size');
        }

        const str = document.createTextNode(text);
        const obj = document.createElement('div');

        obj.style.fontSize = Number.isInteger(p.size) ? `${p.size}px` : p.size;
        obj.style.fontFamily = p.font;
        obj.style.margin = `${0}px`;
        obj.style.padding = `${0}px`;
        obj.style.position = 'absolute';
        obj.style.whiteSpace = 'nowrap';
        obj.appendChild(str);

        document.body.appendChild(obj);
        const res = { w: obj.offsetWidth, h: obj.offsetHeight };
        document.body.removeChild(obj);

        return res;
    },
};

// eslint-disable-next-line func-names
(function () {
    JX._initialize();
}());

export default JX;
