<?php
namespace fmihel\lib;
use fmihel\lib\Arr;


class Type {
    public static function get($value){
        $type = gettype($value);
        
        if (($type==='array')&&(Arr::is_assoc($value)))
            $type = 'assoc';

        return $type;
    }
    public static function is_numeric($val){
        if (($val === true) || ($val === false)) 
            return false;
            
        $val = trim($val.'');
        $result = is_numeric($val);  


        return $result;
    }


}
?>