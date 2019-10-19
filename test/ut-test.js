/* eslint-disable no-undef */
import chai from 'chai';
import ut from '../source/ut';

describe('ut', () => {
    describe('test', () => {
        it('test', () => {
            const res = ut.test();
            // console.info('test', res);
            chai.expect((res === 'test')).to.equal(true);
        });
    });
});
