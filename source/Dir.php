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

    public static function struct($path, $exts = array(), $only_dir = false, $level = 10000, $_root = '', $slash = DIRECTORY_SEPARATOR)
    {
        /*return file_struct begin from $path
        $res = array(
        array(  'name' - short name  Ex: menu
        'path' - path from begin $path Ex: ws/inter/menu/
        'is_file' - true if file
        childs = array(...) - childs dir (if is_file = false :)
        )
        )
         */
        $res = array();
        if ($_root == '') {
            $_root = self::slash($path, false, true, $slash);
        }

        //------------------------------------------------
        $ext = self::_exts($exts);
        //------------------------------------------------
        // add directory
        $dir = self::scandir($path);
        for ($i = 0; $i < count($dir); $i++) {
            $item = $dir[$i];
            if (($item !== '.') && ($item !== '..')) {
                $item_path = self::slash(self::join([$path, $item]), false, false, $slash); //self::slash($path,false,false).self::slash($item,true,false);
                if (self::is_dir($item_path)) {
                    $res[] = [
                        'name' => $item,
                        'path' => substr($item_path, strlen($_root)),
                        'is_file' => false,
                        'childs' => ($level <= 0 ? [] : self::struct($item_path . $slash, $ext, $only_dir, $level - 1, $_root, $slash))
                    ];
                };
            };
        }; //for

        // add files
        if (!$only_dir) {
            for ($i = 0; $i < count($dir); $i++) {
                $item = $dir[$i];
                if (($item !== '.') && ($item !== '..')) {
                    $item_file = self::slash(self::join([$path, $item]), false, false, $slash); // self::slash($path,false,false).self::slash($item,true,false);

                    if (self::is_file($item_file)) {
                        $_ext = strtoupper(self::ext($item));
                        if ((count($ext) == 0) || (in_array($_ext, $ext))) {
                            $res[] = [
                                'name' => $item,
                                'path' => substr($item_file, strlen($_root)),
                                'is_file' => true,
                            ];
                        }

                    }
                }
            }
        }

        return $res;
    }

    public static function files($path, $exts = '', $full_path = false, $only_root = true)
    {

        //echo 'path:  '.$path."\n";

        $struct = self::struct($path, $exts, false, 0);
        $full_path = ($only_root ? $full_path : true);

        $res = array();

        for ($i = 0; $i < count($struct); $i++) {
            $item = $struct[$i];
            if ($item['is_file']) {
                //array_push($res,($full_path?$item['path']:$item['name']));
                array_push($res, ($full_path ? $path : '') . $item['name']);
            }
        }

        $dirs = ($only_root ? array() : self::dirs($path, true));
        for ($i = 0; $i < count($dirs); $i++) {

            $next_path = $path . $dirs[$i] . '/';

            $out = self::files($next_path, $exts, true, false);
            for ($j = 0; $j < count($out); $j++) {
                array_push($res, $out[$j]);
            }

        }
        return $res;
    }

    public static function dirs($path, $full_path = false)
    {
        $struct = self::struct($path, '', true, 0);
        $res = array();
        for ($i = 0; $i < count($struct); $i++) {
            $item = $struct[$i];
            if (!$item['is_file']) {
                array_push($res, ($full_path ? $item['path'] : $item['name']));
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
        $paths = [$path];
        if (substr($path, 0, 1) !== '/') {
            $paths[] = '/' . $path;
        }
        foreach ($paths as $dir) {
            try {
                if (@is_dir($dir)) {
                    return true;
                }

                $list = @scandir($dir);
                if (gettype($list) === 'array' && count($list) > 0) {
                    return true;
                }

                $tmp = self::join([$dir, Str::random(10) . '.txt']);
                if (@file_put_contents($tmp, 'test') > 0) {
                    unlink($tmp);
                    return true;
                }

            } catch (\Exception $e) {
            }
        };
        return false;
    }

    /** аналог is_file */
    public static function is_file(string $path): bool
    {
        return !self::is_dir($path);
    }

}
