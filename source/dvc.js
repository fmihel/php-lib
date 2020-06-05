/* global JX,jhandler,navigator */
/**
 * dvc - информация о габаритах устройства
 * Основной задачей данного класса я вляется определение момента
 * смены ландшафтной ориентации экрана на портретную и обратно,
 * а так же возвожность пересчета из системы координат броузера
 * в метрическую и обратно.
 *
 * Проверка смены ориентации
 * dvs.onOrientation(function(){
 *
 *        //произошла смена ориентации
 *
 *});
 *
 *
 * Пересчет из ск броузера в мм
 * dvs.toMM(px);
 *
 * обратно
 * dvs.toPX(mm);
 *
 *
 *
 */


class Dvc {
    constructor() {
        this.os = 'Windows';
        this.platform = 'PC';
        this.mobile = false;
        this.landscape = undefined;
        this.tablet_area = 80; /* область в мм, по которой вычисляем габаритность устройства, если хотябы одна сторона меньше, то устройство маленький тел, в противном случае планшет */
        this.devicePixelRatio = 1;
        this.device = { w: 0, h: 0 }; /* размер в px устройства ( то что заявил производитель) */
        this.browser = { w: 0, h: 0 }; /* размер в px броузера (может не совпадать с устройством) */
        this.viewport = { w: 0, h: 0 }; /* размер в px видимой области броузера (изменяется когда на мобильном появляется клавиатура и т.д. в ск броузера) */
        this.size = { w: 0, h: 0 }; /* габариты устройства в мм */
        this.overallness = 'monitor'; /* габаритность устройства (монитор, телефон, планшет) monitor|phone|tablet */
        this.kmm = 1.55; /* коэф пикселизации, найден экспериментальным путем :( */
        this.isIE = false;
        /* zoom:1, коэфф масшабирования */
        this.browserName = 'uncknown';
        this.chromium = false;

        this._timer = null;
        this._handler = undefined;
        this._landscaping = 0;
        this._sfull = false;

        const t = this;


        this._browserName();

        this._platform();
        this._landscape();
        this._updateArea();
        /*
        this._timer = setInterval(
            () => {
                t._update();
            },
            100,
        );
        */
        this.chromium = ((t.browserName === 'chrome') || (t.browserName === 'opera'));
    }

    // eslint-disable-next-line class-methods-use-this
    // onOrientation(o) {
    // dvc._handler.add(o);
    // }

    toMM(px) {
        const t = this;
        return JX.translate(px, 0, t.browser.w, 0, t.size.w);
    }

    toPX(mm) {
        const t = this;
        return JX.translate(mm, 0, t.size.w, 0, t.browser.w);
    }


    fullscreen(enable = undefined/* bool or undef */) {
        if (enable === undefined) {
        // return (document.fullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled);
            const fs = (document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
            return ((fs !== null) && (fs !== undefined));
        }
        const dom = document.documentElement;

        if (enable) {
            if (dom.requestFullscreen) dom.requestFullscreen();
            else if (dom.mozRequestFullScreen) dom.mozRequestFullScreen();
            else if (dom.webkitRequestFullscreen) dom.webkitRequestFullscreen();
            else if (dom.msRequestFullscreen) dom.msRequestFullscreen();
        } else if (document.cancelFullscreen) document.cancelFullscreen();
        else if (document.exitFullscreen) document.exitFullscreen();
        else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
        else if (document.msExitFullscreen) document.msExitFullscreen();

        this.landscape = undefined;
        return undefined;
    }

    _browserName() {
        const t = this;
        const agent = navigator.userAgent;

        if ((agent.search(/MSIE/) > -1) || (agent.search(/\.NET/) > -1)) t.browserName = 'ie';
        else if ((agent.search(/Edge/) > -1)) t.browserName = 'edge';
        else if ((agent.search(/Firefox/) > -1)) t.browserName = 'firefox';
        else if ((agent.search(/Opera/) > -1) || (agent.search(/OPR\//) > -1)) t.browserName = 'opera';
        else if ((agent.search(/Chrome/) > -1)) t.browserName = 'chrome';
        else if ((agent.search(/Safari/) > -1)) t.browserName = 'safari';
        t.isIE = (t.browserName === 'ie');
    }


    _update() {
        const t = this; const h = t._handler; const fs = t.fullscreen();

        if (t._landscape() || (t._sfull !== fs)) {
            if (t._tua !== undefined) clearTimeout(t._tua);
            t._sfull = fs;
            t._tua = setTimeout(() => {
                t._updateArea();
                h.do('all');
                t._tua = undefined;
            }, 500);
        }
    }

    _updateArea() {
        const t = this;

        t._dpr();

        t._viewport();
        t._device();
        t._browser();
        t._size();
        t._overallness();
    }

    _viewport() {
        const t = this; const r = { w: window.innerWidth, h: window.innerHeight };
        if (r.w != t.viewport.w && r.h != t.viewport.h) {
            t.viewport = r;
            return true;
        }
        return false;
    }

    _platform() {
        const t = this;
        if (navigator.userAgent.match(/Android/i)) {
            t.os = 'Android';
            t.platform = 'uncknown';
            t.mobile = true;
        } else if (navigator.userAgent.match(/BlackBerry/i)) {
            t.os = 'BlackBerry';
            t.platform = 'BlackBerry';
            t.mobile = true;
        } else if (navigator.userAgent.match(/iPhone/i)) {
            t.os = 'iOS';
            t.platform = 'iPhone';
            t.mobile = true;
        } else if (navigator.userAgent.match(/iPad/i)) {
            t.os = 'iOS';
            t.platform = 'iPad';
            t.mobile = true;
        } else if (navigator.userAgent.match(/iPod/i)) {
            t.os = 'iOS';
            t.platform = 'iPod';
            t.mobile = true;
        } else if (navigator.userAgent.match(/Opera Mini/i)) {
            t.os = 'OperaMini';
            t.platform = 'uncknown';
            t.mobile = true;
        } else if (navigator.userAgent.match(/IEMobile/i)) {
            t.os = 'Windows';
            t.platform = 'uncknown';
            t.mobile = true;
        }
    }

    _landscape() {
        const t = this; let l = (window.innerHeight < window.innerWidth); let res = false;

        // eslint-disable-next-line no-restricted-globals
        if ((t.mobile) && (screen) && (screen.orientation) && (screen.orientation.type)) l = (screen.orientation.type === 'landscape-primary');

        res = (l !== t.landscape);
        t.landscape = l;

        return res;
    }

    // eslint-disable-next-line class-methods-use-this
    zoom() {
        // eslint-disable-next-line no-restricted-globals
        const r = (window.devicePixelRatio ? window.devicePixelRatio : Math.sqrt(screen.deviceXDPI * screen.deviceYDPI) / 96) * screen.width;


        // eslint-disable-next-line no-mixed-operators
        return Math.round((r / window.screen.width) * 100);
    }

    _dpr() {
        // eslint-disable-next-line no-restricted-globals
        const t = this; const r = (window.devicePixelRatio ? window.devicePixelRatio : Math.sqrt(screen.deviceXDPI * screen.deviceYDPI) / 96);

        if (r !== t.devicePixelRatio) {
            t.devicePixelRatio = r;
            return true;
        }
        return false;
    }

    _device() {
        // eslint-disable-next-line no-restricted-globals
        const t = this; const r = { w: screen.width * t.devicePixelRatio, h: screen.height * t.devicePixelRatio };
        if (r.w != t.device.w || r.h != t.device.h) {
            t.device = r;
            return true;
        }
        return false;
    }

    _browser() {
        const t = this; let r;

        if (t.mobile) {
            r = {
                w: window.innerWidth,
                // eslint-disable-next-line no-restricted-globals
                h: (screen.height * (window.innerWidth / screen.width)).toFixed(0),
            };
        } else {
            r = {
                w: window.innerWidth,
                h: window.innerHeight,
            };
        }
        if (r.w != t.browser.w || r.h != t.browser.h) {
            t.browser = r;
            return true;
        }

        return false;
    }

    _size() {
        const t = this; const inch = 25.4; const dpi = (t.devicePixelRatio * 96) / inch;
        const r = { w: (t.device.w / dpi / t.kmm).toFixed(1), h: (t.device.h / dpi / t.kmm).toFixed(1) };

        if (r.w != t.size.w || r.h != t.size.h) {
            t.size = r;
            return true;
        }
        return false;
    }

    _overallness() {
        const t = this;
        const s = t.size;
        let r = 'monitor';
        if (t.mobile) {
            r = ((s.w < t.tablet_area) || (s.h < t.tablet_area) ? 'phone' : 'tablet');
        }

        if (r !== t.overallness) {
            t.overallness = r;
            return true;
        }
        return false;
    }
}

/* init object */
export default new Dvc();
