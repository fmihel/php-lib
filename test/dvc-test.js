/* eslint-disable no-undef */
import chai from 'chai';
import dvc from '../source/dvc';

describe('dvc', () => {
    describe('param', () => {
        it('browserName', () => {
            const res = dvc.browserName;
            console.info('dvc.browserName =', dvc.browserName);
            chai.expect(res !== '').to.equal(true);
        });
        it('os', () => {
            const res = dvc.os;
            console.info('dvc.os =', dvc.os);
            chai.expect(res !== '').to.equal(true);
        });
        it('platform', () => {
            const res = dvc.platform;
            console.info('dvc.platform =', dvc.platform);
            chai.expect(res !== '').to.equal(true);
        });
        it('mobile', () => {
            const res = dvc.mobile;
            console.info('dvc.mobile =', dvc.mobile);
            chai.expect(res !== '').to.equal(true);
        });
        it('landscape', () => {
            const res = dvc.landscape;
            console.info('dvc.landscape =', dvc.landscape);
            chai.expect(res !== '').to.equal(true);
        });
        it('chromium', () => {
            const res = dvc.chromium;
            console.info('dvc.chromium =', dvc.chromium);
            chai.expect(res !== '').to.equal(true);
        });
        it('overallness', () => {
            const res = dvc.overallness;
            console.info('dvc.overallness =', dvc.overallness);
            chai.expect(res !== '').to.equal(true);
        });
    });
});
