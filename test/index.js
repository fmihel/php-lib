import mocha from 'mocha';
import test from './dom-test';

$(() => {
    mocha.setup();

    test();

    mocha.run();
    mocha.checkLeaks();
});
