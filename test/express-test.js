/* global describe,it,chai, */
import {
    routerPaths,
} from '../source/express';

function equalByMean(a, b) {
    if (a.length !== b.length) {
        return false;
    }

    for (let i = 0; i < a.length; i++) {
        if (b.indexOf(a[i]) === -1) {
            return false;
        }
    }
    return true;
}
describe('express', () => {
    describe('routerPaths', () => {
        it('"/"', () => {
            const res = routerPaths('/');
            // console.info('DOM("body")', res);
            const to = ['/'];
            chai.expect(equalByMean(res, to)).to.equal(true);
        });

        it('"admin","/"', () => {
            const res = routerPaths(['admin', '/']);
            // console.info(res);
            const to = ['/admin', '/'];
            chai.expect(equalByMean(res, to)).to.equal(true);
        });

        it('{admin:"autorize"},"/"', () => {
            const res = routerPaths([{ admin: 'autorize' }, '/']);
            // console.info(res);
            const to = ['/admin/autorize', '/admin', '/'];
            chai.expect(equalByMean(res, to)).to.equal(true);
        });

        it('{admin:["autorize","login"]},"/"', () => {
            const res = routerPaths([{ admin: ['autorize', 'login'] }, '/']);
            // console.info(res);
            const to = ['/admin/login', '/admin/autorize', '/admin', '/'];
            chai.expect(equalByMean(res, to)).to.equal(true);
        });

        it('{admin:[{autorize:["new","sigin"]},"login"]},"/"', () => {
            const res = routerPaths([{ admin: [{ autorize: ['new', 'sigin'] }, 'login'] }, '/']);
            // console.info(res);
            const to = ['/admin/autorize/sigin', '/admin/autorize/new', '/admin/login', '/admin/autorize', '/admin', '/'];
            chai.expect(equalByMean(res, to)).to.equal(true);
        });
        it('use func param', () => {
            const res = routerPaths({ admin: [{ autorize: ['new', 'sigin'] }, 'login'] }, '/');
            // console.info(res);
            const to = ['/admin/autorize/sigin', '/admin/autorize/new', '/admin/login', '/admin/autorize', '/admin', '/'];
            chai.expect(equalByMean(res, to)).to.equal(true);
        });
    });
});
