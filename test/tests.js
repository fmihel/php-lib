/* eslint-disable camelcase */
/* global it */

const tests = (datas, msg, it_func) => {
    const do_it = (data, i) => {
        const smsg = (typeof msg === 'function' ? msg(data, i) : msg);
        it(smsg, () => {
            it_func(datas[i], i, smsg);
        });
    };
    for (let i = 0; i < datas.length; i++) {
        if (datas[i].only) {
            do_it(datas[i], i);
            return;
        }
    }

    datas.map((data, i) => {
        if (!datas[i].no) do_it(datas[i], i);
    });
};

module.exports = tests;
