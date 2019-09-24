const JX = {
    _params: {

    },
    window: $(window),
    screen: {
        x: 0, y: 0, w: 0, h: 0,
    },
    mouse: {
        x: 0, y: 0,
    },
    // eslint-disable-next-line no-underscore-dangle
    _initialize() {
        JX.window.on('mousemove', (e) => {
            JX.mouse.x = e.originalEvent.clientX;
            JX.mouse.y = e.originalEvent.clientY;
        });

        const updateScreenSize = () => {
            JX.screen.w = JX.window.width();
            JX.screen.h = JX.window.height();
        };

        updateScreenSize();
        JX.window.on('resize', updateScreenSize);
    },
};

// eslint-disable-next-line func-names
(function () {
    JX._initialize();
}());

module.exports = JX;
