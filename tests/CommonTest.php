<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\Common;


final class CommonTest extends TestCase{
    public function test_get(){

        $value = ['A'=>'20'];
        $current = Common::get($value,'A','ERROR');
        $result = ($current === '20');
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = [1,3,4,6];
        $current = Common::get($value,1,'ERROR');
        $result = ($current === 3);
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------

        $value = ['A'=>[1,3,4,6]];
        $current = Common::get($value,'A',2,'ERROR');
        $result = ($current === 4);
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = ['A'=>[1,['B'=>['C'=>10]],2]];
        $current = Common::get($value,'A',1,'B','C','ERROR');
        $result = ($current === 10);
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------

        $value = ['A'=>[1,3,4,6]];
        $current = Common::get($value,'B',2,'ERROR');
        $result = ($current === 'ERROR');
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = null;
        $current = Common::get($value,'B',2,'ERROR');
        $result = ($current === 'ERROR');
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = ['A'=>[1,3,4,6]];
        $current = Common::get($value,'B',2,'ERROR');
        $result = ($current === 'ERROR');
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = ['A'=>[1,3,4,6]];
        $current = Common::get($value,'A',7,'ERROR');
        $result = ($current === 'ERROR');
        //error_log(print_r($current,true));
        self::assertTrue( $result );
        //------------------------------------
        self::assertTrue( Common::get(['A'=>[1,3,5]],'A',3,-1) ===-1 );


    }
    public function test_eq(){
        
        self::assertTrue( (Common::eq(10,10)) === true);
        //------------------------------------        
        self::assertTrue( (Common::eq('string','string') === true) );
        //------------------------------------        
        self::assertTrue( (Common::eq('1',1) === true) );
        //------------------------------------        
        self::assertTrue( (Common::eq('10.67',10.67) === true) );
        //------------------------------------        
        self::assertTrue( (Common::eq('10.67',10.6) === false ));
        //------------------------------------        
        self::assertTrue( (Common::eq('10,67',10) === false) );
        //------------------------------------        
        self::assertTrue( (Common::eq(1,true) === true) );
        //------------------------------------        
        self::assertTrue( (Common::eq(0,false) === true) );
        //------------------------------------        
        self::assertTrue( (Common::eq('',false) === false) );
        //------------------------------------        
        self::assertTrue( (Common::eq('',true) === false) );
        //------------------------------------ 
        self::assertTrue( (Common::eq('0',false) === true) );
        //------------------------------------        
        self::assertTrue( (Common::eq(null,'') === false) );
        //------------------------------------        
        self::assertTrue( (Common::eq(null,0) === true) );
        //------------------------------------        
        self::assertTrue( (Common::eq(null,'0') === true) );
        //------------------------------------        
    }    
    public function test_isset(){

        self::assertFalse(Common::isset(0,'A','ERROR') );
        //------------------------------------
        self::assertTrue( Common::isset(['A'=>['ERROR'=>10]],'A','ERROR') );
        //------------------------------------
        self::assertTrue( Common::isset(['A'=>10],'A') );
        //------------------------------------
        self::assertFalse(Common::isset(null,'A','ERROR') );
        //------------------------------------
        self::assertTrue( Common::isset(['A'=>['ERROR'=>10]],'A','ERROR') );
        //------------------------------------
        self::assertTrue( Common::isset(['A'=>[1,3,5]],'A',1) );
        //------------------------------------
        self::assertFalse( Common::isset(['A'=>[1,3,5]],'A',3) );
    }

}

?>