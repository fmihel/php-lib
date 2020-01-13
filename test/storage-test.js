/* global describe,it */
import chai from 'chai';
import storage, { Storage } from '../source/storage';


describe('storage', () => {
    describe('pack', () => {
        it('pack(string)', () => {
            const res = Storage.pack('test');
            chai.expect(res).to.equal('{"storage":"test"}');
        });
        it('unPack(string)', () => {
            const dat = Storage.pack('test');
            const res = Storage.unPack(dat);
            chai.expect(res).to.equal('test');
        });
        it('pack(object)', () => {
            const res = Storage.pack({ name: 'test', age: 10 });
            chai.expect(res).to.equal('{"storage":{"name":"test","age":10}}');
        });
        it('unPack(object)', () => {
            const def = { name: 'test', age: 10 };
            const dat = Storage.pack(def);
            const res = Storage.unPack(dat);
            chai.expect(res).to.deep.equal(def);
        });
    });

    describe('local', () => {
        it('set/get(string)', () => {
            const eq = 'Mike';
            storage.set('test', eq);
            const res = storage.get('test');
            chai.expect(res).to.equal(eq);
        });
        it('set/get(object)', () => {
            const eq = {
                str: 'jesn', num: 100, auth: { name: 'Mike', bool: true }, arr: [1, 4, 5],
            };
            storage.set('test_obj', eq);
            const res = storage.get('test_obj');
            chai.expect(res).to.deep.equal(eq);
        });
        it('not exist', () => {
            const name = 'hwwjkehdjkwhdw22';
            const res = storage.exist(name);
            chai.expect(res).to.equal(false);
        });
        it('exist', () => {
            const name = 'test';
            const res = storage.exist(name);
            chai.expect(res).to.equal(true);
        });

        it('del', () => {
            const name = 'for_del';
            storage.set(name, { obj: 'name' });
            storage.del(name);
            const res = storage.exist(name);
            chai.expect(res).to.equal(false);
        });
    });
    describe('cookie', () => {
        it('set/get(string)', () => {
            const param = { type: 'cookie' };
            const eq = 'Mike';
            storage.set('test_z', eq, param);
            const res = storage.get('test_z', param);
            chai.expect(res).to.equal(eq);
        });

        it('set/get(object)', () => {
            const param = { type: 'cookie' };
            const eq = {
                str: 'jesn', num: 100, auth: { name: 'Mike', bool: true }, arr: [1, 4, 5],
            };
            storage.set('test_obj', eq, param);
            const res = storage.get('test_obj', param);
            chai.expect(res).to.deep.equal(eq);
        });
        it('exist', () => {
            const param = { type: 'cookie' };
            const name = 'test_z';
            const res = storage.exist(name, param);
            chai.expect(res).to.equal(true);
        });
        it('not exist', () => {
            const param = { type: 'cookie' };
            const name = 'hwwssjkehdjkwhdw22';
            const res = storage.exist(name, param);
            chai.expect(res).to.equal(false);
        });
        it('del', () => {
            const param = { type: 'cookie' };
            const name = 'for_del';

            storage.set(name, { obj: 'name' }, param);
            storage.del(name, param);
            const res = storage.exist(name, param);
            chai.expect(res).to.equal(false);
        });
    });
});
