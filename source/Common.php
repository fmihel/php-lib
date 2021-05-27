<?php
namespace fmihel\lib;
use fmihel\lib\Type;
use fmihel\console;

class Common {


    /** Common::get($var,...$props,$default)
     * 
     */
    public static function get($var,...$props){
        $count = count($props);
        if ($count<=1)
            throw new \Exception("Common::get must have 3 or more params");
        $default = $props[$count-1];            
        $count--;
            
        if (!isset($var))
            return $default;

        for($i=0;$i<$count;$i++){
            $prop = $props[$i];

            if ((is_string($prop))&&(is_numeric($prop)))
                $prop = intval($prop);
                
            $type = Type::get($var);    
            if ( ( ($type==='array')||($type==='assoc'))&&(isset($var[$prop]) ) ){
                
                if ( $i === $count-1 )
                    return $var[$prop];    
                else    
                    $var = $var[$prop];
                    
            }elseif ( ($type==='object')&&(property_exists($var,$prop))){

                if ($i===$count-1)
                    return $var->{$prop};    
                else    
                    $var = $var->{$prop};                
            }else
                return $default;    
        }
    }
    

    /** сравнение двух величин (только для строк и чисел), сначала идет строгое сравнение (если типы совпадают)
     * 
     */
    public static function eq($a,$b){

        $typeA = Type::get($a);
        $typeB = Type::get($b);

        if ($typeA === $typeB)
            return $a === $b;

        $left = false;
        $right = false;
        $typeLeft = '';
        $typeRight = '';

        $norm = function ($type) use (&$left,&$right,&$typeLeft,&$typeRight,$typeA,$typeB,$a,$b) {
            if ($typeA === $type){
                $left = $a;
                $right = $b;
                $typeLeft = $typeA;
                $typeRight = $typeB;
                return true;
            }
            if ($typeB === $type){
                $left = $b;
                $right = $a;
                $typeLeft = $typeB;
                $typeRight = $typeA;
                return true;
            }
            return false;
        };

        if ($norm('string')){
            $left = trim($left);
            if (Type::is_numeric($right))
                    return ($left == trim($right.''));
        }

        if ($norm('boolean')){

            if ($typeRight === 'string'){
                $right = trim($right);
                return (($left && $right === '1') || (!$left && $right === '0'));
            }

            if (Type::is_numeric($right)){
                return (($left===true && $right === 1) || ($left===false && $right === 0));
            }
        }

        if ($norm('NULL')){
            if ($typeRight === 'string'){
                $right = trim($right);
                return ($right === '0');
            }
            
            if (Type::is_numeric($right)){
                return ( $right === 0 );
            }

        }
        
        return false;

    }

    
}