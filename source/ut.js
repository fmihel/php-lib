import { ut as inner } from 'fmihel-lib';

const ut = {
    /** пустышка  */
    test() {
        return 'test';
    },
};

export default {
    ...inner, ...ut,
};
