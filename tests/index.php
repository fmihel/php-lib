<?php

use fmihel\lib\Dir;

require_once __DIR__ . '/source/Dir.php';
require_once __DIR__ . '/source/Str.php';

function msg(...$args)
{
    error_log('---------------------------');
    foreach ($args as $arg) {
        error_log(print_r($arg, true));
    }
    error_log('---------------------------');
};

$path = 'D:\work\windeco\windeco3\tests\server\\';

$files = Dir::files($path, 'php', true, false);

msg('files', $files);
