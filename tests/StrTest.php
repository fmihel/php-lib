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

    public function test_translit(){
        $value = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ _-';
        $out = 'abvgdeegziyklmnoprstufhchssuiueuyABVGDEEGZIYKLMNOPRSTUFHCHSSUIUEUY___';
        self::assertTrue( Str::translit($value) === $out );

        $value = 'русский';
        $out = 'russkiy';
        self::assertTrue( Str::translit($value) === $out );

        $value = '123Я';
        $out = '123Y';
        self::assertTrue( Str::translit($value) === $out );

        $value = '..-+ ';
        $out = '__';
        self::assertTrue( Str::translit($value) === $out );

        $value = 'english';
        $out = 'english';
        self::assertTrue( Str::translit($value) === $out );

        $value = '&^+#$@%!.,';
        $out = '';
        self::assertTrue( Str::translit($value) === $out );
    }

};