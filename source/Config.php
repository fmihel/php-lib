<?php
namespace fmihel\lib;

class Config{
    private static $config;

    private static function get_config(){
        if (self::$config === null)
            self::$config = new ConfigClass();
        return self::$config;    
    }
    public static function get(...$param){
        $config = self::get_config();
        return $config->get(...$param);
    }
    public static function set($name,$value){
        $config = self::get_config();
        $config->set($name,$value);
    }
    public static function def($name,$value){
        $config = self::get_config();
        $config->def($name,$value);
    }
    public static function define($name,$value){
        $config = self::get_config();
        $config->def($name,$value);
    }
    public static function loadFromFile($__DIR__,$file='',$reopen=false){
        $config = self::get_config();
        return $config->loadFromFile($__DIR__,$file,$reopen);
    }
    


}

?>