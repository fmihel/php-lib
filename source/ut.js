
const ut = {
    random(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },

    random_str(count) {
        let res = '';
        const possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        for (let i = 0; i < count; i++) res += possible.charAt(Math.floor(Math.random() * possible.length));
        return res;
    },
    /**
     * Получить значение дочернего свойства используя цепочку аргументов
     * Example:
     * let a = { b: {f:[0,1,2,{c:"text"}]}}
     * Example:
     *  if (a)&&(a.b)&&(a.b.f)&&(a.b.f[3])&&*(a.b.f[3].c)
     *      return a.b.f[3].c;
     *   else
     *      return 'not find';
     *  or
     *   return ut.get(a,'b','f',3,'c','find`t text');
     * @param  {...any} args args[0] - исходный объект, args[1]..args[n-1] - имена или индексы args[n] - значение по умолчанию.
     * @return throw|any|default
     */
    get(...args) {
        if (args.length < 3) {
            throw new Error('ut.get must have more 2 args, (0)-object ,(1..n-1) - name or index, (n)-default maen');
        }

        let nameOrIndex;
        let obj = args[0];
        const defaultValue = args[args.length - 1];

        if (obj === undefined) {
            return defaultValue;
        }
        try {
            for (let i = 1; i < args.length - 1; i++) {
                nameOrIndex = obj[args[i]];
                if (nameOrIndex === undefined) {
                    return defaultValue;
                }
                obj = nameOrIndex;
            }
            return obj;
        } catch (e) {
            return defaultValue;
        }
    },
};

module.exports = ut;
