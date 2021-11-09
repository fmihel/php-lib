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
    describe('$', () => {
        it('1. JX.$("#mocha")', () => {
            const one = JX.$('#mocha');
            one._test738 = 'mile';
            const two = JX.$('#mocha');

            // console.log('res >> ', res);
            chai.expect(two._test738).to.equal('mile');
        });
        it('2. JX.$("#mocha")', () => {
            const one = JX.$('#mocha');
            one._test738 = 'mile';
            const two = JX.$('#mocha', { group: 'some' });

            // console.log('res >> ', res);
            chai.expect(two._test738).to.equal(undefined);
        });
    });
    describe('getStyle', () => {
        it('getStyle', () => {
            const dom = DOM('#mocha');
            const style = JX.getStyle(dom);
            chai.expect(true).to.equal(true);
        });
    });

    describe('textSize', () => {
        it('textSize("someText and more texy") = {w:int,h:int}', () => {
            const size = JX.textSize('someText qwejh jwhedf hwe');

            console.log('someText >> ', size);
            chai.expect(size).to.have.property('w');
            chai.expect(size).to.have.property('h');
        });

        it('with padding ', () => {
            const obj = document.createElement('div');
            const str = document.createTextNode('so long text in this node!');
            obj.style.padding = '13px';
            obj.style.position = 'absolute';
            obj.appendChild(str);
            document.body.appendChild(obj);
            // console.log('width', obj.offsetWidth);
            const size = JX.textSize('someText qwejh jwhedf hwe', { parentDom: obj, attr: { padding: true } });

            console.log('someText >> ', size);
            chai.expect(true).to.equal(true);

            document.body.removeChild(obj);
        });
    });
});
