/* global chai,describe,it */
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
});
