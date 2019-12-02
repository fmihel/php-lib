
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
     * Заменяет все вхождения search на replaceTo в строке str
     * @param {*} str исходная строка
     * @param {*} search искомая замена
     * @param {*} replaceTo на что будем менять
     */
    replaceAll(str, search, replaceTo) {
        return str.replace(new RegExp(search, 'g'), replaceTo);
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
    /**
     * Возврат команды по ее алиасу(короткому описанию)
     * Ex: alias('direct',{direction:'direct'}) => direction
     * Ex: alias('direct',{direction:['direct','dir','d']}) => direction
     * Ex: alias('d',{direction:['direct','dir','d']}) => direction
     * Ex: alias('left',{direction:['direct','dir','d'],right:['r','noLeft']}) => left
     * Ex: alias('r',{direction:['direct','dir','d'],right:['r','noLeft']}) => right
     * @param {string} alias
     * @param {string|[]} conform
     * @return string|Exception
     */
    alias(alias, conform) {
        const keys = Object.keys(conform);
        for (let k = 0; k < keys.length; k++) {
            const name = keys[k];
            const def = conform[name];
            if (Array.isArray(def)) {
                try {
                    for (let i = 0; i < def.length; i++) {
                        if (def[i] === alias) {
                            return name;
                        }
                    }
                } catch (e) {
                    console.error(e);
                    throw new Error(e);
                }
            } else if (typeof def === 'string') {
                if (alias === name) {
                    return name;
                }
            } else {
                throw new Error(`ut.alias [${def}] is not array of string`);
            }
        }
        return alias;
    },
    /**
     * перебор по элементам или свойствам
     * При переборе по объекту в ф-цию func передается 4 параметра
     * ( значение свойства, имя свойства, весь объект, порядковый номер )
     *
     * @param {object|array} o
     * @param {function} func
     * @return obj|Exception
     */
    each(o, func) {
        let msg = '';
        if (typeof func !== 'function') {
            msg = 'func in ut.each(..,func) must be function';
            console.error(msg);
            throw Error(msg);
        }

        if (Array.isArray(o)) {
            return o.find(func);
        }
        if (typeof o === 'object') {
            try {
                const keys = Object.keys(o);
                const res = keys.find((key, i) => func(o[key], key, o, keys.length));
                return (res ? o[res] : undefined);
            } catch (e) {
                console.error(e);
                throw Error(e);
            }
        }

        msg = 'o in ut.each(o,..) must be array or object';
        console.error(msg);
        throw Error(msg);
    },


};

export default ut;
