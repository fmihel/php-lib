<?php
namespace fmihel\lib;

use Error;

define('_SEPARATOR_DIRECTORY','/');

class Dir{
    /**
     * добавление/удаление замыкающих слешей
     */
    public static function slash($dir,$left=true,$right=true){
        if (trim($dir)=='') return '';
        /*учитываем вариант когда точка находится в имени корневой папки*/
        $root =  substr($_SERVER['DOCUMENT_ROOT'],strrpos($_SERVER['DOCUMENT_ROOT'],'/')+1);

        $dirs = explode(_SEPARATOR_DIRECTORY,trim($dir));
        $out = '';
        
        $is_dos = false;

        if (count($dirs)>0){
					$s = $dirs[0];
					$is_dos = (substr($s,strlen($s)-1)==':');
				}	
        
        for($i=0;$i<count($dirs);$i++)
            $out.=(strlen($dirs[$i])>0?(strlen($out)>0?_SEPARATOR_DIRECTORY:'').$dirs[$i]:'');
        
        $last=$dirs[count($dirs)-1];
        return ((($left)&&(!$is_dos))?_SEPARATOR_DIRECTORY:'').$out.(($right)&&(($last==$root)||(!strpos($last,'.')))?_SEPARATOR_DIRECTORY:'');
    }
    
    public static function pathinfo($file){
        $out = array('file'=>$file,'dirname'=>'','basename'=>'','extension'=>'','filename'=>'');
        $slash = '/';
        //------------------------------------------------
        $have_oslash = (mb_strpos($file,'\\')!==false);
        if ($have_oslash)
            $file = str_replace('\\',$slash,$file);
        //------------------------------------------------

        $lim = mb_strrpos($file,$slash);
        if ($lim!==false){
            $left = mb_substr($file,0,$lim);
            $right= mb_substr($file,$lim+1);
            
            $out['dirname'] = $left;
            $out['basename'] = $right;
            $out['filename'] = $right;
            
            $pos_ext = mb_strrpos($right,'.');
            if ($pos_ext!==false){
                $out['extension'] = mb_substr($right,$pos_ext+1);
                $out['filename'] = mb_substr($right,0,$pos_ext);
            }
            
        }else{
            $out['basename'] = $file;
            $out['filename'] = $file;
            
            $pos_ext = mb_strrpos($file,'.');
            if ($pos_ext!==false){
                $out['extension'] = mb_substr($file,$pos_ext+1);
                $out['filename'] = mb_substr($file,0,$pos_ext);
            };
            
        }

        //------------------------------------------------
        if ($have_oslash)
            foreach($out as $k=>$v)
                $out[$k] = str_replace($slash,'\\',$v);
        //------------------------------------------------
        
        return $out;
    }

    public static function ext($file){
        //S: получите расширение файла
        $path = self::pathinfo($file);
        return $path['extension'];
    }
    
    private static function _exts($exts){
        //------------------------------------------------
        if (!is_array($exts)){
            $_ext=explode(',',$exts);
            $ext=array();
            for($i=0;$i<count($_ext);$i++){
                if(trim($_ext[$i])!=='')
                    array_push($ext,trim($_ext[$i]));
            };
        }else
            $ext=$exts;
        //------------------------------------------------
        //upper ext    
        for($i=0;$i<count($ext);$i++)
            $ext[$i] = strtoupper($ext[$i]);
        return $ext;
    }
    
    public static function struct($path,$exts=array(),$only_dir=false,$level=10000,$_root=''){
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
        if ($_root=='') $_root=self::slash($path,false,true);
        //------------------------------------------------
        $ext = self::_exts($exts);    
        //------------------------------------------------
        // add directory
        $dir = self::scandir($path);
        for($i=0;$i<count($dir);$i++){
            $item = $dir[$i];
            if (($item!=='.')&&($item!=='..')){
                $item_path = self::slash(self::join([$path,$item]).false,false);  //self::slash($path,false,false).self::slash($item,true,false);
                if (self::is_dir($item_path)){
                    array_push($res,array(
                        'name'=>$item,
                        //'path'=>APP::abs_path($_root,$item_path),
                        'path'=>substr($item_path,strlen($_root)),
                        'is_file'=>false,
                        'childs'=>($level<=0?array():self::struct($item_path.'/',$ext,$only_dir,$level-1,$_root))));
                }
            }
        }
    
        // add files
        if (!$only_dir)
        for($i=0;$i<count($dir);$i++){
            $item = $dir[$i];
            if (($item!=='.')&&($item!=='..')){
                $item_file = self::slash(self::join([$path,$item]),false,false);// self::slash($path,false,false).self::slash($item,true,false);
            
                if (self::is_file($item_file)){
                    $_ext = strtoupper(self::ext($item));
                    if ((count($ext)==0)||(in_array($_ext,$ext)))
                    array_push($res,array(
                        'name'=>$item,
                        //'path'=>APP::abs_path($_root,$item_file),
                        'path'=>substr($item_file,strlen($_root)),
                        'is_file'=>true));
                }
            }
        }

        return $res;
    }
    
    private static function _lstruct($struct,&$to){
        
        for($i=0;$i<count($struct);$i++){
            $el=$struct[$i];
            if ($el['is_file']){
                $add = array();
                foreach($el as $k=>$v){
                    if ($k!=='childs')
                        $add[$k]=$v;
                }
                array_push($to,$add);
            }
        }
        for($i=0;$i<count($struct);$i++){
            $el=$struct[$i];
            if (!$el['is_file']){
                //$add = array();
                //foreach($el as $k=>$v){
                //    if ($k!=='childs')
                //        $add[$k]=$v;
            //    }
            //    array_push($to,$add);
                self::_lstruct($el['childs'],$to);
            }
        }
        
    }    
    
    private static function lstruct($path,$exts=array()){
        $struct = self::struct($path,$exts);
        $out = array();
        for($i=0;$i<count($struct);$i++){
            $el=$struct[$i];
            if ($el['is_file']){
                $add = array();
                foreach($el as $k=>$v){
                    if ($k!=='childs')
                        $add[$k]=$v;
                }
                array_push($out,$add);
            }
        }
        
        for($i=0;$i<count($struct);$i++){
            $el=$struct[$i];
            if (!$el['is_file']){
                //$add = array();
                //foreach($el as $k=>$v){
                //    if ($k!=='childs')
                //        $add[$k]=$v;
                //}
                //array_push($out,$add);
                self::_lstruct($el['childs'],$out);
            }
        }
        return $out;
    }    
    
    public static function files($path,$exts='',$full_path=false,$only_root=true){
        
        //echo 'path:  '.$path."\n";        
        
        $struct     =   self::struct($path,$exts,false,0);
        $full_path  =   ($only_root?$full_path:true);


        $res        =   array();
        
        for($i=0;$i<count($struct);$i++){
            $item = $struct[$i];
            if ($item['is_file']){
                //array_push($res,($full_path?$item['path']:$item['name']));
                array_push($res,($full_path?$path:'').$item['name']);    
            }    
        }
        
        $dirs       =   ($only_root?array():self::dirs($path,true));
        for($i=0;$i<count($dirs);$i++){
            
            $next_path = $path.$dirs[$i].'/';

            $out = self::files($next_path,$exts,true,false);
            for($j=0;$j<count($out);$j++)
                array_push($res,$out[$j]);    
        }
        return $res;
    }
    
    public static function dirs($path,$full_path=false){
        $struct = self::struct($path,'',true,0);
        $res = array();
        for($i=0;$i<count($struct);$i++){
            $item = $struct[$i];
            if (!$item['is_file'])
                array_push($res,($full_path?$item['path']:$item['name']));    
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
    public static function clear($path){
        
        $files = self::files($path,'',false);
        $dirs  = self::dirs($path,false);
        
        for($i=0;$i<count($files);$i++)
            unlink($path.$files[$i]);

        for($i=0;$i<count($dirs);$i++){
            $dir = self::join([$path,$dirs[$i]]);
            self::clear(self::slash($dir,false,true));
            rmdir($dir);
        };    
    }
    /** удаляет папку вместе  с ее содержимым */
    public static function delete($path) {
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
    public static function info($dir){
        $exist = file_exists($dir);
        
        if ($exist){
            $is_dir = self::is_dir($dir);
            $is_file = !$is_dir;
        }else{
            $is_dir = false;
            $is_file = false;
        };    
        
        return array('exist'=>$exist,'is_dir'=>$is_dir,'is_file'=>$is_file);
    }
     /**
     * проверка существовния папки
     */ 
    public static function exist($dir){
        return (file_exists($dir) && self::is_dir($dir));
    }
    
    /**
     * копирует папку
    */
    public static function copy($src,$dst,$stopOnError = false) { 
        $res = true;
        
        if (!self::exist($src)) return false;
        
        $dir = opendir($src);
        
        if ($dir!==false){
            @mkdir($dst); 
            while(false !== ( $file = readdir($dir)) ) { 
                if (( $file != '.' ) && ( $file != '..' )) { 
                    
                    if ( self::is_dir($src . '/' . $file) ){ 
                        if (!self::copy($src . '/' . $file,$dst . '/' . $file,$stopOnError))
                            $res = false;
                    }else{ 
                        if (!copy($src . '/' . $file,$dst . '/' . $file))
                            $res = false;
                    }
                    
                    if ((!$res)&&($stopOnError))
                        break;
                } 
            } 
            closedir($dir); 
            
        }else
            return false;
            
        return $res;    
    }
    /** 
     * Получение абсолютного пути из from в to
    */
    public static function abs_path($from,$to = ''){
        if ($to !== '')
        {
            $to     = self::slash($to,true,false);
            $from   = self::slash($from,false,false).self::slash($to,true,false);
        };
            
        $path = $from;
        $path = str_replace(array('/', '\\'), _SEPARATOR_DIRECTORY, $path);
        $parts = array_filter(explode(_SEPARATOR_DIRECTORY, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) 
        {
            if ('.' == $part) 
                continue;
            if ('..' == $part) 
            {
                array_pop($absolutes);
            }else{
                $absolutes[] = $part;
            };
        };
        return self::slash(implode(_SEPARATOR_DIRECTORY, $absolutes),false,true);
    }
    /**
     * Получение относительного пути
     * from = '/home/decoinf3/public_html/test/myproject/';
     * to = '/home/decoinf3/public_html/rest/a
     * result ../../rest/a
     */
    static function rel_path($from,$to){
        
        $ps = _SEPARATOR_DIRECTORY;
        $arFrom = explode($ps, rtrim($from, $ps));
        $arTo = explode($ps, rtrim($to, $ps));
        while(count($arFrom) && count($arTo) && ($arFrom[0] == $arTo[0]))
        {
            array_shift($arFrom);
            array_shift($arTo);
        }
        return str_pad("", count($arFrom) * 3, '..'.$ps).implode($ps, $arTo);
    }

    static function pathAsDos($path){
        return str_replace("/",'\\',$path);
    }

    static function pathAsUnix($path){
        return str_replace('\\',"/",$path);
    }

    /** соединяет папки 
     * @param {array} - массив папок
     * @param {string} - варианты соединения asis | auto | unix | dos (unix = /, dos = \)
     * 
     * 
    */
    static function join(array $paths,$as='auto'){
        $as = strtolower($as);
        if ($as === '/') $as = 'unix';
        if ($as === '\\') $as = 'dos';

        $out = '';
        $count = count($paths);
        $dos = false;

        for($i=0;$i<$count;$i++){
            $path = trim($paths[$i]);
            $len = mb_strlen($path)-1;
            $left = strpos($path,'/') === 0  || strpos($path,'\\') === 0;
            $right = strrpos($path,'/') === $len  || strrpos($path,'\\') === $len;

            if ($dos === false && strpos($path,'\\')!==false)
                $dos = true;
            
            if ($i>0 && $left)
                $path = substr($path,1);
            if ($i<$count-1 && !$right)
                $path .= '/';
            
            $out.=$path;
        }


        if ($as=='auto' && (strpos($out,'http://')===0 || strpos($out,'https://')===0) ){
            $as = 'unix';
        }

        if ($as === 'unix' || ($as === 'auto' && !$dos)){
            return self::pathAsUnix($out);
        }elseif ($as === 'dos' || ($as === 'auto' && $dos) ){
            return self::pathAsDos($out);
        }
        return $out;
    }
    /** аналог is_dir однако решает проблему в
     * https://www.php.net/manual/ru/function.is-dir.php
     * см Note that on Linux is_dir returns FALSE if a parent directory does not have +x (executable) set for the php process.
     */
    static function is_dir(string $path):bool{
        $paths = [$path];
        if (substr($path,0,1) !== '/') {
            $paths[] = '/'.$path;
        }
        foreach($paths as $dir){
            try{
                if (@is_dir($dir))
                    return true;
                
                $list = @scandir($dir);
                if (gettype($list) === 'array' && count($list)>0)
                    return true;
        
                $tmp = self::join([$dir,Str::random(10).'.txt']);
                if (@file_put_contents($tmp,'test')>0 ){
                    unlink($tmp);
                    return true;
                }
        
            }catch(\Exception $e){
            }
        };
        return false;
    }
    /** аналог is_file 
     */
    static function is_file(string $path):bool{
        try{
            return !self::is_dir($path);
        }catch(\Exception $e){

        }
        return false;    
    }
    
    static function scandir(string $path):array{
        
        $paths = [$path];
        if (substr($path,0,1) !== '/') {
            $paths[] = '/'.$path;
        };
        foreach($paths as $dir){
            $list = @scandir($dir);
            if (gettype($list) === 'array')
                return $list;
        };
        return [];
    }
    
};//class DIRS

?>