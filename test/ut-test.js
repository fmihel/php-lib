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
    describe('random_str', () => {
        it('random_str', () => {
            const res = ut.random_str(10);
            // console.info('test', res);
            chai.expect((res.length === 10)).to.equal(true);
        });
    });
    describe('replaceAll', () => {
        it(' "яблоки на снегу, как яблоки на снегу","яблоки","апельсины"', () => {
            const str = 'яблоки на снегу, как яблоки на снегу';
            const find = 'яблоки';
            const to = 'апельсины';
            const out = 'апельсины на снегу, как апельсины на снегу';
            const res = ut.replaceAll(str, find, to);
            // console.info(res);
            chai.expect(res).to.equal(out);
        });
    });

    describe('replaceAll', () => {
        it(' "apple is not pineapple, is a apple","apple","orange"', () => {
            const str = 'apple is not pineapple, is a apple';
            const find = 'apple';
            const to = 'orange';
            const out = 'orange is not pineorange, is a orange';
            const res = ut.replaceAll(str, find, to);
            // console.info(res);
            chai.expect(res).to.equal(out);
        });
    });
});
