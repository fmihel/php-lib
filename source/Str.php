<?php
namespace fmihel\lib;

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
}
?>