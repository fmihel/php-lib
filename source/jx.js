import $ from 'jquery';

const JX = {
    _params: {
        screen: {
            x: 0, y: 0, w: 0, h: 0,
        },
        mouse: {
            x: 0, y: 0,
        },
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
};

// eslint-disable-next-line func-names
(function () {
    JX._initialize();
}());

export default JX;
