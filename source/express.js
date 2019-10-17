
/**
 * возвращает полный список маршрутов, заданный через иерархию в paths
 * Ex:
 * routerPaths('path') => ["/path"]
 * Ex:
 * routerPaths(['path','/tool','/']) =>  ["/path","/tool","/"]
 * Ex:
 * routerPaths([{path:"user"},"/"]) => ["/path/user","path","/"]
 * Ex:
 * routerPaths([{path:{user:["test","more"]}},"/"]) => ["/path/user/more","/path/user/test","/path/user","path","/"]
 * Предполагется использовать призадании статических маршрутов в express
 * app = express();
 * app.use(routerPaths(...),handler);
 * @return {array}
 */

// eslint-disable-next-line import/prefer-default-export
export function routerPaths(...pathss) {
    let out = [];

    const addSlash = (str) => (str[0] !== '/' ? '/' : '') + str;

    const add = (...str) => {
        let a = '';
        str.forEach((s) => { a += addSlash(s); });
        out.push(a);
    };

    pathss.forEach((paths) => {
        const type = typeof paths;
        if (type === 'string') {
            add(paths);
        } else if (Array.isArray(paths)) {
            out = [];
            paths.forEach((v) => {
                const t = typeof (v);
                if (t === 'string') {
                    add(v);
                } else if (t === 'object') {
                    routerPaths(v).forEach((b) => add(b));
                } else {
                    throw Error('type is not string or object');
                }
            });
        } else if (type === 'object') {
            Object.keys(paths).forEach((key) => {
                const v = paths[key];
                routerPaths(v).forEach((b) => {
                    add(key, b);
                });
                add(key);
            });
        }
    });
    return out;
}
