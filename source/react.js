/**
 * Позволяет расширить наследуемые свойства текущими props
 * При этом свойства из props считаются более приоритетными
 * @param {React.Component} ReactComponent - компонент react
 * @param {object} props - объект для установки свойств
 */
function defaultProps(ReactComponent, props) {
    // eslint-disable-next-line no-param-reassign
    ReactComponent.defaultProps = $.extend(true, $.extend(true, {}, ReactComponent.defaultProps), props);
}

module.exports = { defaultProps };
