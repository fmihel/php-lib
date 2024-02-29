<?php
namespace fmihel\lib\test;

use fmihel\lib\Url;
use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
    public function test_join()
    {

        //------------------------------------------
        $result = Url::join('dir');
        self::assertTrue($result === 'dir');
        //------------------------------------------
        $result = Url::join('dir/');
        self::assertTrue($result === 'dir');
        //------------------------------------------
        $result = Url::join('/dir');
        self::assertTrue($result === '/dir');
        //------------------------------------------
        $result = Url::join('/dir/');
        self::assertTrue($result === '/dir');
        //------------------------------------------

        $result = Url::join('/dir/', '/dir2/', '/join');
        self::assertTrue($result === '/dir/dir2/join');
        //------------------------------------------
        $result = Url::join('dir/', 'dir2', '/join/');
        self::assertTrue($result === 'dir/dir2/join');
        //------------------------------------------

        $result = Url::join('dir', 'file.txt');
        self::assertTrue($result === 'dir/file.txt');
        //------------------------------------------
        $result = Url::join('path', 'dir', 'file.txt');
        self::assertTrue($result === 'path/dir/file.txt');
        //------------------------------------------
        $result = Url::join('path', '', 'file.txt');
        self::assertTrue($result === 'path//file.txt');

        //------------------------------------------
        $result = Url::join('http://');
        self::assertTrue($result === 'http://');
        //------------------------------------------
        $result = Url::join('http://', 'dir', 'file.txt');
        self::assertTrue($result === 'http://dir/file.txt');

        //------------------------------------------
        $result = Url::join('http://dir');
        self::assertTrue($result === 'http://dir');
        //------------------------------------------
        $result = Url::join('http://dir/');
        self::assertTrue($result === 'http://dir');

        //------------------------------------------
        //------------------------------------------
        //------------------------------------------

        $result = Url::join('a\\b');
        self::assertTrue($result === 'a\\b');
        //------------------------------------------
        $result = Url::join('\\a');
        self::assertTrue($result === '\a');
        //------------------------------------------
        $result = Url::join('a\\');
        self::assertTrue($result === 'a');
        //------------------------------------------
        $result = Url::join('\\a\\');
        self::assertTrue($result === '\\a');
        //------------------------------------------
        $result = Url::join('\\a\\', 'b\\');
        self::assertTrue($result === '\a\b');
        //------------------------------------------
        $result = Url::join('c:\\', 'b\\');
        self::assertTrue($result === 'c:\\b');
        //------------------------------------------
        $result = Url::join('c:\\b', 'b\\');
        self::assertTrue($result === 'c:\b\b');
        //------------------------------------------
        $result = Url::join('c:\b', 'b\\', 'a');
        self::assertTrue($result === 'c:\b\b\a');
        //------------------------------------------
        $result = Url::join('c:\b', '/a', '\c');
        self::assertTrue($result === 'c:\b\a\c');
        //------------------------------------------
        $result = Url::join('c:\b', '/a', '\c');
        self::assertTrue($result === 'c:\b\a\c');
        //------------------------------------------

    }
    public function test_build()
    {
        // --------------------------------------------
        $result = Url::build('http://test.php/');
        $expect = 'http://test.php/';
        self::assertEquals($expect, $result);
        // --------------------------------------------
        $result = Url::build('http://test.php/', ['a' => 10]);
        $expect = 'http://test.php/?a=10';
        self::assertEquals($expect, $result);
        // --------------------------------------------
        $result = Url::build('http://test.php/?', ['a' => 10]);
        $expect = 'http://test.php/?a=10';
        self::assertEquals($expect, $result);
        // --------------------------------------------
        $result = Url::build('http://test.php/?p', ['a' => 10]);
        $expect = 'http://test.php/?p&a=10';
        self::assertEquals($expect, $result);
        // --------------------------------------------
        $result = Url::build('http://test.php', ['a' => 10, 'b' => '10203']);
        $expect = 'http://test.php?a=10&b=10203';
        self::assertEquals($expect, $result);
        // --------------------------------------------
        $result = Url::build('http://test.php?   ', ['a' => 10, 'b' => '10203']);
        $expect = 'http://test.php?a=10&b=10203';
        self::assertEquals($expect, $result);
        // --------------------------------------------
        $result = Url::build('http://test.php   ', ['a' => 10, 'b' => '10203']);
        $expect = 'http://test.php?a=10&b=10203';
        self::assertEquals($expect, $result);
        // --------------------------------------------
    }
}
