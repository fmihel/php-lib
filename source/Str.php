<?php
namespace fmihel\lib;


define('STR_TRANSLIT_RUS',str_split(mb_convert_encoding('абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ','cp1251','utf-8'),1));
define('STR_TRANSLIT_ENG',str_split(mb_convert_encoding("abvgdeegziyklmnoprstufhchsseieeuyABVGDEEGZIYKLMNOPRSTUFHCHSSEIEEUY",'cp1251','utf-8'),1));
 
    
class Str {
    /**  случайная строка длиной $count (начинается с буквы, всегда загланые буквы и цифры) */
    public static function random(int $count):string{
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
    /**  транслитирация для строки.
     *   заменятся только кирилические символы,
     *   для др символов можно задать спец ф-цию callback,
     *   которая должна вернуть значение для переданного символа
     *   Ex: trasnlit('йцрувцр839wkjd');
     *   Ex: trasnlit('path/путь\',Str::TRANSLIT_TO_URL);
    */
    public static function translit(string $s,$callback=null):string{
       
       $s   = str_split(mb_convert_encoding($s,'cp1251','utf-8'),1);
       $len = count($s);

       $out = '';
       for($i=0;$i<$len;$i++){
            $pos = array_search($s[$i],STR_TRANSLIT_RUS);
            if ($pos!==false){
                $out.=STR_TRANSLIT_ENG[$pos];
            }elseif ($callback){
                $res = call_user_func($callback,$s[$i]);
                if ($res)
                    $out.=$res;
            }else{
                $out.=$s[$i];
            }
       };
       return $out;
   }
   
    /**  транслитирация ф-ция для для строки для адреса url 
     *   кирилица заменяется на транслит, пробед и - на _ 
     *   косая черта / и точка оставляется
     *   остальное игнорируется
     *   Ex: trasnlit('path/path',Str::TRANSLIT_TO_URL);
    */
    public static function TRANSLIT_TO_URL(string $s){
        if ($s === '\\')
            return '/';

        if ($s === '_' || $s === ' ' || $s === '-')
            return '_';            

        if ( $s === '/'  || $s === '.')
            return $s;

        $code = ord($s);
        if ( ($code>=97 && $code<=122) || ($code>=65 && $code<=90) || ($code>=48 && $code<=57)){
            return $s;
        };

        return '';
    }
}
?>