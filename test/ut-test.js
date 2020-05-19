/* global describe,it,chai */
import ut from '../source/ut';

describe('ut', () => {
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

    it('random(10,100)', () => {
        const res = ut.random(10, 100);
        // console.info('random(10,100)', res);
        chai.expect((res >= 10 && res <= 100)).to.equal(true);
    });


    it('random_str(10)', () => {
        const res = ut.random_str(10);
        // console.info('random_str(10)', res);
        chai.expect(res.length === 10).to.equal(true);
    });

    describe('get', () => {
        const a = { b: { f: [0, 1, 13, { c: 'text' }] } };
        it('get(a,\'b\',\'f\',3,\'c\',\'none\') = "text"', () => {
            const res = ut.get(a, 'b', 'f', 3, 'c', false);
            // console.info('get(a,\'b\',\'f\',3,\'c\',false)', res);
            chai.expect(res === 'text').to.equal(true);
        });
        it('get(a,\'b\',\'f\',2,\'none\') = 13', () => {
            const res = ut.get(a, 'b', 'f', 2, false);
            // console.info('get(a,"b","f",2,"none")', res);
            chai.expect(res === 13).to.equal(true);
        });
        it('get(a,"b","f",4,"none") = "none"', () => {
            const res = ut.get(a, 'b', 'f', 4, 'none');
            // console.info('get(a,"b","f",4,"none")', res);
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
            // console.info('alias(..., def = ', def, ' )');
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
            // console.info('alias(..., def2 = ', def2, ' )');
            chai.expect(() => {
                ut.alias('some', def2);
            }).to.throw();
            done();
        });
    });

    describe('each', () => {
        it('stop on find', () => {
            const res = ut.each(['a', 'c', 3, 4], (v) => v === 3);
            // console.log('res >> ', res);
            chai.expect(res).to.equal(3);
        });
        it('undefined', () => {
            const res = ut.each(['a', 'c', 3, 4], (v) => v === 10);
            // console.log('res >> ', res);

            chai.expect(res).to.equal(undefined);
        });

        it('full loop', () => {
            let res = '';
            ut.each(['a', 'c', 3, 4], (v) => {
                res += v;
            });
            // console.log('res >> ', res);
            chai.expect(res).to.equal('ac34');
        });

        it('Exception param 1', (done) => {
            chai.expect(() => {
                ut.each('a', () => {});
            }).to.throw();
            done();
        });
        it('Exception param 2', (done) => {
            chai.expect(() => {
                ut.each(['a', 'c', 3, 4]);
            }).to.throw();
            done();
        });

        it('object loop find', () => {
            const res = ut.each({
                a: 1, b: 3, c: 7, d: { f: 10 },
            }, (v, k) => (k === 'b'));
            // console.log('res >> ', res);
            chai.expect(res).to.equal(3);
        });
        it('object loop all', () => {
            const res = {
                a: 1, b: 3, c: 7, d: 15,
            };
            ut.each(res, (v, k, o) => {
                // eslint-disable-next-line no-param-reassign
                o[k] = `${v}a`;
            });
            // console.log('res >> ', res);
            chai.expect(res).to.deep.equal({
                a: '1a', b: '3a', c: '7a', d: '15a',
            });
        });
    });


    describe('translate', () => {
        it('translate(1,0,10,0,100) = 10', () => {
            const res = ut.translate(1, 0, 10, 0, 100);
            chai.expect(res).to.equal(10);
        });
        it('translate(-4,-5,0,0,5) = 1', () => {
            const res = ut.translate(-4, -5, 0, 0, 5);
            chai.expect(res).to.equal(1);
        });

        it('translate(-10,10,10,200,400) = Exception', (done) => {
            chai.expect(() => {
                ut.translate(-10, 10, 10, 200, 400);
            }).to.throw('translate param y1 == y2 !');
            done();
        });
    });

    describe('promises', () => {
        it('promises([f1,f2,..],param)', (done) => {
            const f = (res) => new Promise((ok) => {
                setTimeout(() => {
                    ok(res + 1);
                }, 10);
            });
            const funcs = [f, f, f];
            ut.promises(funcs, 0).then((res) => {
                if (res !== funcs.length) {
                    throw new Error(`Expected ${res}, but got ${funcs.length}`);
                }
                done();
            });
        });
    });
});
