<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\Str;


final class StrTest extends TestCase{
    public function test_random(){
        $value = Str::random(13);
        //error_log(print_r($value,true));
        self::assertTrue( strlen($value) === 13 );
    }
};