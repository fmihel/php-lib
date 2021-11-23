<?php
namespace fmihel\lib\test;

use PHPUnit\Framework\TestCase;
use fmihel\lib\ConfigClass;

$config = null;
function getConfig(){
    global $config;
    if ($config === null){
        $config = new ConfigClass();
        $config->loadFromFile(__DIR__.'/data/config.php');
    }
    return $config;
}

final class ConfigTest extends TestCase{

    public function test_get(){
        // --------------------------------------------
        $config = getConfig();
        // --------------------------------------------
        $res = $config->get('load');
        //error_log($res);
        self::assertTrue($res === 'ok');
        // --------------------------------------------
        $res = $config->get('noexists','default');
        self::assertTrue($res === 'default');
        // --------------------------------------------
        $this->expectException(\Exception::class);
        $res = $config->get('noexists');
        // --------------------------------------------
    }

    public function test_set(){
        // --------------------------------------------
        $config = getConfig();
        // --------------------------------------------
        $config->set('newVar',true);
        $res = $config->get('newVar');
        self::assertTrue($res === true);
    }

    public function test_def(){
        // --------------------------------------------
        $config = getConfig();
        // --------------------------------------------
        $config->def('load','newloadmean');
        $res = $config->get('load');
        self::assertTrue($res === 'ok');
        // --------------------------------------------
        $config->def('load2','newload2mean');
        $res = $config->get('load2');
        self::assertTrue($res === 'newload2mean');
        // --------------------------------------------
        $res = $config->get('load2');
        self::assertTrue($res === 'newload2mean');
    }
    

}

?>