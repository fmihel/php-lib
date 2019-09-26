/* global describe,it,chai, defaultProps,_ */
describe('react', () => {
    describe('defaultProps', () => {
        it('defaultProps(com,{value:123,css:{self:"main"}})', () => {
            const com = { defaultProps: { caption: 'header', css: { self: 'common', border: 'brdr' } } };
            defaultProps(com, { value: 123, css: { self: 'main' } });
            const ok = { value: 123, caption: 'header', css: { self: 'main', border: 'brdr' } };

            // https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.core.min.js
            console.info(com);
            console.info(ok);
            chai.expect(_.isEqual(com.defaultProps, ok)).to.equal(true);
        });
    });
});
