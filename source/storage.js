
export class Storage {
    constructor(param = {}) {
        this.param = {
            default: false,
            type: 'local', // local session cookie
            cookie_expires: 315400000, /* 10 years */
            cookie_path: '/',
            cookie_domen: window.location.hostname,
            ...param,
        };
    }


    get(name, param = {}) {
        const p = {
            ...this.param,
            ...param,
        };

        return Storage.unPack(this._get(name, p));
    }

    set(name, val, param = {}) {
        const p = {
            ...this.param,
            ...param,
        };
        this._set(name, Storage.pack(val), p);
    }

    exist(name, param = {}) {
        const p = {
            ...this.param,
            ...param,
        };
        return true;
    }

    del(name, param = {}) {
        const p = {
            ...this.param,
            ...param,
        };
    }

    static pack(val) {
        return JSON.stringify({ storage: val });
    }

    static unPack(pack) {
        const res = JSON.parse(pack);
        return res.storage;
    }

    // eslint-disable-next-line class-methods-use-this
    _set(name, val, param) {
        if (param.type === 'cookie') {
            const a = {
                expires: param.cookie_expires,
                path: param.cookie_path,
                domen: param.cookie_path,
            };

            let { expires } = a;

            if (typeof expires === 'number' && expires) {
                const d = new Date();
                d.setTime(d.getTime() + expires * 1000);
                expires = d;
                a.expires = expires;
            }

            if (expires && expires.toUTCString) {
                a.expires = expires.toUTCString();
            }

            let updatedCookie = `${name}=${encodeURIComponent(val)}`;

            // eslint-disable-next-line no-restricted-syntax ,guard-for-in
            for (const propName in a) {
                updatedCookie += `; ${propName}=${a[propName]}`;
            }
            document.cookie = updatedCookie;
        } else if (param.type === 'session') {
            sessionStorage.setItem(name, val);
        } else {
            localStorage.setItem(name, val);
        }
    }

    _get(name, param) {
        try {
            if (this.exist(name, param)) {
                if (param.type === 'cookie') {
                    const matches = document.cookie.match(new RegExp(`(?:^|; )${name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1')}=([^;]*)`));
                    if (matches) {
                        return decodeURIComponent(matches[1]);
                    }
                    throw Error(`cookie matches=${matches}`);
                }
                return (param.type === 'session' ? (sessionStorage.getItem(name)) : (localStorage.getItem(name)));
            }
            throw new Error(`${name} is not exists`);
        } catch (e) {
            console.error(e);
            return Storage.pack(param.default);
        }
    }
}
const storage = new Storage();

export default storage;
