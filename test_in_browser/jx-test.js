/* global chai,describe,it */
import JX from '../source/jx';

describe('JX', () => {
    it('JX.screen()', () => {
        const res = JX.screen();
        console.info('JX.screen', JX.screen());
        chai.expect((res && res.w !== undefined && res.w !== 0)).to.equal(true);
    });
    it('JX.mouse()', () => {
        console.info('JX.mouse', JX.mouse());
        chai.expect(true).to.equal(true);
    });
});
