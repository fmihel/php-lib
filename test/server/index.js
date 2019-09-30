/**
 * сервер для запуска тестов.
 * скрипт запуска см. в package.json scripts:{test:...}
 * После успешного запуска открывается страница браузера с нужным маршрутом
 */
const express = require('express');
const favicon = require('serve-favicon');
const open = require('open');
const config = require('./config');

const app = express();

app.use(config.path.test.virtual, express.static(config.path.test.path));
app.use(config.path.source.virtual, express.static(config.path.source.path));
app.use(config.path.source.virtual, (from, to) => {
    to.redirect(`${from.originalUrl}.js`);
});
app.use(favicon('./favicon.ico'));

app.listen(config.port, () => {
    console.info('Server for run tests: ok');
    const url = `http://localhost:${config.port}${config.path.test.virtual}`;
    console.info('start:', url);
    (async () => {
        await open(url);
    })();
});
