/* global describe,it,chai */
import ut from '../source/ut';

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

    describe('alias', () => {
        const def = { direction: ['dir', 'direct'], 'left-grow': ['left', 'top'] };

        it('alias("dir",...) === "direction"', () => {
            const res = ut.alias('dir', def);
            console.info('alias(..., def = ', def, ' )');
            chai.expect(res === 'direction').to.equal(true);
        });
        it('alias("right",...) === "right"', () => {
            const res = ut.alias('right', def);
            chai.expect(res === 'right').to.equal(true);
        });
        it('alias("left",...) === "left-grow"', () => {
            const res = ut.alias('left', def);
            chai.expect(res === 'left-grow').to.equal(true);
        });
        const def2 = { direction: 15, 'left-grow': ['left', 'top'] };
        it('alias(...) => Exception ', (done) => {
            console.info('alias(..., def2 = ', def2, ' )');
            chai.expect(() => {
                ut.alias('some', def2);
            }).to.throw();
            done();
        });
    });
});
