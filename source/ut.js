import ut from 'fmihel-lib';

const _ut = {
    _changeLocation: {
        list: [],
        observer: undefined,
        pathname: false,
    },
    /** пустышка  */
    test() {
        return 'test';
    },
    /**
     * событие на изменение маршрута броузера ( в пределах страницы )
     * @param {function} callback function
     * @return {function} unregistered callback
    */
    onChangeLocation(callback) {
        const cl = _ut._changeLocation;

        if (cl.observer === undefined) {
            cl.observer = new MutationObserver(() => {
                if (window.location.pathname !== cl.pathname) {
                    cl.pathname = window.location.pathname;
                    cl.list.forEach((f) => {
                        try {
                            f(window.location);
                        } catch (e) {
                            console.error(e, f);
                        }
                    });
                }
            });
            cl.observer.observe(document.querySelector('body'), { attributes: true, childList: true, subtree: true });
        }
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
    },
};

export default {
    ...ut, ..._ut,
};
