/* global describe,it,chai, */
import { DOM, DOMS, $D } from '../source/dom';

describe('dom', () => {
    describe('DOM', () => {
        it('DOM("body")', () => {
            const res = DOM('body');
            console.info('DOM("body")', res);
            chai.expect((res && res.tagName === 'BODY')).to.equal(true);
        });

        it('DOM("#mocha-stats")', () => {
            const res = DOM('#mocha-stats');
            console.info('DOM("#mocha-stats")', res);
            chai.expect((res && res.tagName === 'UL')).to.equal(true);
        });


        it('DOM(".progress")', () => {
            const res = DOM('.progress');
            console.info('DOM(".progress")', res);
            chai.expect((res && res.tagName === 'LI')).to.equal(true);
        });
    });
    describe('DOMS', () => {
        it('DOMS("div")', () => {
            const res = DOMS('div');
            console.info('DOMS("div")', res);
            chai.expect((res !== [])).to.equal(true);
        });


        it('DOMS("#mocha-stats")', () => {
            const res = DOMS('#mocha-stats');
            console.info('DOMS("#mocha-stats")', res);
            chai.expect((res !== [])).to.equal(true);
        });


        it('DOMS(".suite")', () => {
            const res = DOMS('.suite');
            console.info('DOMS(".suite")', res);
            chai.expect((res !== [])).to.equal(true);
        });
    });
    describe('$D', () => {
        it('$D(DOM("#mocha-stats")..)', () => {
            const sel = '#mocha-stats';
            const dom = DOM(sel);
            $D(dom, { a: 1, b: 2 });
            const res = $D(dom);
            console.info('DOM("#mocha-stats")', res);
            chai.expect((res) && ('a' in res) && (res.a === 1)).to.equal(true);
        });
    });
});
