<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\{Config,Base};

define('TABLE_FILL','test_clients');
define('TABLE_EMPTY','test_clients_phone');

// tables  ---------------
// test_clients - is filled
// test_clients_phone - is empty
//  ------------------------

final class BaseTest extends TestCase{

    public static function setUpBeforeClass(): void
    {
        Config::loadFromFile(__DIR__.'/data/configBase.php');
        /*
        Base::connect(
            Config::get('server'),
            Config::get('user'),
            Config::get('pass'),
            Config::get('baseName'),
            'test'
        );
        */
        Base::connect(Config::get('test'));

    }    
    public function test_connect(){
        $connect = Base::connect(
            Config::get('server'),
            Config::get('user'),
            Config::get('pass'),
            Config::get('baseName'),
            'test'
        );
        self::assertTrue( $connect === true);
    }

    /**
     * @depends test_connect
     */    
    public function test_query(){
        $q = 'select * from '.TABLE_FILL;
        $res = Base::query($q,'test');
        //error_log(print_r($res,true));
        self::assertTrue( $res!==false );

         // --------------------------------------------
         $this->expectException(\Exception::class);
         $res = Base::query($q,'test2');
         // --------------------------------------------
    }
    /**
     * @depends test_connect
     */    
    public function test_ds(){
        // ------------------------------
        $q = 'select * from '.TABLE_FILL;
        $ds = Base::ds($q,'test');
        self::assertTrue( Base::assign($ds) );
        // ------------------------------
        self::assertTrue( !Base::isEmpty($ds) );
        // ------------------------------
        $q = 'select * from '.TABLE_EMPTY;
        $ds = Base::ds($q,'test');
        self::assertTrue( Base::isEmpty($ds) );
        // ------------------------------
        $q = 'select * from qwedwqd'.TABLE_FILL;
        $this->expectException(\Exception::class);        
        $ds = Base::ds($q,'test');
        
    }

    /**
     * @depends test_connect
     */    
    public function test_row(){

        $q = 'select * from '.TABLE_FILL;
        $ds = Base::ds($q,'test');
        $row = Base::row($ds);
        self::assertTrue(gettype($row) === 'array'  );

        $q = 'select * from '.TABLE_EMPTY;
        $ds = Base::ds($q,'test');
        $row = Base::row($ds);
        //error_log(gettype($row));
        self::assertNull($row);
    }

    /**
     * @depends test_connect
     */    
    public function test_read(){

        $q = 'select * from '.TABLE_FILL;
        $ds = Base::ds($q,'test');
        $count = Base::count($ds);
        $res = true;
        while($row = Base::read($ds)){
            if (gettype($row) !== 'array')
                $res = false;
            $count--;
        }
        self::assertTrue(($res&&$count === 0));


        $q = 'select * from '.TABLE_EMPTY;
        $ds = Base::ds($q,'test');
        $count = Base::count($ds);
        $res = true;
        while($row = Base::read($ds)){
            if (gettype($row) !== 'array')
                $res = false;
            $count--;
        }
        self::assertTrue(($res&&$count === 0));

    }

    /**
     * @depends test_connect
     */    
    public function test_value(){

        $q = 'select NAME from '.TABLE_FILL;
        $value = Base::value($q,'test');
        self::assertTrue($value === 'bbbb');
        // --------------------------------------------
        $q = 'select NAMEZ from '.TABLE_FILL;
        $value = Base::value($q,'test',['default'=>133]);
        self::assertTrue($value === 133);
        // --------------------------------------------
        $q = 'select NAME,AGE from '.TABLE_FILL;
        $value = Base::value($q,'test',['field'=>'AGE']);
        self::assertTrue($value==2);
        // --------------------------------------------
        $q = 'select NAMEZ from '.TABLE_FILL;
        $this->expectException(\Exception::class);
        $value = Base::value($q,'test');
        // --------------------------------------------
    }

    /**
     * @depends test_connect
     */    
    public function test_generate(){
        $table = 'MY_TABLE';
        $data = ['NAME'=>'Mike','AGE'=>12,'STORY'=>'ok','ID'=>102934];
        $types = ['NAME'=>'string','AGE'=>'float','STORY'=>'string'];
        $param = ['types'=>$types];
        // ----------------------------------------        
        $result = Base::generate('insert',$table,$data,$param);
        $equal = 'insert into `MY_TABLE` (`NAME`,`AGE`,`STORY`,`ID`) values ("Mike",12,"ok",102934)';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        $dataSelf = $data;
        $dataSelf['NAME']= [$dataSelf['NAME'],'string'];
        $dataSelf['STORY']= [$dataSelf['STORY'],'string'];
        $result = Base::generate('insert',$table,$dataSelf,[]);
        $equal = 'insert into `MY_TABLE` (`NAME`,`AGE`,`STORY`,`ID`) values ("Mike",12,"ok",102934)';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        $result = Base::generate('insert',$table,$data,array_merge($param,['exclude'=>'ID']));
        $equal = 'insert into `MY_TABLE` (`NAME`,`AGE`,`STORY`) values ("Mike",12,"ok")';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        $result = Base::generate('update',$table,$data,array_merge($param,['exclude'=>'ID']));
        $equal = 'update `MY_TABLE` set `NAME`="Mike",`AGE`=12,`STORY`="ok"';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        $result = Base::generate('update',$table,$data,array_merge($param,['include'=>'NAME,AGE']));
        $equal = 'update `MY_TABLE` set `NAME`="Mike",`AGE`=12';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        $result = Base::generate('insertOnDuplicate',$table,$data,array_merge($param,['exclude'=>'ID']));
        $equal = 'insert into `MY_TABLE` (`NAME`,`AGE`,`STORY`) values ("Mike",12,"ok") on duplicate key update `NAME`="Mike",`AGE`=12,`STORY`="ok"';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        

        $result = Base::generate('update',$table,$data,['where'=>'ID=::ID','types'=>$types]);
        $equal = 'update `MY_TABLE` set `NAME`="Mike",`AGE`=12,`STORY`="ok",`ID`=102934 where ID=102934';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        $result = Base::generate('update',$table,$data,['where'=>'ID=::ID and AGE=::AGE','exclude'=>'NAME','types'=>$types]);
        $equal = 'update `MY_TABLE` set `AGE`=12,`STORY`="ok",`ID`=102934 where ID=102934 and AGE=12';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        $result = Base::generate(
            'update',
            'DEALER',
            [
                'ID_DEALER' =>  1839,
                'NAME'      =>  ['Mike','string'],
                'DAY'       =>  10,
                'ARCH'      =>  [1,'string'],
            ],
            [
                'where' =>'ID_DEALER=::ID_DEALER or ARCH="1"',
                'exclude'=>['ID_DEALER','ARCH']
            ]
        );

        $equal = 'update `DEALER` set `NAME`="Mike",`DAY`=10 where ID_DEALER=1839 or ARCH="1"';
        //error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        
        // ----------------------------------------        
        $result = Base::generate(
            'update',
            'DEALER',
            [
                'ID_DEALER' =>  1839,
                'NAME'      =>  ['Mike','string'],
                'DAY'       =>  10,
                'ARCH'      =>  [1,'string'],
            ],
            [
                'rename'=>['ID_DEALER'=>'ID'],    
                'where' =>'ID=::ID or ARCH="1"',
                'exclude'=>['ID','ARCH']
            ]
        );

        $equal = 'update `DEALER` set `NAME`="Mike",`DAY`=10 where ID=1839 or ARCH="1"';
        error_log($result);
        self::assertSame($result,$equal);
        // ----------------------------------------        


    }


}

?>