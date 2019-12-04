import fl from 'fmihel-lib';

const ut = {
    /** пустышка  */
    test() {
        return 'test';
    },
};

export default {
    ...fl.ut, ...ut,
};
