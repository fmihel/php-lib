<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\Dir;


final class DirTest extends TestCase{

    public function test_slash(){
        //-------------------------------------        
        $in = 'mytext/width';
        $out = '/'.$in.'/';

        $res = Dir::slash($in);
        self::assertSame($out,$res );
        //-------------------------------------        
        $in = 'mytext/width.js';
        $out = '/'.$in.'';

        $res = Dir::slash($in);
        self::assertSame($out,$res );
        //-------------------------------------        
        $in = '/mytext/width/';
        $out = 'mytext/width';

        $res = Dir::slash($in,false,false);
        self::assertSame($out,$res );
        //-------------------------------------        
    }
    public function test_abs_path(){
        //-------------------------------------        
        $from = 'E:\temp\more\lib';
        $to   = '';
        $result = Dir::abs_path($from,$to);
        $compare =  'E:'._DIRECTORY_SEPARATOR.'temp'._DIRECTORY_SEPARATOR.'more'._DIRECTORY_SEPARATOR.'lib'._DIRECTORY_SEPARATOR;
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
        $from = 'E:\temp\more\lib';
        $to   = '../../screen';
        $result = Dir::abs_path($from,$to);
        // E:\temp\screen
        $compare =  'E:'._DIRECTORY_SEPARATOR.'temp'._DIRECTORY_SEPARATOR.'screen'._DIRECTORY_SEPARATOR;
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
    }
    public function test_rel_path(){
        //-------------------------------------        
        $from = '/home/decoinf3/public_html/test/myproject/';
        $to   = '/home/decoinf3/public_html/rest/a';
        $result = Dir::rel_path($from,$to);
        // ../../rest/a
        $compare =  '..'._DIRECTORY_SEPARATOR.'..'._DIRECTORY_SEPARATOR.'rest'._DIRECTORY_SEPARATOR.'a';
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
        $from = '/home/decoinf3/public_html/test/myproject/';
        $to   = '/home/decoinf3/public_html/more/data/';
        $result = Dir::rel_path($from,$to);
        // ../../more/data
        $compare =  '..'._DIRECTORY_SEPARATOR.'..'._DIRECTORY_SEPARATOR.'more'._DIRECTORY_SEPARATOR.'data';
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
        $from = 'E:/upper/lower/set';
        $to   = 'E:/upper/maximal/len';
        $result = Dir::rel_path($from,$to);
        // ../../maximal/len
        $compare =  '..'._DIRECTORY_SEPARATOR.'..'._DIRECTORY_SEPARATOR.'maximal'._DIRECTORY_SEPARATOR.'len';
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
    }
    public function test_pathAsUnix(){
        //-------------------------------------        
        $path = '/path\\path/path\\';        
        $res = '/path/path/path/';
        self::assertEquals(Dir::pathAsUnix($path),$res );
        //-------------------------------------        
    }
    public function test_pathAsDos(){
        //-------------------------------------        
        $path = '/path/path/path\\';        
        $res = '\\path\\path\\path\\';
        self::assertEquals(Dir::pathAsDos($path),$res );
        //-------------------------------------        
    }
    public function test_join(){
        //-------------------------------------        
        $paths = ["/path\\","path","file.jpg"];        
        $res = '\\path\\path\\file.jpg';
        self::assertEquals(Dir::join($paths),$res );
        //-------------------------------------        
        $paths = ["/path/","/path/path","file.jpg"];        
        $res = '/path/path/path/file.jpg';
        self::assertEquals(Dir::join($paths),$res );
        //-------------------------------------        
        $paths = ["/path/","//","path","file.jpg"];        
        $res = '/path//path/file.jpg';
        self::assertEquals(Dir::join($paths),$res );
        //-------------------------------------        
        $paths = ["/path/","","path","file.jpg"];        
        $res = '/path//path/file.jpg';
        self::assertEquals(Dir::join($paths),$res );
        //-------------------------------------        
        $paths = ["/left/","text\more","right","file.jpg"];        
        $res = '/left/text\more/right/file.jpg';
        $as = 'asis';
        self::assertEquals(Dir::join($paths,$as),$res );
        //-------------------------------------        
        $paths = ["/left/","text\more","right","/file.jpg"];        
        $as = 'unix';
        $res = '/left/text/more/right/file.jpg';
        self::assertEquals(Dir::join($paths,$as),$res );
        //-------------------------------------        
        $paths = ["/left/","text\more","right","/file.jpg"];        
        $as = 'dos';
        $res = '\\left\\text\\more\\right\\file.jpg';
        self::assertEquals(Dir::join($paths,$as),$res );
        //-------------------------------------        
        $paths = ["/left\\","text\more","right","/file.jpg"];        
        $as = 'auto';
        $res = '\\left\\text\\more\\right\\file.jpg';
        self::assertEquals(Dir::join($paths,$as),$res );
        //-------------------------------------        
        $paths = ["http://www.site.ru/path","text\more","right","/file.jpg"];        
        $as = '';
        $res = 'http://www.site.ru/path/text\\more/right/file.jpg';
        self::assertEquals(Dir::join($paths,$as),$res );
        //-------------------------------------        
        $paths = ["http://www.site.ru/path","text\more","right","/file.jpg"];        
        $as = 'unix';
        $res = 'http://www.site.ru/path/text/more/right/file.jpg';
        self::assertEquals(Dir::join($paths,$as),$res );
        //-------------------------------------        
        $paths = ["http://www.site.ru/path","text\more","right","/file.jpg"];        
        $as = 'dos';
        $res = 'http:\\\\www.site.ru\path\text\more\right\file.jpg';
        self::assertEquals(Dir::join($paths,$as),$res );
        //------------------------------------- 
        $paths = ["http://www.site.ru/path","text\more","right","/file.jpg"];        
        $as = 'unix';
        $res = 'http://www.site.ru/path/text/more/right/file.jpg';
        self::assertEquals(Dir::join($paths,$as),$res );
        //-------------------------------------        
        $paths = ["http://www.site.ru/path","text\more","right\\","\\file.jpg"];        
        $res = 'http://www.site.ru/path/text/more/right/file.jpg';
        self::assertEquals(Dir::join($paths),$res );
        //-------------------------------------        

    }
}

?>