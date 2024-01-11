<?php
namespace fmihel\lib\test;

use fmihel\lib\Dir;
use fmihel\lib\System;
use PHPUnit\Framework\TestCase;

final class DirTest extends TestCase
{

    public function test_join()
    {
        $__DIR__ = "D:\\work\\fmihel\\php-lib\\tests";
        //-------------------------------------
        $result = Dir::join("path", "path", "file.jpg");
        $expect = 'path/path/file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join("/path/", "/path/path", "file.jpg");
        $expect = '/path/path/path/file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join("path/", "////", "/path", "file.jpg");
        $expect = 'path/path/file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join('D:\\', "path/file.jpg");
        $expect = 'D:\\path\\file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join($__DIR__, "path/file.jpg");
        $expect = $__DIR__ . '\path\file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join($__DIR__, "\path/file.jpg");
        $expect = $__DIR__ . '\path\file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join("/path/", "", "path", "file.jpg");
        $expect = '/path/path/file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join("/left/", "text\more", "right", "file.jpg");
        $expect = '/left/text/more/right/file.jpg';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join("https://", "text\more", "file.php");
        $expect = 'https://text/more/file.php';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::join("ftp://more/", "text\more", "more/line//", "/file.php");
        $expect = 'ftp://more/text/more/more/line/file.php';
        self::assertEquals($expect, $result);

    }
    public function test_files()
    {

        $path = System::is_win() ? "D:\\work\\fmihel\\php-lib\\tests\\data" : "./tests/data";
        $result = Dir::files($path, ['txt', 'php', 'dat'], true, false);
        error_log(print_r($result, true));
        self::assertTrue(true);
    }
    public function test_ext()
    {
        $result = Dir::ext('file.php');
        $expect = 'php';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::ext('d:\test\file.docx');
        $expect = 'docx';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::ext('d:\test\file.docx/more');
        $expect = '';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::ext('line/stone/more.dat');
        $expect = 'dat';
        self::assertEquals($expect, $result);
        //-------------------------------------
        $result = Dir::ext('line/stone/.gitignore');
        $expect = 'gitignore';
        self::assertEquals($expect, $result);
    }
    // public function test_slash(){
    //     //-------------------------------------
    //     $in = 'mytext/width';
    //     $out = '/mytext/width/';

    //     $res = Dir::slash($in,true,true,'/');
    //     self::assertSame($out,$res );
    //     //-------------------------------------
    //     $in = 'mytext/width.js';
    //     $out = '/mytext/width.js';

    //     $res = Dir::slash($in,true,true,'/');
    //     self::assertSame($out,$res );
    //     //-------------------------------------
    //     $in = '/mytext/width/';
    //     $out = 'mytext/width';

    //     $res = Dir::slash($in,false,false,'/');
    //     self::assertSame($out,$res );
    //     //-------------------------------------
    // }
    // public function test_abs_path(){
    //     //-------------------------------------
    //     $from = 'E:\\temp\\more\\lib';
    //     $to   = '';
    //     $result = Dir::abs_path($from,$to,'\\');
    //     $compare =  'E:\\temp\\more\\lib\\';
    //     //error_log($result);
    //     self::assertSame($result == $compare,true);
    //     //-------------------------------------
    //     $from = 'E:\temp\more\lib';
    //     $to   = '../../screen';
    //     $result = Dir::abs_path($from,$to,'\\');
    //     $compare =  'E:\\temp\\screen\\';
    //     //error_log($result);
    //     self::assertSame($result == $compare,true);
    //     //-------------------------------------
    // }
    // public function test_rel_path(){
    //     //-------------------------------------
    //     $from = '/home/decoinf3/public_html/test/myproject/';
    //     $to   = '/home/decoinf3/public_html/rest/a';
    //     $result = Dir::rel_path($from,$to,'/');
    //     // ../../rest/a
    //     $compare =  '../../rest/a';
    //     //error_log($result);
    //     self::assertSame($result == $compare,true);
    //     //-------------------------------------
    //     $from = '/home/decoinf3/public_html/test/myproject/';
    //     $to   = '/home/decoinf3/public_html/more/data/';
    //     $result = Dir::rel_path($from,$to,'/');
    //     // ../../more/data
    //     $compare =  '../../more/data';
    //     //error_log($result);
    //     self::assertSame($result == $compare,true);
    //     //-------------------------------------
    //     $from = 'E:/upper/lower/set';
    //     $to   = 'E:/upper/maximal/len';
    //     $result = Dir::rel_path($from,$to,'/');
    //     // ../../maximal/len
    //     $compare =  '../../maximal/len';
    //     //error_log($result);
    //     self::assertSame($result == $compare,true);
    //     //-------------------------------------
    // }
    // public function test_pathAsUnix(){
    //     //-------------------------------------
    //     $path = '/path\\path/path\\';
    //     $res = '/path/path/path/';
    //     self::assertEquals(Dir::pathAsUnix($path),$res );
    //     //-------------------------------------
    // }
    // public function test_pathAsDos(){
    //     //-------------------------------------
    //     $path = '/path/path/path\\';
    //     $res = '\\path\\path\\path\\';
    //     self::assertEquals(Dir::pathAsDos($path),$res );
    //     //-------------------------------------
    // }
    public function test_pathinfo()
    {
        //-------------------------------------
        $path = 'c:\test\test2\file.txt';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'c:\test\test2\file.txt');
        self::assertEquals($info['dirname'], 'c:\test\test2');
        self::assertEquals($info['basename'], 'file.txt');
        self::assertEquals($info['extension'], 'txt');
        self::assertEquals($info['filename'], 'file');
        //-------------------------------------
        $path = 'c:\test\test2';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'c:\test\test2');
        self::assertEquals($info['dirname'], 'c:\test');
        self::assertEquals($info['basename'], 'test2');
        self::assertEquals($info['extension'], '');
        self::assertEquals($info['filename'], 'test2');
        //-------------------------------------
        $path = 'c:\test\test2\\';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'c:\test\test2\\');
        self::assertEquals($info['dirname'], 'c:\test\test2');
        self::assertEquals($info['basename'], '');
        self::assertEquals($info['extension'], '');
        self::assertEquals($info['filename'], '');
        //-------------------------------------
        $path = 'c:\test\test2\file.';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'c:\test\test2\file.');
        self::assertEquals($info['dirname'], 'c:\test\test2');
        self::assertEquals($info['basename'], 'file.');
        self::assertEquals($info['extension'], '');
        self::assertEquals($info['filename'], 'file');
        //-------------------------------------
        $path = 'http://www.windeco.su/test.php';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'http://www.windeco.su/test.php');
        self::assertEquals($info['dirname'], 'http://www.windeco.su');
        self::assertEquals($info['basename'], 'test.php');
        self::assertEquals($info['extension'], 'php');
        self::assertEquals($info['filename'], 'test');
        //-------------------------------------
        $path = 'https://www.windeco.su/test.php';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'https://www.windeco.su/test.php');
        self::assertEquals($info['dirname'], 'https://www.windeco.su');
        self::assertEquals($info['basename'], 'test.php');
        self::assertEquals($info['extension'], 'php');
        self::assertEquals($info['filename'], 'test');
        //-------------------------------------
        $path = 'https://www.windeco.su/test/index';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'https://www.windeco.su/test/index');
        self::assertEquals($info['dirname'], 'https://www.windeco.su/test');
        self::assertEquals($info['basename'], 'index');
        self::assertEquals($info['extension'], '');
        self::assertEquals($info['filename'], 'index');
        //-------------------------------------
        $path = 'atest/test/index.txt';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'], 'atest/test/index.txt');
        self::assertEquals($info['dirname'], 'atest/test');
        self::assertEquals($info['basename'], 'index.txt');
        self::assertEquals($info['extension'], 'txt');
        self::assertEquals($info['filename'], 'index');
        //-------------------------------------
        $path = 'atest/test/index/';
        $info = Dir::pathinfo($path);
        //error_log(print_r($info, true));
        self::assertEquals($info['file'], 'atest/test/index/');
        self::assertEquals($info['dirname'], 'atest/test/index');
        self::assertEquals($info['basename'], '');
        self::assertEquals($info['extension'], '');
        self::assertEquals($info['filename'], '');
        //-------------------------------------
    }
}
