/* global describe,it,chai,  */
import { defaultProps, flex, flexChild } from '../source/react';

describe('react', () => {
    describe('defaultProps', () => {
        it('defaultProps(com,{value:123,css:{self:"main"}})', () => {
            const com = { defaultProps: { caption: 'header', css: { self: 'common', border: 'brdr' } } };
            defaultProps(com, { value: 123, css: { self: 'main' } });
            const ok = { value: 123, caption: 'header', css: { self: 'main', border: 'brdr' } };

            // console.info(com.defaultProps, '=', ok);
            chai.expect(com.defaultProps).to.deep.equal(ok);
        });
    });
    describe('flex', () => {
        // { display:"flex", alignContent: "stretch", alignItems:"stretch", flexDirection:"row", flexWrap:"nowrap", justifyContent: "flex-start" }
        it('flex()', () => {
            const res = flex();

            const out = {
                alignContent: 'stretch',
                alignItems: 'stretch',
                display: 'flex',
                flexDirection: 'row',
                flexWrap: 'nowrap',
                justifyContent: 'flex-start',
            };
            console.info('flex()', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('{direction:"column",content:"start"}', () => {
            const res = flex({ direction: 'column', content: 'start' });

            const out = {
                alignContent: 'stretch',
                alignItems: 'stretch',
                display: 'flex',
                flexDirection: 'column',
                flexWrap: 'nowrap',
                justifyContent: 'flex-start',
            };
            // console.info('flex', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('error in params {direction:"column",content:"swehdkjh"}', () => {
            const res = flex({ direction: 'column', content: 'wqedwekd' });

            const out = {
                alignContent: 'stretch',
                alignItems: 'stretch',
                display: 'flex',
                flexDirection: 'column',
                flexWrap: 'nowrap',
                justifyContent: 'flex-start',
            };
            // console.info('flex', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('{content:"stretch",align:"start"}', () => {
            const res = flex({ content: 'center', align: 'stretch' });

            const out = {
                alignContent: 'stretch',
                alignItems: 'stretch',
                display: 'flex',
                flexDirection: 'row',
                flexWrap: 'nowrap',
                justifyContent: 'center',
            };
            // console.info('flex', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('{justifyContent:"stretch",alignItems:"start"}', () => {
            const res = flex({ justifyContent: 'center', alignItems: 'stretch' });

            const out = {
                alignContent: 'stretch',
                alignItems: 'stretch',
                display: 'flex',
                flexDirection: 'row',
                flexWrap: 'nowrap',
                justifyContent: 'center',
            };
            // console.info('flex', res);
            chai.expect(res).to.deep.equal(out);
        });
    });
    describe('flexChild', () => {
        it('flexChild()', () => {
            const res = flexChild();

            const out = {
                alignSelf: 'auto',
                flexBasis: 'auto',
                flexGrow: 1,
                flexShrink: 1,
                order: 0,
            };
            // console.info('flex()', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('{ grow: 1 }', () => {
            const res = flexChild({ grow: 1 });

            const out = {
                alignSelf: 'auto',
                flexBasis: 'auto',
                flexGrow: 1,
                flexShrink: 1,
                order: 0,
            };
            // console.info('flex()', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('{ stretch: 1 } = {grow: 1}', () => {
            const res = flexChild({ stretch: 1 });

            const out = {
                alignSelf: 'auto',
                flexBasis: 'auto',
                flexGrow: 1,
                flexShrink: 1,
                order: 0,
            };
            // console.info('flex()', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('{ flexGrow: 2 } = {grow:2} ', () => {
            const res = flexChild({ flexGrow: 2 });

            const out = {
                alignSelf: 'auto',
                flexBasis: 'auto',
                flexGrow: 2,
                flexShrink: 1,
                order: 0,
            };
            // console.info('flex()', res);
            chai.expect(res).to.deep.equal(out);
        });

        it('{grow: 3, basis: 5, align: \'end\', shrink: 2, order: 5,}', () => {
            const res = flexChild({
                grow: 3, basis: 5, align: 'end', shrink: 2, order: 5,
            });

            const out = {
                alignSelf: 'flex-end',
                flexBasis: 5,
                flexGrow: 3,
                flexShrink: 2,
                order: 5,
            };
            console.info('flexChild()', res);
            chai.expect(res).to.deep.equal(out);
        });
    });
});
