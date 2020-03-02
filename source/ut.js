import ut from 'fmihel-lib';

const _ut = {
    _changeLocation: {
        list: [],
        observer: undefined,
        pathname: false,
        update(forced = false) {
            const cl = _ut._changeLocation;
            if ((forced) || (window.location.pathname !== cl.pathname)) {
                cl.pathname = window.location.pathname;
                cl.list.forEach((f) => {
                    try {
                        f(window.location);
                    } catch (e) {
                        console.error(e, f);
                    }
                });
            }
        },
    },
    /** пустышка  */
    test() {
        return 'test';
    },
    /**
     * событие на изменение маршрута броузера ( в пределах страницы )
     * @param {function|undefined} callback function if callback === undefined then call doChangeLocation forced
     * @return {function|undefined} unregistered callback
    */
    // eslint-disable-next-line consistent-return
    onChangeLocation(callback) {
        const cl = _ut._changeLocation;

        if (cl.observer === undefined) {
            cl.observer = new MutationObserver(() => { cl.update(); });
            cl.observer.observe(document.querySelector('body'), { attributes: true, childList: true, subtree: true });
        }
        if (callback === undefined) {
            cl.update(true);
        } else {
            cl.list.push(callback);
            return () => {
                let idx = -1;
                for (let i = 0; i < cl.list.length; i++) {
                    if (cl.list[i] === callback) {
                        idx = i;
                        break;
                    }
                }
                if (idx > -1) cl.list.splice(idx, 1);
            };
        }
    },
};

export default {
    ...ut, ..._ut,
};
