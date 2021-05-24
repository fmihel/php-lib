<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\Type;

class ForObjTest {
    public $a = '';
};

final class TypeTest extends TestCase{

    public function test_is_numeric(){
        $value = 10;
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = 10.23;
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '';
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = 'a';
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = 'a10';
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = -10;
        $result = (Type::is_numeric($value) );
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '10';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = ' 10';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '-10';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '10.';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '10.0';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '10.102';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = 'a10.102';
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '10,102';
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '10.102a';
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '010';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '0';
        $result = (Type::is_numeric($value));
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = '10.102 a';
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = true;
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = false;
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = null;
        $result = (Type::is_numeric($value) === false);
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------

    }
    /**
     * @depends test_is_numeric
     */    
    public function test_type(){

        $value = 10;
        $type = Type::get($value);
        $result = ($type === 'integer');
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = 10.34;
        $type = Type::get($value);
        $result = ($type === 'double');
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = 'string';
        $type = Type::get($value);
        $result = ($type === 'string');
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = ['10','20',30,40];
        $type = Type::get($value);
        $result = ($type === 'array');
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = true;
        $type = Type::get($value);
        $result = ($type === 'boolean');
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = ['A'=>10,'B'=>20];
        $type = Type::get($value);
        $result = ($type === 'assoc');
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
        $value = new ForObjTest();
        $type = Type::get($value);
        $result = ($type === 'object');
        //error_log(print_r($type,true));
        self::assertTrue( $result );
        //------------------------------------
    }
}
?>