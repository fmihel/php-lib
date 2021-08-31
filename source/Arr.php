<?php
namespace fmihel\lib;

class Arr {
    /** 
     * проверка, является ли входная переменная ассоциативным массивом
    */
    public static function is_assoc($array){
        $result = false;
        try{
        
            $result  = ( count(array_filter(array_keys($array), 'is_string')) > 0 );
        
        }catch(\Exception $e){
                
        }
        return $result;
    }
    /** 
     * аналог ф-ции extend jQuery
     * только ассоциативные массивы
    */
    public static function extend($a = [],$b = []){
        
        if ((is_array($a)) && (is_array($b))) {
            if ($a === []) 
                return $b;

            $res = [];
                
            if (self::is_assoc($a)) {

                foreach ($a as $k => $v) {
                  if (!isset($b[$k])) {
                        $res[$k] = $v;
                    } else {
                        if ((is_array($v)) && (is_array($b[$k]))) {
                            $res[$k] = self::extend($v, $b[$k]);
                        } else {
                            $res[$k] = $b[$k];
                        }
                    }
                }

                foreach($b as $k=>$v){
                    if (!isset($a[$k])){
                        $res[$k] = $v;
                    }
                }


            }
            return $res;
        };
        return $a;
    }
    
    /** 
     * сравнение массивов
    */
    public static function eq(Array $a=[],Array $b=[],$param=[]){
        $p = array_merge([
            'compare'=>'soft'
        ],$param);
        
        if (($a===[]) && ($b===[])) 
            return true;

        if (count($a)!==count($b))
            return false;
        
        $assocA = self::is_assoc($a);
        $assocB = self::is_assoc($a);


        if ($assocA!==$assocB)
            return false;
        
        if ((!$assocA) && (!$assocB)) 
            return count(array_diff($a,$b)) === 0;
        
        foreach($a as $key=>$val){
            $typeVal = gettype($val);
            if (gettype($key) === 'string'){
                
                $eq = ( isset($b[$key]) && (  ( $typeVal==='array' && self::eq($val,$b[$key],$p) )  ||  ($p['compare'] === 'soft' && $b[$key]==$val) || ($p['compare'] !== 'soft' && $b[$key]===$val) )); 
                if (!$eq)
                    return false;
            }else{
                $find = false;
                foreach($b as $keyB=>$valB){
                    if (gettype($keyB)!=='string'){
                        if (  ( $typeVal==='array' && self::eq($val,$valB,$p) )  ||  ($p['compare'] === 'soft' && $valB==$val) || ($p['compare'] !== 'soft' && $valB===$val) ){
                            $find = true;
                            break;
                        };
                    };
                };
                if (!$find) 
                    return false;
            }        
        }
        return true;    
    }
    /** 
     * аналог jQuery extend но на выходе будут только ключи содержащиеся в $default
     * Ex: Arr::default(['ID'=>1,'NAME'=>'noneme'],['NAME'=>'mike','AGE'=>100])
     * res:  ['ID'=>1,'NAME'=>'mike']
    */
    public static function default(array $default,array $args){
        return array_intersect_key(array_merge($default,$args),$default);
    }

    /** перемещает элемент массива из позиции $from в $to */
    public static function move(array &$array, int $from,int $to) {
        $out = array_splice($array, $from, 1);
        array_splice($array, $to, 0, $out);
    }
    /** вставить элемент в позицию в массиве ( первый элемент = 0 )
    * Arr::insert([0,1,2,3],333);    // [0,1,2,3,333]
    * Arr::insert([0,1,2,3],333,0);  // [333,0,1,2,3]
    * Arr::insert([0,1,2,3],333,-10);// [333,0,1,2,3]
    * Arr::insert([0,1,2,3],333,1);  // [0,333,1,2,3]
    */
    public static function insert(array &$toArray,$data,$pos=false){
        if ($pos===false || $pos>=count($toArray)){
            $toArray[]=$data;
        }elseif ($pos<=0){
            array_unshift($toArray,$data);
        }else
            array_splice($toArray, $pos, 0, $data);
        
    }
}


?>