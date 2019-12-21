import ut from 'fmihel-lib';

const _ut = {
    /** пустышка  */
    test() {
        return 'test';
    },
};

export default {
    ...ut, ..._ut,
};
