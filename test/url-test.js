/* eslint-disable import/no-named-as-default-member */
/* eslint-disable import/no-named-as-default */
/* eslint-disable camelcase */
/* eslint-disable no-undef */
import chai from 'chai';
import url from '../source/url';
import tests from './tests';

const only = true;
const no = true;

describe('url', () => {
    describe('extPath', () => {
        tests([
            { from: 'https://path1/path2/test.js', to: 'https://path1/path2/' },
            { from: 'path1/path2/test.js', to: 'path1/path2/' },
            { from: '/path1/path2/test.js', to: '/path1/path2/' },
        ],
        (data, i) => `${i}: ${data.from} >> ${data.to}`,
        (data) => {
            const res = url.extPath(data.from);
            chai.expect(res === data.to).to.equal(true);
        });
    });

    describe('extFileName', () => {
        tests([
            { from: 'https://path1/path2/test.js', to: 'test.js' },
            { from: 'https://path1/path2/test/', to: '' },
            { from: 'test.js', to: 'test.js' },
        ],
        (data, i) => `${i}: '${data.from}' >> '${data.to}'`,
        (data) => {
            const res = url.extFileName(data.from);
            chai.expect(res === data.to).to.equal(true);
        });
    });
    describe('params', () => {
        tests([
            { url: 'https://www.site.ru/index.php', out: {} },
            { url: 'https://www.site.ru/index.php?t=4', out: { t: '4' } },
            { url: 'http://localhost:3000/?grep=url%20params', out: { grep: 'url params' } },
            { url: 'https://www.site.ru/index.php?t=4&s=text', out: { t: '4', s: 'text' } },
        ],
        (data, i) => `${i}: ${data.url} => ${JSON.stringify(data.out)}`,
        (data) => {
            const res = url.params(data.url);
            // console.log(res);
            chai.expect(res).to.eql(data.out);
        });
    });
    describe('params(url,set,replace)', () => {
        tests([
            {
                url: 'https://www.site.ru/index.php', set: { t: 4 }, replace: false, out: 'https://www.site.ru/index.php?t=4',
            },
            {
                url: 'https://www.site.ru/index.php?t=10&s=text', set: { t: 4 }, replace: false, out: 'https://www.site.ru/index.php?t=4&s=text',
            },
            {
                url: 'https://www.site.ru/index.php?t=10&s=text', set: { t: 4 }, replace: true, out: 'https://www.site.ru/index.php?t=4',
            },
            {
                url: 'https://www.site.ru/index.php?t=10&s=text#hashtag', set: { t: 4 }, replace: false, out: 'https://www.site.ru/index.php?t=4&s=text%23hashtag',
            },
        ],
        (data, i) => `${i}: ( ${data.url} , ${JSON.stringify(data.set)} , ${data.replace} ) => ${data.out}`,
        (data) => {
            const res = url.params(data.url, data.set, data.replace);
            // console.log(res);
            chai.expect(res).to.eql(data.out);
        });
    });
    describe('parsing', () => {
        tests([
            {
                url: 'https://www.site.ru/index.php',
                out: {
                    domen: 'www.site.ru',
                    file: 'index.php',
                    hash: '',
                    host: 'www.site.ru',
                    param: {},
                    params: '',
                    path: '',
                    protocol: 'https:',
                    url: 'https://www.site.ru/index.php',
                },
            },
            {
                url: 'www.site.ru/index',
                out: {
                    domen: 'www.site.ru',
                    file: 'index',
                    hash: '',
                    host: 'www.site.ru',
                    param: {},
                    params: '',
                    path: '',
                    protocol: 'http:',
                    url: 'www.site.ru/index',
                },
            },
            {
                url: 'www.site.ru/index.php?t=3&s=text',
                out: {
                    domen: 'www.site.ru',
                    file: 'index.php',
                    hash: '',
                    host: 'www.site.ru',
                    param: { t: '3', s: 'text' },
                    params: 't=3&s=text',
                    path: '',
                    protocol: 'http:',
                    url: 'www.site.ru/index.php?t=3&s=text',
                },
            },
            {
                url: 'https://www.site.ru/?t=3&s=text',
                out: {
                    domen: 'www.site.ru',
                    file: '',
                    hash: '',
                    host: 'www.site.ru',
                    param: { t: '3', s: 'text' },
                    params: 't=3&s=text',
                    path: '',
                    protocol: 'https:',
                    url: 'https://www.site.ru/?t=3&s=text',
                },
            },
            {
                url: 'https://www.site.ru/dir1/dir2/?t=3&s=text',
                out: {
                    domen: 'www.site.ru',
                    file: '',
                    hash: '',
                    host: 'www.site.ru',
                    param: { t: '3', s: 'text' },
                    params: 't=3&s=text',
                    path: 'dir1/dir2/',
                    protocol: 'https:',
                    url: 'https://www.site.ru/dir1/dir2/?t=3&s=text',
                },
            },
            {

                url: 'https://www.site.ru?t=3&s=text#hash_name',
                out: {
                    domen: 'www.site.ru',
                    file: '',
                    hash: 'hash_name',
                    host: 'www.site.ru',
                    param: { t: '3', s: 'text' },
                    params: 't=3&s=text',
                    path: '',
                    protocol: 'https:',
                    url: 'https://www.site.ru?t=3&s=text',
                },
            },
            {
                url: 'http://localhost:3000/?themeStyle=dark&themeSize=small&token=CHOBSER1T8QJ3GVA&order=edit&ID_ORDER=20306',
                out: {
                    domen: 'localhost',
                    file: '',
                    hash: '',
                    host: 'localhost:3000',
                    param: {
                        themeStyle: 'dark', themeSize: 'small', token: 'CHOBSER1T8QJ3GVA', order: 'edit', ID_ORDER: '20306',
                    },
                    params: 'themeStyle=dark&themeSize=small&token=CHOBSER1T8QJ3GVA&order=edit&ID_ORDER=20306',
                    path: '',
                    protocol: 'http:',
                    url: 'http://localhost:3000/?themeStyle=dark&themeSize=small&token=CHOBSER1T8QJ3GVA&order=edit&ID_ORDER=20306',
                },
            },
        ],
        (data, i) => `${i}: ${data.url} => ${JSON.stringify(data.out)}`,
        (data) => {
            const res = url.parsing(data.url);
            // console.log(res);
            chai.expect(res).to.eql(data.out);
        });
    });

    describe('nocache', () => {
        tests([
            {
                url: 'https://www.site.ru/index.php', out: `https://www.site.ru/index.php?${url.default.nocacheName}=XXXXXXX`,
            },
        ],
        (data, i) => `${i}:  ${data.url} => ${data.out}`,
        (data) => {
            const newUrl = url.nocache(data.url);
            const { param } = url.parsing(newUrl);
            // console.log(newUrl, param, url.default.nocacheName);
            chai.expect(url.default.nocacheName in param).to.equal(true);
        });
    });
});
