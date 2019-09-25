/* global describe,it,chai,ut */
describe('ut', () => {
    it('random(10,100)', () => {
        const res = ut.random(10, 100);
        console.info('random(10,100)', res);
        chai.expect((res >= 10 && res <= 100)).to.equal(true);
    });


    it('random_str(10)', () => {
        const res = ut.random_str(10);
        console.info('random_str(10)', res);
        chai.expect(res.length === 10).to.equal(true);
    });
});
