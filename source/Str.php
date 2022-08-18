<?php
namespace fmihel\lib;


define('STR_TRANSLIT_RUS',str_split(mb_convert_encoding('абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ _-','cp1251','utf-8'),1));
define('STR_TRANSLIT_ENG',str_split(mb_convert_encoding("abvgdeegziyklmnoprstufhchssuiueuyABVGDEEGZIYKLMNOPRSTUFHCHSSUIUEUY___",'cp1251','utf-8'),1));
 
    
class Str {
    /**  случайная строка длиной $count (начинается с буквы, всегда загланые буквы и цифры) */
    public static function random(int $count){
        $result = '';
        for($i = 0;$i<$count;$i++)  {
            if ($i === 0){
                $result.=chr(rand(65,90));
            }else{
                if (rand(1,10) > 6)        
                    $result.=chr(rand(48,57));
                else
                    $result.=chr(rand(65,90));
            };
        };  

        return $result;
    }
    /**  транслитирация для строки
     *   разрешены все буквы, цифры и знак "_" , пробел и "-" заменяются на "_"
    */
    public static function translit(string $s):string{
       
       $s   = str_split(mb_convert_encoding($s,'cp1251','utf-8'),1);
       $len = count($s);

       $out = '';
       for($i=0;$i<$len;$i++){
           $code = ord($s[$i]);
           if ( ($code>=97 && $code<=122) || ($code>=65 && $code<=90) || ($code>=48 && $code<=57)){
               $out.=$s[$i];
           }else{
               $pos = array_search($s[$i],STR_TRANSLIT_RUS);
               if ($pos!==false){
                   $out.=STR_TRANSLIT_ENG[$pos];
               };
           };
       };
       return $out;
   }
}
?>