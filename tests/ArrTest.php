<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\Arr;


class ForISAssoc {
    public $a = '';
};


final class ArrTest extends TestCase{

    public function test_is_assoc(){
        $data = ['test'=>10];
        
        self::assertTrue( Arr::is_assoc($data));
        
        $data = ['10',1,2,4,5];
        self::assertFalse(Arr::is_assoc($data));
        
        $data = new ForISAssoc();
        self::assertFalse(Arr::is_assoc($data));
        
    }
    public function test_eq(){
        //------------------------------------
        $a = [1,2,3,4];
        $b = [3,4,1,2];
        
        self::assertTrue(Arr::eq($a,$b));
        //------------------------------------
        $a = ['b'=>true,'a'=>10,1,2];
        $b = [2,1,'a'=>10,'b'=>true];
        
        self::assertTrue(Arr::eq($a,$b));
        //------------------------------------
        $a = ['b'=>true,'a'=>10];
        $b = ['a'=>10,'b'=>1];
        
        self::assertFalse(Arr::eq($a,$b,['compare'=>'strong']));
        //------------------------------------
        $a = ['b'=>true,'a'=>[10,1,2]];
        $b = ['a'=>[2,10,1],'b'=>true];
        
        self::assertTrue(Arr::eq($a,$b));
        //------------------------------------
        $a = ['b'=>true,'a'=>[10,'in'=>['place'],2]];
        $b = ['a'=>[2,10,'in'=>['place']],'b'=>true];
        
        self::assertTrue(Arr::eq($a,$b));
        //------------------------------------
    }
    /**
     * @depends test_eq
     */    
    public function test_extend(){

        $param = [];
        $add = ['a'=>10];
        $current = Arr::extend($param,$add);
        $result = Arr::eq($current,['a'=>10]);
        //error_log(print_r($current,true));
        self::assertTrue($result);
        //------------------------------------
        $param = ['test'=>10];
        $add = [];
        $current = Arr::extend($param,$add);
        $result = Arr::eq($current,['test'=>10]);
        //error_log(print_r($result,true));
        self::assertTrue($result);
        //------------------------------------
        $param = ['test'=>10];
        $add = ['a'=>10];
        $current = Arr::extend($param,$add);
        $result = Arr::eq($current,['a'=>10,'test'=>10]);
        //error_log(print_r($result,true));
        self::assertTrue($result);
        //------------------------------------
        $param = ['test'=>10,'a'=>[1,2,3,4]];
        $add = ['a'=>10];
        $current = Arr::extend($param,$add);
        $result = Arr::eq($current,['a'=>10,'test'=>10]);
        //error_log(print_r($result,true));
        self::assertTrue($result);
        //------------------------------------
        $param = ['test'=>10,'a'=>['c'=>30,'b'=>10] ];
        $add = ['a'=>['b'=>20]];
        $current = Arr::extend($param,$add);
        $result = Arr::eq($current,['a'=>['c'=>30,'b'=>20],'test'=>10]);
        self::assertTrue($result);
        //------------------------------------
        $param = [ 'test'=>10,'a'=>['c'=>30] ];
        $add   = [ 'a'=>['b'=>20] ];
        $current = Arr::extend($param,$add);
        $result = Arr::eq($current,['test'=>10,'a'=>['c'=>30,'b'=>20]]);
        //error_log(print_r($current,true));
        self::assertTrue($result);
        //------------------------------------
        $param = [ 'test'=>10,'a'=>['c'=>['lock'=>'more'] ]];
        $add   = [ 'a'=>['b'=>20,'c'=>['lock'=>'force','k'=>[1,2,3]]] ];
        $current = Arr::extend($param,$add);
        $result = Arr::eq($current,['test'=>10,'a'=>['c'=>['k'=>[2,3,1],'lock'=>'force'],'b'=>20]]);
        //error_log(print_r($current,true));
        self::assertTrue($result);
        //------------------------------------
        
    }
}

?>