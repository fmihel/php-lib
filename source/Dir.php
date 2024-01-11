<?php

namespace fmihel\lib;

require_once __DIR__ . '/Str.php';

class Dir
{

    public static function pathinfo($file)
    {
        $out = array('file' => $file, 'dirname' => '', 'basename' => '', 'extension' => '', 'filename' => '');
        $slash = '/';
        //------------------------------------------------
        $have_oslash = (mb_strpos($file, '\\') !== false);
        if ($have_oslash) {
            $file = str_replace('\\', $slash, $file);
        }

        //------------------------------------------------

        $lim = mb_strrpos($file, $slash);
        if ($lim !== false) {
            $left = mb_substr($file, 0, $lim);
            $right = mb_substr($file, $lim + 1);

            $out['dirname'] = $left;
            $out['basename'] = $right;
            $out['filename'] = $right;

            $pos_ext = mb_strrpos($right, '.');
            if ($pos_ext !== false) {
                $out['extension'] = mb_substr($right, $pos_ext + 1);
                $out['filename'] = mb_substr($right, 0, $pos_ext);
            }

        } else {
            $out['basename'] = $file;
            $out['filename'] = $file;

            $pos_ext = mb_strrpos($file, '.');
            if ($pos_ext !== false) {
                $out['extension'] = mb_substr($file, $pos_ext + 1);
                $out['filename'] = mb_substr($file, 0, $pos_ext);
            };

        }

        //------------------------------------------------
        if ($have_oslash) {
            foreach ($out as $k => $v) {
                $out[$k] = str_replace($slash, '\\', $v);
            }
        }

        //------------------------------------------------

        return $out;
    }

    public static function files(string $path, array $exts = [], bool $full_path = false, bool $only_root = true): array
    {

        $res = [];
        $struct = self::scandir($path);
        foreach ($struct as $item) {
            if ($item !== '.' && $item !== '..') {
                $name = self::join($path, $item);
                if (self::is_file($name)) {
                    $res[] = $full_path ? $name : $item;
                } elseif (!$only_root) {
                    $res = array_merge($res, self::files($name, $exts, $full_path, false));
                }
            }
        }
        return $res;
    }

    public static function dirs(string $path, bool $full_path = false, bool $only_root = true): array
    {
        $res = [];
        $struct = self::scandir($path);
        foreach ($struct as $item) {
            if ($item !== '.' && $item !== '..') {
                $name = self::join($path, $item);
                if (self::is_dir($name)) {
                    $res[] = $full_path ? $name : $item;
                    if (!$only_root) {
                        $res = array_merge($res, self::dirs($name, $full_path, false));
                    }}
            }
        }
        return $res;
    }
    /**
     * clear folder
     * $path is relation path to clear path ( delete all inside in $path,widthout $path)
     * example
     * you app place in:   home/ubuntu/www/app/test01/index.php
     * need clear folder:  home/ubuntu/www/aaa/bbb/
     * use next:
     * $path =  APP::slash(APP::rel_path($Application->PATH,$Application->ROOT.'aaa/bbb/'));
     * self::clear($path)
     *
     */
    public static function clear($path)
    {

        $files = self::files($path, '', false);
        $dirs = self::dirs($path, false);

        for ($i = 0; $i < count($files); $i++) {
            unlink($path . $files[$i]);
        }

        for ($i = 0; $i < count($dirs); $i++) {
            $dir = self::join([$path, $dirs[$i]]);
            self::clear(self::slash($dir, false, true));
            rmdir($dir);
        };
    }
    /** удаляет папку вместе  с ее содержимым */
    public static function delete($path)
    {
        if (!is_dir($path)) {
            throw new \Exception("$path must be a directory");
        }
        if (substr($path, strlen($path) - 1, 1) != '/') {
            $path .= '/';
        }
        $files = glob($path . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::delete($file);
            } else {
                unlink($file);
            }
        }
        rmdir($path);
    }
    public static function info($dir)
    {
        $exist = file_exists($dir);

        if ($exist) {
            $is_dir = self::is_dir($dir);
            $is_file = !$is_dir;
        } else {
            $is_dir = false;
            $is_file = false;
        };

        return array('exist' => $exist, 'is_dir' => $is_dir, 'is_file' => $is_file);
    }
    /**
     * проверка существовния папки
     */
    public static function exist($dir)
    {
        return (self::is_dir($dir));
    }

    /**
     * копирует папку
     */
    public static function copy($src, $dst, $stopOnError = false)
    {
        $res = true;

        if (!self::exist($src)) {
            return false;
        }

        $dir = opendir($src);

        if ($dir !== false) {
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {

                    if (self::is_dir($src . '/' . $file)) {
                        if (!self::copy($src . '/' . $file, $dst . '/' . $file, $stopOnError)) {
                            $res = false;
                        }

                    } else {
                        if (!copy($src . '/' . $file, $dst . '/' . $file)) {
                            $res = false;
                        }

                    }

                    if ((!$res) && ($stopOnError)) {
                        break;
                    }

                }
            }
            closedir($dir);

        } else {
            return false;
        }

        return $res;
    }

    /** соединяет папки
     */
    public static function join(...$paths): string
    {
        $out = '';
        $is_win = false;
        $list = [];

        $noize = rand(10000, 99999);
        $rootKey = '<%DOS-' . $noize . '%>';
        $httpKey = '<%HTTP-' . $noize . '%>';

        foreach ($paths as $path) {

            // replace http://
            if (strpos($path, '://') !== false) {
                $path = str_replace('://', $httpKey, $path);
            };

            // replace c:\
            if (strpos($path, ':\\') !== false) {
                $is_win = true;
                $path = str_replace(':\\', $rootKey, $path);
            };

            $list[] = str_replace('\\', '/', $path);
        };

        $out = implode('/', $list);
        $out = Str::replace_recursive('//', '/', $out);

        if ($is_win) {
            $out = str_replace($rootKey, ':\\', $out);
            $out = str_replace('/', '\\', $out);
            $out = str_replace('\\\\', '\\', $out);
        } else {
            $out = str_replace($httpKey, '://', $out);
            $out = Str::replace_recursive('///', '//', $out);
        }
        return $out;
    }
    /** аналог is_dir однако решает проблему в
     * https://www.php.net/manual/ru/function.is-dir.php
     * см Note that on Linux is_dir returns FALSE if a parent directory does not have +x (executable) set for the php process.
     */
    public static function is_dir(string $path): bool
    {
        if (@is_dir($path)) {
            return true;
        }

        $list = @scandir($path);
        if (gettype($list) === 'array' && count($list) > 0) {
            return true;
        }

        $is_dir = false;
        $tmp = self::join($path, '_tmp-' . rand(10000, 99999) . '.tmp');
        try {
            $is_dir = (@file_put_contents($tmp, 'test') > 0);
        } catch (\Exception $e) {};

        if (file_exists($tmp)) {
            unlink($tmp);
        }

        return $is_dir;

    }

    /** аналог is_file */
    public static function is_file(string $path): bool
    {
        return !self::is_dir($path);
    }
    private static function scandir(string $path): array
    {
        $list = @scandir($path);
        return gettype($list) === 'array' ? $list : [];
    }
    public static function ext(string $file): string
    {
        if (strpos($file, '.') === false) {
            return '';
        };
        $val = strrev($file);
        $dos = strpos($val, '/');
        $unix = strpos($val, '\\');
        $dot = strpos($val, '.');
        if ($dos !== false && $dot > $dos) {
            return '';
        }

        if ($unix !== false && $dot > $unix) {
            return '';
        }
        return strrev(substr($val, 0, $dot));

    }
}
