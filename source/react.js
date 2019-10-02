import ut from './ut';
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

    const a = $.extend(true, {
        grow: 1,
        shrink: 1,
        basis: 'auto',
        align: 'auto',
        order: 0,
    }, props);

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

    const p = {};
    Object.keys(a).forEach((key) => { p[ut.alias(key, aliasNames)] = a[key]; });


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
    const aliasNames = {
        flexDirection: ['direction', 'direct'],
        flexWrap: ['wrap'],
        justifyContent: ['content'],
        alignItems: ['align'],
    };
    const aliasValues = {
        'flex-start': ['start'],
        'flex-end': ['end'],
        'space-between': ['between'],
        'space-around': ['around'],
    };

    const valids = {
        flexDirection: ['row', 'row-reverse', 'column', 'column-reverse'],
        flexWrap: ['nowrap', 'wrap', 'wrap-reverse'],
        justifyContent: ['flex-start', 'flex-end', 'center', 'space-between', 'space-around'],
        alignItems: ['stretch', 'flex-start', 'flex-end', 'baseline'],
        alignContent: ['stretch', 'flex-start', 'flex-end', 'baseline'],
    };

    const value = (v, can = undefined) => {
        if ((can !== undefined) && (can.length >= 0) && (can.indexOf(v) === -1)) {
            return can[0];
        }
        return v;
    };

    const a = $.extend(true, {
        direction: 'row',
        wrap: 'nowrap',
        content: 'flex-start',
        align: 'stretch',
        alignContent: 'stretch',
    }, prop);


    const p = {};
    Object.keys(a).forEach((key) => { p[ut.alias(key, aliasNames)] = a[key]; });
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
 * @param {*} hThis - ссылка на контекст (this)
 */
export function binds(hThis/**/) {
    for (let i = 1; i < arguments.length; i++) {
        // eslint-disable-next-line prefer-rest-params
        hThis[arguments[i]] = hThis[arguments[i]].bind(hThis);
    }
}

// export default { defaultProps, flex, flexChild };
