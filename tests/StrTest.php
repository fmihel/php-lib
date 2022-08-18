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
        $out =   'abvgdeegziyklmnoprstufhchsseieeuyABVGDEEGZIYKLMNOPRSTUFHCHSSEIEEUY _-';

        //error_log(Str::translit($value));
        self::assertTrue( Str::translit($value) === $out );
        
        $value = 'русский';
        $out = 'russkiy';
        self::assertTrue( Str::translit($value) === $out );

        $value = '123Я';
        $out = '123Y';
        self::assertTrue( Str::translit($value) === $out );

        $value = '._.-+ ';
        $out = '._.-+ ';
        self::assertTrue( Str::translit($value) === $out );

        $value = 'english';
        $out = 'english';
        self::assertTrue( Str::translit($value) === $out );

        $value = '&^+#$@%!.,';
        $out = '&^+#$@%!.,';
        self::assertTrue( Str::translit($value) === $out );

    }
    public function test_translitToUrl(){
        $value = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ _-';
        $out =   'abvgdeegziyklmnoprstufhchsseieeuyABVGDEEGZIYKLMNOPRSTUFHCHSSEIEEUY___';
        //error_log(Str::translitToUrl($value));
        self::assertTrue( Str::translitToUrl($value) === $out );
        
        $value = 'русский';
        $out = 'russkiy';
        self::assertTrue( Str::translitToUrl($value) === $out );

        $value = '/123Я';
        $out = '/123Y';
        self::assertTrue( Str::translitToUrl($value) === $out );

        $value = '..-+ ';
        $out = '__';
        self::assertTrue( Str::translitToUrl($value) === $out );

        $value = 'english';
        $out = 'english';
        self::assertTrue( Str::translitToUrl($value) === $out );

        $value = '&^+#$@%!.,';
        $out = '';
        self::assertTrue( Str::translitToUrl($value) === $out );

        $value = 'path1/путь4/ path2\dir ';
        $out = 'path1/pute4/_path2/dir_';
        self::assertTrue( Str::translitToUrl($value) === $out );
        
    }

};