
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
};

module.exports = ut;
