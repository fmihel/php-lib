import { ut } from 'fmihel-lib';

const flexScripts = {
    vert: {
        flexDirection: 'column',
        flexWrap: 'nowrap',
        justifyContent: 'flex-start',
        alignItems: 'stretch',
        alignContent: 'stretch',
        display: 'flex',
    },
    horiz: {
        flexDirection: 'row',
        flexWrap: 'nowrap',
        justifyContent: 'flex-start',
        alignItems: 'stretch',
        alignContent: 'stretch',
        display: 'flex',
    },

    stretch: {
        flexGrow: 1,
        flexShrink: 1,
        flexBasis: 'auto',
        alignSelf: 'auto',
        order: 0,
    },
    fixed: {
        flexGrow: 0,
        flexShrink: 1,
        flexBasis: 'auto',
        alignSelf: 'auto',
        order: 0,
    },
    ':center': {
        justifyContent: 'center',
        alignItems: 'center',
    },
    ':left': {
        justifyContent: 'flex-start',
    },
    ':right': {
        justifyContent: 'flex-end',
    },
    ':top': {
        justifyContent: 'flex-start',
    },
    ':bottom': {
        justifyContent: 'flex-end',
    },
};

/**
 * Позволяет расширить наследуемые свойства текущими props
 * При этом свойства из props считаются более приоритетными
 * @param {React.Component} ReactComponent - компонент react
 * @param {object} props - объект для установки свойств
 */
export function defaultProps(ReactComponent, props) {
    // eslint-disable-next-line no-param-reassign
    ReactComponent.defaultProps = $.extend(true, $.extend(true, {}, ReactComponent.defaultProps), props);
}

/**
 * Возвращаеет настройки style flex для дочернего элемента react элемента
 * @param {*} prop
 */
export function flexChild(props = {}) {
    const aliasNames = {
        flexGrow: ['grow', 'stretch'],
        flexShrink: ['shrink', 'decrease'],
        flexBasis: ['basis'],
        alignSelf: ['align'],
    };

    const valids = {
        alignSelf: ['auto', 'flex-start', 'flex-end', 'center', 'space-between', 'space-around'],
    };
    const aliasValues = {
        'flex-start': ['start'],
        'flex-end': ['end'],
        'space-between': ['between'],
        'space-around': ['around'],
    };

    const value = (v, can = undefined) => {
        if ((can !== undefined) && (can.length >= 0) && (can.indexOf(v) === -1)) {
            return can[0];
        }
        return v;
    };

    const a = {};
    Object.keys(props).forEach((key) => { a[ut.alias(key, aliasNames)] = props[key]; });

    const p = $.extend(true, {
        flexGrow: 1, // grow
        flexShrink: 1, // shrink | decreese
        flexBasis: 'auto', // basis
        alignSelf: 'auto', // align
        order: 0,
    }, a);


    const out = {};
    Object.keys(p).forEach((k) => {
        const v = p[k];
        out[k] = value(ut.alias(v, aliasValues), valids[k]);
    });

    return out;
}

/**
 * Возвращаеет настройки style flex контейнера react элемента
 * @param {*} prop
 */
export function flex(prop = {}, child = undefined) {
    if (typeof prop === 'string') {
        let out = {};

        ut.replaceAll(prop, ':', ' :').split(' ').forEach((name) => {
            const p = flexScripts[name];
            if (p) out = { ...out, ...p };
        });
        return out;
    }

    const aliasNames = {
        flexDirection: ['direction', 'direct'],
        flexWrap: ['wrap'],
        justifyContent: ['content'],
        alignItems: ['align'],
    };
    const aliasValues = {
        row: ['horiz', 'horizont'],
        column: ['vert', 'vertical'],
        'flex-start': ['start'],
        'flex-end': ['end'],
        'space-between': ['between'],
        'space-around': ['around'],
    };

    const valids = {
        flexDirection: ['row', 'row-reverse', 'column', 'column-reverse'],
        flexWrap: ['nowrap', 'wrap', 'wrap-reverse'],
        justifyContent: ['flex-start', 'flex-end', 'center', 'space-between', 'space-around'],
        alignItems: ['stretch', 'flex-start', 'flex-end', 'baseline', 'center'],
        alignContent: ['stretch', 'flex-start', 'flex-end', 'baseline'],
    };

    const value = (v, can = undefined) => {
        if ((can !== undefined) && (can.length >= 0) && (can.indexOf(v) === -1)) {
            return can[0];
        }
        return v;
    };

    const a = {};
    Object.keys(prop).forEach((key) => { a[ut.alias(key, aliasNames)] = prop[key]; });

    const p = $.extend(true, {
        flexDirection: 'row', // deirecrion | direct
        flexWrap: 'nowrap', // wrap
        justifyContent: 'flex-start', // content
        alignItems: 'stretch', // align
        alignContent: 'stretch', // alignContent
    }, a);


    // const p = {};
    // Object.keys(a).forEach((key) => { p[ut.alias(key, aliasNames)] = a[key]; });
    p.display = 'flex';

    const out = {};
    Object.keys(p).forEach((k) => {
        const v = p[k];
        const val = ut.alias(v, aliasValues);
        out[k] = value(val, valids[k]);

        // out[k] = ut.alias(value(v, valids[k]), aliasValues);
    });

    if (child !== undefined) {
        $.extend(true, out, flexChild(child));
    }
    return out;
}
/**
 * сокращение синтаксиса для привязывания ф-ций react класса к контексту
 * Ex:
 * this.onMouseMove = this.onMouseMove.bind(this);
 * this.onClick = this.onClick.bind(this);
 * vs
 * binds(this,'onMouseMove','onClick');
 *
 * @param {...any}  - первый параметра ссылка на объект, остальные - строковые имена функция
 */
export function binds(...a) {
    let h = null;
    a.forEach((v, i) => {
        if (i === 0) { h = v; } else { h[v] = h[v].bind(h); }
    });
}
/**
 * стартовая инициализация состояний react одноименными данными из props
 *
 * @param  {...any} a - первый параметра ссылка на объект, остальные - строковые имена переменных из props
 */
export function propsToState(...a) {
    let h = null;
    a.forEach((v, i) => {
        if (i === 0) {
            h = v;
            h.state = h.state === undefined ? {} : h.state;
        } else {
            h.state[v] = h.props[v];
        }
    });
}
// export default { defaultProps, flex, flexChild };
