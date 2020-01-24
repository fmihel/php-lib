<?php
namespace fmihel\lib;

/** 
 * загрузка файла конфигурации
*/
class ConfigClass{

    public $param;
    private $loadedFiles;
    
    private $settings=[
        'fileName'=>'config.php',
        'varName'=>'config',
        
    ];
    

    function __construct(Array $settings=[]){

        $this->settings['appPath'] = Dir::slash(dirname($_SERVER['SCRIPT_FILENAME']),false,true);
        $this->settings = array_merge($this->settings,$settings);

        $this->param = array();
        $this->loadedFiles = array();
        $this->preLoad();
    }
    /** начальная загрузка файла конфигурации */
    private function preLoad(){
        
        $file = $this->settings['fileName'];
        if (file_exists($file))
            $this->loadFromFile($file);
        
    }
    /** 
     * загрузка конфига из файла ( объединяется с текущей конфигурацией) 
     * Возможно ипользовать в двух вариантах
     * 1. loadFromFile(file,[reopen]) тогда file - путь к конфигу относительно папки запускаемого скрипта
     * 2. loadFromFile(dir,file,[reopen]) тогда file - путь относительно абсолютного пути dir ( dir = __DIR__ - то относительно файла который вызывает loadFromFile)
     * 
    */
    public function loadFromFile($__DIR__,$file='',$reopen=false){
        $s = $this->settings;

        if ($file===''){

            $file = $__DIR__;

        }else{
            if (gettype($file)==='boolean'){
                $reopen = $file;
                $file = $__DIR__;
                
            }else{
                $abs =  Dir::abs_path($__DIR__,$file);
                $file = Dir::rel_path($this->appPath,$abs);                
            }    
        };    
        $abs = Dir::abs_path($s['appPath'],$file);    
        
        if (!$reopen){
            // already opened
            if (array_search($abs,$this->loadedFiles)!==false){
                return false;
            }
        }    
        

        if (file_exists($file)){
            
            $ext = pathinfo($file,PATHINFO_EXTENSION);
            if ($ext ==='php'){
                require_once $file;
                $this->param = ARR::extend($this->param,${$s['varName']});
                if (array_search($abs,$this->loadedFiles)===false)
                    $this->loadedFiles[]=$abs;
                return true;
            }
        }else{
            $this->log($file,'file not exists',__FILE__,__LINE__);
        }
        return false;
    }
    
    public function def($name,$mean){
        if (!isset($this->param[$name]))
            $this->param[$name] = $mean;
    }
    public function define($name,$mean){
        $this->def($name,$mean);
    }

    public function set($name,$mean){
        $this->param[$name] = $mean;
    }
    /** 
     * name || name ,default
    */
    public function get(...$param){
        
        $count = count($param);
        if ($count === 0)
            throw new \Exception("need set one or two params", 0);
            
        $name = $param[0];        

            
        if ( !isset($this->param[$name])) {
            if ($count > 1) 
                return  $param[1];
            else
                throw new \Exception("param [$name] is not exists", 0);
        };
        
        return $this->param[$name];
    }


    function clear(){
        $this->param = array();
    }
    
    function debug_info($cr='<br>'){
        
        $out = 'Config{'.$cr;
        $out.='->param['.count($this->param).'] ['.$cr;
        $i = 0;
        foreach($this->param as $name=>$val){
            $out.=($i++).': ['.$name.']:'.gettype($val).' = ';
            $out.=print_r($val,true).$cr;
        }    
        $out.=']'.$cr;

        $out.='->loadedFiles['.count($this->loadedFiles).'] ['.$cr;
        $i = 0;
        foreach($this->loadedFiles as $name){
            $out.=($i++).': "'.$name.'"'.$cr;
        }    
        $out.=']'.$cr;
        

        $out.='}'.$cr;
        return $out;
    }
    
    private function log($msg,$name='',$file='',$line=''){
        
        $out = '';    
        
        $out.= $file!==''?'['.$file.']':'';
        $out.= $line!==''?'['.$line.']':'';
        
        if ($name!=='')
            $out.= " ".$name.':'.$msg.'';
        else    
            $out.= ' '.$msg.'';
            
        error_log($out);
    }

}

?>