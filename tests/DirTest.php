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
}

?>