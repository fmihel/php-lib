export default class Url {
    
    static default = {
        protocol:'http',
    }
    /** текущий адрес */
    static href() {
        return window.location.href;
    
    }
    /** текущий адрес */
    static current() {
        return window.location.href;
    }

    /**  return array of url params or
     *   set array to url params,
     *   if replace === true then
     *      set union with existings params,
     *   else
     *      replace exists params
    */
    static params(url, set = false, replace = false) {
        let pairs = [];
        if (set) {
            let params = set;

            if (!replace) {
                const prev = Url.params(url);
                // params = $.extend(true, prev, params);
                params = { ...prev, ...params };
            }
            const keys = Object.keys(params);
            // for (const key in params) {
            keys.map((key) => {
                // if (params.hasOwnProperty(key)) {
                pairs.push(`${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`);
                // }
            });

            const pr = pairs.join('&');

            const u = Url.parsing(url);
            return `${u.protocol}//${u.domen}/${u.path}${u.file}?${pr}`;
        }
        const request = {};
        if (url.indexOf('?') === -1) return {};

        pairs = url.substring(url.indexOf('?') + 1).split('&');
        for (let i = 0; i < pairs.length; i++) {
            if (pairs[i]) {
                const pair = pairs[i].split('=');
                request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
        }
        return request;
    }

    static _parsing(url) {
        const l = document.createElement('a');
        let addr = url.trim();
        if (addr.indexOf('www.') == 0) {
            addr = Url.default.protocol+`://${addr}`;
        }
        if ((addr.indexOf('https://') == -1) && (addr.indexOf('http://') == -1)) {
            addr = Url.default.protocol+`://${addr}`;
        }
        l.href = addr;
        return l;
    }

    static parsing(url) {
        let addr = url.trim();
        if (addr == '') {
            return {
                url: '', protocol: '', host: '', domen: '', path: '', file: '', params: '', param: {}, hash: '',
            };
        }

        let hash = '';
        const posHash = addr.indexOf('#');
        if (posHash > -1) {
            hash = addr.substr(posHash + 1);
            addr = addr.substr(0, posHash);
        }

        const p = Url._parsing(addr);
        let path = Url.extPath(p.pathname);
        let params = p.search;

        if (path[0] === '/') path = path.substr(1);
        if (params[0] === '?') params = params.substr(1);

        const res = {
        /* full url */url: addr,
            /* https:   */protocol: p.protocol,
            /* www.yandex.ru */host: p.host,
            /* www.yandex.ru */domen: p.hostname,
            /* path1/path2/path3/  */path,
            /* file.js   */file: Url.extFileName(p.pathname),
            /* "param1='12'&param2='3'  " */params,
            /* {param1:'12',param2:'3'} */param: Url.params(addr),
            hash,
        };

        return res;
    }

    static extFileName(file) {
        return file.replace(/^.*[\\\/]/, '');
    }

    static extPath(file) {
        const f = file.trim();
        if (f.substring(f.length - 1, 1) == '/') return f;
        return `${f.substring(0, f.lastIndexOf('/'))}/`;
    }
}
