<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\Dir;


final class DirTest extends TestCase{

    public function test_slash(){
        //-------------------------------------        
        $in = 'mytext/width';
        $out = '/mytext/width/';

        $res = Dir::slash($in,true,true,'/');
        self::assertSame($out,$res );
        //-------------------------------------        
        $in = 'mytext/width.js';
        $out = '/mytext/width.js';

        $res = Dir::slash($in,true,true,'/');
        self::assertSame($out,$res );
        //-------------------------------------        
        $in = '/mytext/width/';
        $out = 'mytext/width';

        $res = Dir::slash($in,false,false,'/');
        self::assertSame($out,$res );
        //-------------------------------------        
    }
    public function test_abs_path(){
        //-------------------------------------        
        $from = 'E:\\temp\\more\\lib';
        $to   = '';
        $result = Dir::abs_path($from,$to,'\\');
        $compare =  'E:\\temp\\more\\lib\\';
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
        $from = 'E:\temp\more\lib';
        $to   = '../../screen';
        $result = Dir::abs_path($from,$to,'\\');
        $compare =  'E:\\temp\\screen\\';
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
    }
    public function test_rel_path(){
        //-------------------------------------        
        $from = '/home/decoinf3/public_html/test/myproject/';
        $to   = '/home/decoinf3/public_html/rest/a';
        $result = Dir::rel_path($from,$to,'/');
        // ../../rest/a
        $compare =  '../../rest/a';
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
        $from = '/home/decoinf3/public_html/test/myproject/';
        $to   = '/home/decoinf3/public_html/more/data/';
        $result = Dir::rel_path($from,$to,'/');
        // ../../more/data
        $compare =  '../../more/data';
        //error_log($result);
        self::assertSame($result == $compare,true);
        //-------------------------------------        
        $from = 'E:/upper/lower/set';
        $to   = 'E:/upper/maximal/len';
        $result = Dir::rel_path($from,$to,'/');
        // ../../maximal/len
        $compare =  '../../maximal/len';
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
    public function test_pathinfo(){
        //-------------------------------------        
        $path = 'c:\test\test2\file.txt';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'c:\test\test2\file.txt');
        self::assertEquals($info['dirname'],'c:\test\test2');
        self::assertEquals($info['basename'],'file.txt');
        self::assertEquals($info['extension'],'txt');        
        self::assertEquals($info['filename'],'file');
        //-------------------------------------        
        $path = 'c:\test\test2';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'c:\test\test2');
        self::assertEquals($info['dirname'],'c:\test');
        self::assertEquals($info['basename'],'test2');
        self::assertEquals($info['extension'],'');        
        self::assertEquals($info['filename'],'test2');
        //-------------------------------------        
        $path = 'c:\test\test2\\';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'c:\test\test2\\');
        self::assertEquals($info['dirname'],'c:\test\test2');
        self::assertEquals($info['basename'],'');
        self::assertEquals($info['extension'],'');        
        self::assertEquals($info['filename'],'');
        //-------------------------------------        
        $path = 'c:\test\test2\file.';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'c:\test\test2\file.');
        self::assertEquals($info['dirname'],'c:\test\test2');
        self::assertEquals($info['basename'],'file.');
        self::assertEquals($info['extension'],'');        
        self::assertEquals($info['filename'],'file');
        //-------------------------------------        
        $path = 'http://www.windeco.su/test.php';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'http://www.windeco.su/test.php');
        self::assertEquals($info['dirname'],'http://www.windeco.su');
        self::assertEquals($info['basename'],'test.php');
        self::assertEquals($info['extension'],'php');        
        self::assertEquals($info['filename'],'test');
        //-------------------------------------        
        $path = 'https://www.windeco.su/test.php';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'https://www.windeco.su/test.php');
        self::assertEquals($info['dirname'],'https://www.windeco.su');
        self::assertEquals($info['basename'],'test.php');
        self::assertEquals($info['extension'],'php');        
        self::assertEquals($info['filename'],'test');
        //-------------------------------------        
        $path = 'https://www.windeco.su/test/index';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'https://www.windeco.su/test/index');
        self::assertEquals($info['dirname'],'https://www.windeco.su/test');
        self::assertEquals($info['basename'],'index');
        self::assertEquals($info['extension'],'');        
        self::assertEquals($info['filename'],'index');
        //-------------------------------------        
        $path = 'atest/test/index.txt';        
        $info = Dir::pathinfo($path);
        //error_log(print_r($info,true));
        self::assertEquals($info['file'],'atest/test/index.txt');
        self::assertEquals($info['dirname'],'atest/test');
        self::assertEquals($info['basename'],'index.txt');
        self::assertEquals($info['extension'],'txt');        
        self::assertEquals($info['filename'],'index');
        //-------------------------------------        
        $path = 'atest/test/index/';        
        $info = Dir::pathinfo($path);
        error_log(print_r($info,true));
        self::assertEquals($info['file'],'atest/test/index/');
        self::assertEquals($info['dirname'],'atest/test/index');
        self::assertEquals($info['basename'],'');
        self::assertEquals($info['extension'],'');        
        self::assertEquals($info['filename'],'');
        //-------------------------------------        
    }
}

?>