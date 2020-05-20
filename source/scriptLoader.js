/**
 * динамическая загрузка скрипта
 */
class ScriptLoader {
    constructor() {
        this.list = [];
    }

    /** возвращает признак, что загружен скрипт
     * @param {string} addr of scripts
     * @returns {bool}
    */
    exist(url) {
        return this.list.indexOf(url) >= 0;
    }

    /** @returns {number} count of loading script */
    count() {
        return this.list.length;
    }

    /** return name loading script by index
     * @param {int} index
     * @returns {string}
    */
    get(i) {
        return this.list[i];
    }

    /** динамическая загрузка js скрипта
     * @param {string|object} string = "addr" object = {url:"addr"}
     * @returns {Promise}
    */
    load(param) {
        const p = {
            url: false,
            ...(typeof param === 'string' ? { url: param } : param),
        };

        return new Promise((ok, err) => {
            if (!p.url) {
                throw Error('use load("addr") or load({url:"add"}');
            }

            if (!this.exist(p.url)) {
                const script = document.createElement('script');
                script.onload = () => {
                    this.list.push(p.url);
                    ok(p.url);
                };
                script.onerror = () => {
                    err(p.url);
                };

                script.src = p.url;
                document.head.append(script);
            }
            ok(p.url);
        });
    }
}

export default new ScriptLoader();
