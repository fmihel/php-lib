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

export function flex(prop = {}) {
    const p = $.extend(true, {
        direction: 'row',
        wrap: 'nowrap',
        content: 'flex-start',
        align: 'stretch',
        alignContent: 'start',
    }, prop);

    p.display = 'flex';

    const aliasNames = {

        flexDirection: ['direction'],
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

    const out = {};

    Object.keys(p).forEach((k) => {
        const v = p[k];
        const name = ut.alias(k, aliasNames);
        out[name] = ut.alias(value(v, valids[name]), aliasValues);
    });

    return out;
}

export default { defaultProps, flex };
