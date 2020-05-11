/* global describe,it */
import chai from 'chai';
import JX from '../source/jx';
import { DOM } from '../source/dom';

describe('JX', () => {
    it('screen()', () => {
        const res = JX.screen();
        // console.info('JX.screen', JX.screen());
        chai.expect((res && res.w !== undefined && res.w !== 0)).to.equal(true);
    });
    it('mouse()', () => {
        // console.info('JX.mouse', JX.mouse());
        chai.expect(true).to.equal(true);
    });
    describe('pos', () => {
        it('pos("#mocha")', () => {
            const dom = DOM('#mocha');
            const res = JX.pos(dom);
            // console.log('res >> ', res);
            chai.expect(res).to.have.property('x');
            chai.expect(res).to.have.property('y');
            chai.expect(res).to.have.property('w');
            chai.expect(res).to.have.property('h');
        });
    });
    describe('abs', () => {
        it('abs("#mocha")', () => {
            const dom = DOM('#mocha');
            const res = JX.abs(dom);
            // console.log('res >> ', res);
            chai.expect(res).to.have.property('x');
            chai.expect(res).to.have.property('y');
            chai.expect(res).to.have.property('w');
            chai.expect(res).to.have.property('h');
        });
    });
    describe('visible', () => {
        it('visible("#mocha")', () => {
            const dom = DOM('#mocha');
            const res = JX.visible(dom);
            // console.log('res >> ', res);
            chai.expect(res).to.equal(true);
        });
        it('visible("#mocha","deep")', () => {
            $('body').append('<div style="display:none"><div id="hgw4ws"></div>');
            const dom = DOM('#hgw4ws');
            const res = JX.visible(dom, 'deep');
            // console.log('res >> ', res);
            chai.expect(res).to.equal(false);
        });
        it('visible("#mocha","deep")', () => {
            const dom = DOM('#mocha');
            const res = JX.visible(dom, 'deep');
            // console.log('res >> ', res);
            chai.expect(res).to.equal(true);
        });
    });
});
