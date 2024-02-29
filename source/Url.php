<?php
namespace fmihel\lib;

class Url
{
    /** соединяет куски маршрутов */
    public static function join(...$paths): string
    {
        $dos = '\\';
        $unix = '/';

        $toUnix = true;

        //echo $toUnix ?'unix':'dos';
        //echo '<br>';

        // первое вхождение \ или / определяет тип пути dos или unix
        for ($i = 0; $i < count($paths); $i++) {

            $dosPos = strpos($paths[$i], $dos);
            $unixPos = strpos($paths[$i], $unix);
            if (($dosPos !== false && $unixPos === false) || ($dosPos !== false && $unixPos !== false && $dosPos < $unixPos)) {
                $toUnix = false;
                break;
            }
        }

        $out = '';
        foreach ($paths as $path) {

            if ($toUnix) {

                $path = self::trims(str_replace($dos, $unix, $path), $unix, $out, !self::isprot(trim($path)));
                $out = $out . ($out && !self::isprot($out) ? $unix : '') . $path;

            } else {

                $path = self::trims(str_replace($unix, $dos, $path), $dos, $out, !self::isprot(trim($path), false));
                $out = $out . ($out && !self::isprot($out, false) ? $dos : '') . $path;

            }
        }

        if ($toUnix) {
            if (!self::isprot($out)) {
                $out = self::trims(str_replace($dos, $unix, $out), $unix, false);
            }
        } else {
            if (!self::isprot($out, false)) {
                $out = self::trims(str_replace($unix, $dos, $out), $dos, false);
            }
        }

        return $out;

    }

    public static function build(string $url, array $attr = []): string
    {
        $url = trim($url);

        $amp = strpos($url, '?');
        $have_param = ($amp !== false && strlen($url) > ($amp + 1));

        // $attrs = [];
        // foreach ($attr as $name => $val) {
        //     $attrs[] = $name . '=' . $val;
        // };
        $sattr = http_build_query($attr);

        if (empty($sattr)) {
            $sattr = '';
        } else {
            $sattr = ($have_param ? '&' : ($amp ? '' : '?')) . $sattr;
        }

        return $url . $sattr;
    }

    private static function trims(string $str, $slash = '/', $left = true, $right = true): string
    {

        $str = trim($str);

        if ($left && strpos($str, $slash) === 0) {
            $str = substr($str, 1);
        }

        if ($right && substr($str, strlen($str) - 1) === $slash) {
            $str = substr($str, 0, strlen($str) - 1);
        }

        return $str;
    }
    private static function isprot($str, $unix = true)
    {

        return (strrpos($str, ($unix ? '://' : ':\\')) === strlen($str) - ($unix ? 3 : 2));
    }

}
