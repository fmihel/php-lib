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

    describe('get', () => {
        const a = { b: { f: [0, 1, 13, { c: 'text' }] } };
        it('get(a,\'b\',\'f\',3,\'c\',\'none\') = "text"', () => {
            const res = ut.get(a, 'b', 'f', 3, 'c', false);
            console.info('get(a,\'b\',\'f\',3,\'c\',false)', res);
            chai.expect(res === 'text').to.equal(true);
        });
        it('get(a,\'b\',\'f\',2,\'none\') = 13', () => {
            const res = ut.get(a, 'b', 'f', 2, false);
            console.info('get(a,"b","f",2,"none")', res);
            chai.expect(res === 13).to.equal(true);
        });
        it('get(a,"b","f",4,"none") = "none"', () => {
            const res = ut.get(a, 'b', 'f', 4, 'none');
            console.info('get(a,"b","f",4,"none")', res);
            chai.expect(res === 'none').to.equal(true);
        });
        it('get(a,"b") => Exception ', (done) => {
            chai.expect(() => {
                ut.get(a, 'b');
            }).to.throw();
            done();
        });
    });
});
