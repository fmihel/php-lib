/* eslint-disable no-undef */
import chai from 'chai';
import {
    DOM,
    DOMS,
    $D,
    parentDOM,
    childDOM,
} from '../source/dom';

describe('dom', () => {
    describe('DOM', () => {
        it('DOM("body")', () => {
            const res = DOM('body');
            // console.info('DOM("body")', res);
            chai.expect((res && res.tagName === 'BODY')).to.equal(true);
        });

        it('DOM("#mocha-stats")', () => {
            const res = DOM('#mocha-stats');
            // console.info('DOM("#mocha-stats")', res);
            chai.expect((res && res.tagName === 'UL')).to.equal(true);
        });


        it('DOM(".progress")', () => {
            const res = DOM('.progress');
            // console.info('DOM(".progress")', res);
            chai.expect((res && res.tagName === 'LI')).to.equal(true);
        });
    });
    describe('DOMS', () => {
        it('DOMS("div")', () => {
            const res = DOMS('div');
            // console.info('DOMS("div")', res);
            chai.expect((res !== [])).to.equal(true);
        });


        it('DOMS("#mocha-stats")', () => {
            const res = DOMS('#mocha-stats');
            // console.info('DOMS("#mocha-stats")', res);
            chai.expect((res !== [])).to.equal(true);
        });


        it('DOMS(".suite")', () => {
            const res = DOMS('.suite');
            // console.info('DOMS(".suite")', res);
            chai.expect((res !== [])).to.equal(true);
        });
    });
    describe('$D', () => {
        it('$D(DOM("#mocha-stats")..)', () => {
            const sel = '#mocha-stats';
            const dom = DOM(sel);
            $D(dom, { a: 1, b: 2 });
            const res = $D(dom);
            // console.info('DOM("#mocha-stats")', res);
            chai.expect((res) && ('a' in res) && (res.a === 1)).to.equal(true);
        });
    });
    describe('parentDOM', () => {
        it('parentDOM("body")', () => {
            const res = parentDOM('body');
            // console.info('parentDOM("body")', res);
            chai.expect((res && res.tagName === 'HTML')).to.equal(true);
        });

        it('parentDOM("#mocha-stats")', () => {
            const res = parentDOM('#mocha-stats');
            // console.info('parentDOM("#mocha-stats")', res);
            chai.expect((res && res.id === 'mocha')).to.equal(true);
        });


        it('parentDOM(DOM("#mocha-report"))', () => {
            const dom = DOM('#mocha-report');
            const res = parentDOM(dom);
            // console.info('parentDOM(DOM("#mocha-report"))', res);
            chai.expect((res && res.id === 'mocha')).to.equal(true);
        });
    });

    describe('childDOM', () => {
        it('childDOM("#mocha-stats")', () => {
            const res = childDOM('#mocha-stats');
            // console.info('childDOM("#mocha-stats")', res);
            chai.expect((res && res.length > 0)).to.equal(true);
        });
    });
});
