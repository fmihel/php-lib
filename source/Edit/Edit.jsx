/* eslint-disable array-callback-return */
/* eslint-disable no-unused-vars */
/* eslint-disable import/no-extraneous-dependencies */
import React from 'react';
import { ut, JX } from 'fmihel-browser-lib';

export default class Edit extends React.Component {
    constructor(p) {
        super(p);

        this.onChange = this.onChange.bind(this);
        this.onKeyPress = this.onKeyPress.bind(this);
        this.onFocus = this.onFocus.bind(this);
        this.onBlur = this.onBlur.bind(this);
        this.state = {
            value: this.getValue(),
            clampClass: false,
            focused: false,
        };

        this.inputRef = React.createRef();
    }

    getValue() {
        const value = this.props.value || this.props.children;
        return value === undefined ? '' : value;
    }

    onChange(e) {
        if (this.props.onChange) {
            this.props.onChange({ id: this.props.id, value: e.target.value });
        } else {
            this.setState({ value: e.target.value });
        }
    }

    onKeyPress(o) {
        if (this.props.onKeyPress) {
            this.props.onKeyPress({
                id: this.props.id,
                value: o.target.value,
                key: o.key,
                args: o,
            });
        }
    }

    onFocus() {
        const dom = this.inputRef.current;
        const width = JX.pos(dom).w;
        if (width <= parseInt(this.props.clamp, 10)) {
            this.setState({ clampClass: 'wd-edit-input-scale', focused: true });
        } else this.setState({ focused: true });
    }

    onBlur() {
        const dom = this.inputRef.current;
        if (this.state.clampClass) {
            this.setState({ clampClass: false, focused: false });
        }
        this.setState({ focused: false });
    }

    componentDidMount() {
        // разовый вызов после первого рендеринга
    }

    componentWillUnmount() {
        // разовый после последнего рендеринга
    }

    componentDidUpdate(prevProps, prevState, prevContext) {
        // каждый раз после рендеринга (кроме первого раза !)
    }

    render() {
        const {
            dim, placeholder, disable, labelName, addClass, style, hint, type, required, min, max, step,
        } = this.props;
        const { fontSize, ...frameStyle } = style;
        const { clampClass, focused } = this.state;
        const disabled = ut.toBool(this.props.disabled);
        const visible = ut.toBool(this.props.visible);
        const readonly = ut.toBool(this.props.readonly);
        const props = {};// доп атрибуты input
        const value = ((this.props.onChange || readonly) ? this.getValue() : this.state.value);

        let editInputClass = `wd-edit-input${disabled ? ' wd-edit-disabled' : ''}`;
        editInputClass += (readonly ? ' wd-edit-readonly' : '');
        if (required && (`${value}`).length === 0) editInputClass += ' wd-edit-require ';

        const display = (visible ? 'flex' : 'none');
        if (readonly) props.readOnly = 'readonly';
        if (labelName) props.id = labelName;
        if (min !== undefined) props.min = min;
        if (max !== undefined) props.max = max;
        if (step !== undefined) props.step = step;

        const inputStyle = {};
        // список свойсв которые идут из style в input
        ['width', 'textAlign', 'fontSize'].map((prop) => { if (prop in style) inputStyle[prop] = style[prop]; });
        // if (clampClass !== false) {
        //    delete inputStyle.width;
        // }
        let _type = 'text';
        if (type === 'number') _type = focused ? type : 'text';

        return (
            <div className='wd-edit-frame' style={{ display, ...frameStyle }}>
                <input
                    ref = {this.inputRef}
                    type={_type || 'text'}
                    onChange = {this.onChange}
                    onKeyPress={this.onKeyPress}
                    className={`${editInputClass} ${addClass} ${clampClass || ''}`}
                    value={value}
                    disabled={!!disabled}
                    placeholder={placeholder}
                    {...props}
                    style={inputStyle}
                    title={hint}
                    onFocus={this.onFocus}
                    onBlur={this.onBlur}

                />
                {!disable.dim && <div className="wd-edit-dim">{dim}</div>}

            </div>
        );
    }
}
Edit.defaultProps = {
    id: undefined,
    type: 'text',
    disabled: 0,
    onChange: undefined,
    onKeyPress: undefined,
    dim: 'm',
    value: undefined,
    visible: 1,
    placeholder: '',
    readonly: false,
    required: false,
    style: {},
    disable: {
        dim: false,
    },
    addClass: '',
    hint: '',

    min: undefined, // for type = range or number
    max: undefined, // for type = range or number
    step: undefined, // for type = range or number
    clamp: 0,

};
