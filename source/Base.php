<?php
namespace fmihel\lib;

use fmihel\console;

class _db{
    public $db;
    public $transaction;
    public $charset;
    public $errors=array();
    public $error="";
    /*
    public $server;
    public $user;
    public $pass;
    public $name;
    */
    function __construct(){
        $this->db=null;
        $this->transaction  = 0;
        $this->charset = '';
        /*
        $this->server       = '';
        $this->user         = '';
        $this->pass         = '';
        $this->base_name    = '';
        */
    }
};


class Base{

    static private $_base = array();
    static private $_codings = array();
    static private $_types = array();
    /** 
     * @param string | array $server server name or ['server'=>...,'user'=>...,...]
     * @param string $user -   user name
     * @param string $pass -  password
     * @param string $baseName - base name
     * @param string $alias - alias for base
     * @param bool $die - if true then exit from script if connect error
     * @return bool
    */
    public static function connect($server,$user='',$pass='',$baseName='',$alias='',$die = true){

        if (gettype($server)==='array'){
            
            $p = array_merge([
                'server'    =>'',
                'user'      =>'',
                'pass'      =>'',
                'base'      =>'',
                'alias'     =>'',
                'die'       =>true
            ],$server);

            $server     = $p['server'];
            $user       = $p['user'];
            $pass       = $p['pass'];
            $baseName   = $p['baseName'];
            $alias      = $p['alias'];
            $die        = $p['die'];
            
        };
        
        if ( ($server!=='') && ($user==='') && ($pass==='') && ($baseName === '')  && ($alias === '') ) {
            return isset(self::$_base[$server])?true:false;
        }
        

        if (isset(self::$_base[$alias])){
            return true;
        }
        
        $db = new \mysqli($server,$user,$pass,$baseName);

        if ($db->connect_errno){
            $msg = "can`t connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
            error_log($msg);
            if ($die){
                echo $msg;
                exit;
            }

            return false;
        }
        
        $_db = new _db();
        $_db->db = $db;
        
        self::$_base[$alias]=$_db;
        
        return true;
        
    }
    
    public static function disconnect($base){

        if(isset(self::$_base[$base])){
            
            unset($base);
        }   
        
    }
    /** 
     * set or return charset
     * 
     * example set default charset
     * base::charSet('mybase','UTF-8');
     * 
     * example return default charset
     * $v = base::charSet('mybase');
     *
     * example: story/restory codings
     * base::charSet('mybase','story');
     * base::charSet('mybase','UTF-8');
     * ...
     * base::charSet('mybase','restory');
     * 
    */
    public static function charSet($base=null,$coding=null){
            
            if ( (gettype($coding) === 'string')){
                if ($coding === '')
                    $coding = null;
            }else
                $coding = null;

            // убираем путаницу с UTF-8 и utf8 
            if (!is_null($coding)){
                $coding = strtolower($coding);
                if ($coding==='utf-8')
                    $coding = 'utf8';
            }    
            
            $_base = self::getbase($base);
                
            if (is_null($coding)){
                return $_base->charset;
            }else{
                
                //if ($coding === '') 
                //    self::doThrow(__METHOD__,$base,'coding must not be empty ');
                    
                if ($coding === 'story'){
                    
                    if (!isset(self::$_codings[$base]))
                        self::$_codings[$base]=[]; 
                    
                    self::$_codings[$base][]=$_base->db->get_charset()->charset;

                }else if ($coding === 'restory'){
                    $_base->db->set_charset(array_pop(self::$_codings[$base]));
                }else if (!$_base->db->set_charset($coding)) {
                    self::doThrow(__METHOD__,$base,'error set charSet = '.$coding);
                }else
                    $_base->charset = $coding;
            }    
    }
    /**  
     * return base or raise Exception 
     * */
    private static function getbase($base,$exception = true){
        
        if (count(self::$_base)===0) {
            if ($exception)
                throw new \Exception(__CLASS__.__METHOD__.": no have initializing base", 0);
            return false;
        }   
        
        $keys = array_keys(self::$_base);
        if (is_null($base)) 
            $base = $keys[0]; 

        if (isset(self::$_base[$base]))
            return self::$_base[$base];
        
        if ($exception)
            throw new \Exception(__CLASS__.__METHOD__.": base $base is not exists", 0);

        return false;
        
    }
    
    private static function db($base){
        return self::getbase($base)->db;
    }

    public static function error($base){
        $_base = self::getbase($base);
        return $_base->db->error;
    }

    public static function query($sql,$base,$coding=null){
        $db = self::db($base);
        
        if (!is_null($coding)){
            $story  =   self::charSet($base);
            self::charSet($base,$coding);
        }    

        $res =  $db->query($sql);
        if ($res === false)
            self::doThrow(__METHOD__,$base,$sql);
            
        
        if (!is_null($coding))
            self::charSet($base,$story);
        
            
        return $res;

    }
    
    private static function doThrow($method,$base,...$msgs){
        $msg = '';

        foreach($msgs as $m){
            if ($msg !== '') $msg.="\n";
            if (gettype($m) === 'string')
                $msg.=$m;
            else
                $msg.=print_r($m,true);
        }

        
        if (self::getbase($base,false)!==false) {
            $error = self::error($base);
            if ($error)
                $msg = $error."\n".$msg;
        }
        
        throw new \Exception($method.' '.$msg);
    }

    /**
     * @return  false - если ошибка
     * @return object - если запрос выполнен
     */
    public static function ds($sql,$base,$coding=null){

        $ds = self::query($sql,$base,$coding);
        $ds->data_seek(0);
        return $ds;
    }
    /**
     * 
     */
    public static function assign($ds){
        return (gettype($ds)==='object');    
    }
    public static function isEmpty($ds){
        return ( (!self::assign($ds)) || ($ds->num_rows===0) );
    }
    /**
     * Возвращает кол-во записей запроса sql или в ds
     * если задать countFieldName, то будет искать соответсвтвующее поле и выдаст его значение
     * можно задать countFieldName как число, тогда это будет номер необходимого поля
     */
    public static function count($sqlOrDs,$base = null,$countFieldName=''){
        
        $ds = gettype($sqlOrDs)==='string'?self::ds($sqlOrDs,$base,null):$sqlOrDs;
        if (!$ds)
            self::doThrow(__METHOD__,$base,'can`t get ds from sqlOrDs =  ',$sqlOrDs);
        $type = gettype($countFieldName);

        if (($countFieldName!='')||($type==='integer')){
            $fields = self::fields($ds);

            if ($type === 'integer'){
                $row = self::row($ds);
                return intval($row[$fields[$countFieldName]]);
            }else{
                $fields = self::fields($ds);
                $countFieldName = strtoupper(trim($countFieldName));
                foreach($fields as $name){
                    if (
                        (strtoupper(trim($name)) === $countFieldName) 
                        || 
                        (strpos(strtoupper(trim($name)),$countFieldName.'(')===0)
                    ){
                        $row = self::row($ds);
                        return intval($row[$name]);
                    }
                }
            }   
        }
        
        return $ds->num_rows;        
    }

    /** список таблиц */
    public static function tables($base){
        $res = [];
        
        $q = 'SHOW TABLES';
        $ds = self::ds($q,$base);
        while($row = self::read($ds)){
            foreach($row as $field => $table)
                array_push($res,$table);    
        };
            
        return $res;    

    }
    public static function haveField($field,$tableName,$base=null){
        $list = self::fieldsInfo($tableName,$base,true);
        return (array_search($field,$list)!==false);
    }    
    /** 
     * сокращенное имя типа возвращаемое по SHOW COLUMNS FROM...
    */
    private static function shorType($type){
        $match = [
            'int'       =>'int',
            'int('      =>'int',
            'varchar'   =>'string',
            'text'      =>'string',
            'mediumtext'=>'string',
            'longtext'  =>'string',
            'text'      =>'string',
            'float'     =>'float',
            'decimal'   =>'float',
            'datetime'  =>'date',
            'timestamp' =>'date',
            'blob'      =>'blob',
            'longblob'  =>'blob',
            'mediumblob'=>'blob',
            ];
        foreach($match as $m=>$ret){
            if (strpos($type,$m)===0)
                return $ret;
        }
        return 'uncknown';
    }
    /** возвращает либо список имен полей
     *  short = true | 'short'  - список полей
     *  short = 'types' список [поле=>тип,...]
     *  short = false|'full' полную информацию [ [Fiel=>'name',Type=>'string',...], ..] ]
    */
    public static function fieldsInfo($tableName,$base,$short=true){
        $out = [];
        
        $q = 'SHOW COLUMNS FROM `'.$tableName.'`';
        $ds = self::ds($q,$base);
        
        while($row = self::read($ds)){
            
            if (($short===true)||($short==='short')){
                $out[] = $row['Field'];
            }elseif($short === 'types'){
                $out[$row['Field']] = self::shorType($row['Type']);
            }else{
                $out[]=$row;
            }
            
        }
            
        return $out;    
        
    }

    /** возвращает массив с типами для каждого поля таблицы $table базы $base, для использования в generate или update 
     *  данные кешируются, поэтому при изменении структуры таблицы необходимо вызвать getTypes c $refresh =  true
    */
    public static function getTypes(string $table,string $base,bool $refresh = false){
        
        if (!isset(self::$_types[$base])){
            self::$_types[$base] = [];
        }

        if ( $refresh || !isset(self::$_types[$base][$table]) ){
            self::$_types[$base][$table] = self::fieldsInfo($table,$base,'types');
        }

        return self::$_types[$base][$table];
    }
    /** 
     * возвращает информацию о полях результата запроса
     * @param object $ds - ссылка на результат запроса
     * @param bool | array $short_info true-короткая информация, false полная информация ,[field1,field2,..] - только выборочные поля из запроса
     * @return array | object
    */
    public static function fields($ds,$short_info=true){
        $ff = $ds->fetch_fields();
        if (gettype($short_info) === 'array'){
            $out = [];
            for($i=0;$i<count($ff);$i++){
                $item = (array)$ff[$i];
                $dat = [];
                foreach($short_info as $info){
                    if (isset($item[$info]))
                        $dat[$info] = $item[$info]; 
                }
                $out[] = $dat;
            }
            return $out;
        }elseif ($short_info){
            $out = [];
            for($i=0;$i<count($ff);$i++)
                $out[] = $ff[$i]->name;
            
            return $out;
            
        }else{
            
            for($i=0;$i<count($ff);$i++)
                $ff[$i]->stype = self::fieldTypeToStr($ff[$i]->type);
                
            return $ff;
        }    
            
    }
    /* from:
        http://php.net/manual/ru/mysqli-result.fetch-fields.php
    */
    public static function map_field_type_to_bind_type($field_type){
        switch ($field_type)
        {
        case MYSQLI_TYPE_DECIMAL:
        case MYSQLI_TYPE_NEWDECIMAL:
        case MYSQLI_TYPE_FLOAT:
        case MYSQLI_TYPE_DOUBLE:
            return 'd';
    
        case MYSQLI_TYPE_BIT:
        case MYSQLI_TYPE_TINY:
        case MYSQLI_TYPE_SHORT:
        case MYSQLI_TYPE_LONG:
        case MYSQLI_TYPE_LONGLONG:
        case MYSQLI_TYPE_INT24:
        case MYSQLI_TYPE_YEAR:
        case MYSQLI_TYPE_ENUM:
            return 'i';
    
        case MYSQLI_TYPE_TIMESTAMP:
        case MYSQLI_TYPE_DATE:
        case MYSQLI_TYPE_TIME:
        case MYSQLI_TYPE_DATETIME:
        case MYSQLI_TYPE_NEWDATE:
        case MYSQLI_TYPE_INTERVAL:
        case MYSQLI_TYPE_SET:
        case MYSQLI_TYPE_VAR_STRING:
        case MYSQLI_TYPE_STRING:
        case MYSQLI_TYPE_CHAR:
        case MYSQLI_TYPE_GEOMETRY:
            return 's';
    
        case MYSQLI_TYPE_TINY_BLOB:
        case MYSQLI_TYPE_MEDIUM_BLOB:
        case MYSQLI_TYPE_LONG_BLOB:
        case MYSQLI_TYPE_BLOB:
            return 'b';
    
        default:
            trigger_error("unknown type: $field_type");
            return 's';
        };
    }
    
    public static function fieldTypeToStr($field_type){
        
        switch ($field_type){
        
            case MYSQLI_TYPE_DECIMAL:
            case MYSQLI_TYPE_NEWDECIMAL:
            case MYSQLI_TYPE_FLOAT:
            case MYSQLI_TYPE_DOUBLE:
                return 'float';
    
            case MYSQLI_TYPE_BIT:
            case MYSQLI_TYPE_TINY:
            case MYSQLI_TYPE_SHORT:
            case MYSQLI_TYPE_LONG:
            case MYSQLI_TYPE_LONGLONG:
            case MYSQLI_TYPE_INT24:
            case MYSQLI_TYPE_YEAR:
            case MYSQLI_TYPE_ENUM:
                return 'int';
        
            case MYSQLI_TYPE_TIMESTAMP:
            case MYSQLI_TYPE_DATE:
            case MYSQLI_TYPE_TIME:
            case MYSQLI_TYPE_DATETIME:
            case MYSQLI_TYPE_NEWDATE:
            case MYSQLI_TYPE_INTERVAL:
                return 'date';
                
            case MYSQLI_TYPE_SET:
            case MYSQLI_TYPE_VAR_STRING:
            case MYSQLI_TYPE_STRING:
            case MYSQLI_TYPE_CHAR:
            case MYSQLI_TYPE_GEOMETRY:
                return 'string';
        
            case MYSQLI_TYPE_TINY_BLOB:
            case MYSQLI_TYPE_MEDIUM_BLOB:
            case MYSQLI_TYPE_LONG_BLOB:
            case MYSQLI_TYPE_BLOB:
                return 'blob';
                
            default:
                return 'uncknown';
        };
    }
    
    /**
     * перемещает указатель на первую запись
     */
    public static function first($ds){
        $ds->data_seek(0);
    }
    /** 
     * возвращает текущую строку
     * если строки закончились или их нет, то возвращает NULL
    */
    public static function row($sqlOrDs,$base=null,$coding=null){
        
        $ds = gettype($sqlOrDs)==='string'?self::ds($sqlOrDs,$base,$coding):$sqlOrDs;
        
        if (self::isEmpty($ds))
            return NULL;
        
        return $ds->fetch_assoc();
    }
    
    public static function rows($sqlOrDs,$base=null,$coding=null){
        $out = [];
        $ds = gettype($sqlOrDs)==='string'?self::ds($sqlOrDs,$base,$coding):$sqlOrDs;
        while($row = self::read($ds))
            array_push($out,$row);
        return $out;
    }
    
    /**
     * используется для чтения строки из dataset
     * Ex:
     * $ds = base::ds(...)l
     * while($row=base::read($ds)){
     *    ....
     * }
     * @param {dataset} $ds - результат base::ds()
     * @return array(..) | NULL
     */
    public static function read($ds){
        return $ds->fetch_assoc();
    }

    public static function value($sql,$base,$param=[]){
        $p = array_merge([
            'field'     =>'',
            'default'   =>null,
            'coding'    =>null,
            'limit'     =>true
        ],$param);

        $field      = $p['field'];
        $default    = $p['default'];
        $coding     = $p['coding'];
        
        try {

            if ($p['limit']===true){
                if (preg_match('/\s+limit\s+[0-9]+[\s\S]*\Z/m', $sql)!==1)
                    $sql.=' limit 1';
            }            

            $ds = self::ds($sql,$base,$coding);
            
            $fields = self::fields($ds);
            
            if ($field === '')
                $field = $fields[0];
            else if (array_search($field,$fields)===false)
                throw new \Exception(" field = ['$field'] not exist", 0);
                
            if (self::isEmpty($ds))
                throw new \Exception('result of ['.$sql.'] is empty',0);
            
            $row = self::row($ds);
            return $row[$field];

        } catch (\Exception $e) {
            if ($default === null)
                self::doThrow(__METHOD__,$base,$e->getMessage());
        };

        return $default;
    }    
    
    public static function startTransaction($base){
        $b = self::getbase($base);
        
        if ($b->transaction==0)
            $b->db->autocommit(false);    
        $b->transaction+=1;
        return true;
    }

    public static function commit($base){
        $b = self::getbase($base);

        $b->transaction-=1;
        
        if ($b->transaction==0){
            $b->db->commit();
            return true;
        }
        
        if ($b->transaction<0)
            self::doThrow(__METHOD__,$base,'transaction overflow loop...');
        
        return false;
    }
    
    public static function rollback($base=null){
        $b = self::getbase($base);
        
        $b->transaction-=1;
        
        if ($b->transaction==0){ 
            $b->db->rollback();
            return true;
        }
        
        if ($b->transaction<0)
            self::doThrow(__METHOD__,$base,'transaction overflow loop...');
        
        return false;
        
    }
    
    private static function uuidProxy(){
        
        $chrLeft  = 97; //a
        $chrRight  = 102;//f
        $chr0  = 48;
        $chr9  = 57;

        $is_num = (rand(0,10)<7?true:false);

        if ($is_num)
            $code = rand($chr0,$chr9);
        else
            $code = rand($chrLeft,$chrRight);

        return chr($code);
    }
        
    public static function uuid($count=32){
        $uuid = '';
        for($i=0;$i<$count;$i++)
            $uuid.=self::uuidProxy();
        return $uuid;        
    }
    
    public static function insert_uuid($table,$index,$base,$fieldUUID='UUID',$countUUID=32){
        $uuid = self::uuid($countUUID);

        $q='insert into `'.$table.'` set `'.$fieldUUID."` = '".$uuid."'";
        self::query($q,$base);

        $q = 'select `'.$index.'` from `'.$table.'` where `'.$fieldUUID."`='".$uuid."'";
        return self::value($q,$base,['field'=>$index]);
        
    }


    /** преобоазует значение к представлеию в SQL запросе в зависимости от его типа */
    public static function typePerform($value,$type){
        
        
        if (($type==='string')||($type==='date')){
            return '"'.self::esc($value).'"';
        }
        return $value;
        
    }    
    /** генерация текста запроса по входным данным 
    * @param {string} typeQuery insert|update|insertOnDuplicate
    * @param {string} table - имя таблицы
    * @param {array} data  =  [ FIELD_NAME=>VALUE , FIELD_NAME=>[VALUE] , FIELD_NAME=>[VALUE,TYPE],...]
    * Если указывать значение в скобках [VALUE] то его тип будет определяться автоматический
    * Если указать тип значения [VALUE,TYPE] то данный тип будет иметь приоритет над указанным в param->types 
    * @param {array} param =
    *   types=>array,           - array('NAME'=>'string',..) для получения списка типов полей, можно воспользоваться base::fieldsInfo(base,'types');
    *   include=>array|string,  
    *   exclude=>array|string
    *   rename=>array,
    *   where=>string  - выражение, которое (если есть) будет использоваться в запросе update
    *   refactoring = true - вывод в удобном для анализа виде
    *   alias=>array|string|string;string (префексы перед именем поля, перед формированием запроса он удаляется, преобразуя поле 
    *                 в соотвествующее в таблице
    *   ex: alias = "tab";
    *       "tab_NAME" - > "NAME"
    *   
    * @return string|bool    вернет либо запрос, либо false если ни одного поля не было добавлено в запрос
    */
    public static function generate($queryType,$table,$data,$param=[]){

        $types      = isset($param['types'])?$param['types']:[];
        $bTypes     = count($types)>0;
        
        $exclude    = isset($param['exclude'])?$param['exclude']:array();
        if (gettype($exclude)==='string') $exclude = explode(';',str_replace(',',';',$exclude));
        $bExclude   = count($exclude)>0;
    
        $include    = isset($param['include'])?$param['include']:array();
        if (gettype($include)==='string') $include = explode(';',str_replace(',',';',$include));
        $bInclude   = count($include)>0;
    
        $rename     =  isset($param['rename'])?$param['rename']:array();
        $bRename    = count($rename)>0;

        $index     =  isset($param['index'])?$param['index']:'';
        if (($queryType === 'insertOnDuplicate')&&($index === '')){
            foreach($data as $f=>$v){
                if (mb_strpos(strtoupper($f),'ID')!==0){
                    $index = $f;
                    break;
                }
            }
        }

        $where      =  trim(isset($param['where'])?$param['where']:'');
        if ( ($where!=='') && (mb_strpos(strtoupper($where),'WHERE')!==0))
            $where =  'where '.$where;

        $pref     =  isset($param['alias'])?$param['alias']:array();
        if (is_string($pref))
            $pref=array($pref);
        $bPref    = count($pref)>0;
        
        if ( isset($param['refactoring'])  &&  $param['refactoring'] === true ){
            $bRef = true;
            $DCR = "\n\t";
            $CR = "\n";
        }else{
            $bRef = false;
            $DCR = '';
            $CR = "";
        }
        

        $insertBlockLeft = '';
        $insertBlockRight = '';
        $updateBlock = '';

        $is_empty = true;
        $whereHaveVar = strpos($where,'::')===false?false:true;

        foreach($data as $f=>$v){
        
            $need = true;
            $field = $f;
            $value = $v;
            $valType = gettype($value);

            if (($need)&&($bPref)){
                for($i=0;$i<count($pref);$i++){
                    if (strpos($field,$pref[$i].'_')===0){
                        $field = str_replace($pref[$i].'_','',$field);
                        break;
                    }
                }
            } 
            

            if (($need)&&($bRename)){
                if (isset($rename[$field]))
                    $field = $rename[$field];
            } 
            
        
            if ($bInclude){
                $need = (array_search($field,$include)!==false);
            }    
        
            if (($need)&&($bExclude))
                $need = (array_search($field,$exclude)===false);
            
            if (($need)&&($bTypes || $valType ==='array')){
                if ($valType === 'array'){
                    $tp = count($value)>1?$value[1]:gettype($value[0]);
                    $value = self::typePerform($value[0],$tp);
                }elseif (isset($types[$field])!==false){
                    $tp = $types[$field];
                    $value = self::typePerform($value,$tp);
                }
            }

            if ($whereHaveVar){ 
                $whereValue = $value;
                if ( !(( $need)&&($bTypes || $valType ==='array') ) ){
                    if ($valType === 'array'){
                        $tp = count($value)>1?$value[1]:gettype($value[0]);
                        $whereValue = self::typePerform($value[0],$tp);
                    }elseif (isset($types[$field])!==false){
                        $tp = $types[$field];
                        $whereValue = self::typePerform($value,$tp);
                    }
                }

                $where = str_replace('::'.$field,$whereValue,$where);
            }

        
        
            if ($need){
                
                $tab ='';
                if ($bRef){
                    $sl = strlen($field)+2+($queryType!=='insert'?1:0);
                    $tab = ($sl<8?"\t\t":($sl<17?"\t":""));
                };
                    
                $updateBlock.=($updateBlock!==''?',':'').$DCR."`$field`".'='.$tab.$value;
                $insertBlockLeft.=($insertBlockLeft!==''?',':'').$DCR."`$field`";
                if (($queryType==='insert')||($queryType==='insertOnDuplicate')){
                    if ($bRef)
                        $insertBlockLeft.=$tab.'/*'.$value.'*/';
                    
                    $insertBlockRight.=($insertBlockRight!==''?',':'').$value; 
                }
                $is_empty = false;
            }
        }
    
        if ($is_empty) 
            return false;
        else{    
            $result = false;
            if ($queryType === 'insert'){
                $result = 'insert into '.$DCR."`$table` ".$CR."(".$CR."$insertBlockLeft".$CR.") ".$CR."values ($insertBlockRight) ";
            }elseif ($queryType === 'update'){
                $result = 'update '.$DCR."`$table` ".$CR."set $updateBlock ".$CR.$where.' ';
            }elseif($queryType === 'insertOnDuplicate'){
                $result = 'insert into '.$DCR."`$table` ".$CR."(".$CR."$insertBlockLeft".$CR.") ".$CR."values ($insertBlockRight) ".$CR."on duplicate key update "."$updateBlock ";
            }
            return trim($result);
        }
    }


    /** преобразуем данные из $param в список полей для формирования запроса sql */
    public static function fieldsToSQL($param){


        $exclude    = isset($param['exclude'])?$param['exclude']:array();
        if (gettype($exclude)==='string') $exclude = explode(';',str_replace(',',';',$exclude));
        $bExclude   = count($exclude)>0;
    
        $include    = isset($param['include'])?$param['include']:array();
        if (gettype($include)==='string') $include = explode(';',str_replace(',',';',$include));
        $bInclude   = count($include)>0;
    
        $pref     =  isset($param['alias'])?$param['alias']:array();
        $bPref    = count($pref)>0;
            
        $types    = isset($param['types'])?$param['types']:array();
        $bTypes   = count($types)>0;
        
        if ($param['refactoring']===true){
            $DCR = "\n\t";
            $CR = "\n";
        }else{
            $DCR = '';
            $CR = "";
        }
        $fields = array();
        if ($bTypes){
            $_f = array_keys($types);
          //_LOG('['.print_r($include,true).']',__FILE__,__LINE__);
    
            for($i=0;$i<count($_f);$i++){
                $need = true;
                $field = $_f[$i];
                //_LOG("$field",__FILE__,__LINE__);
    
                if ($bInclude)
                    $need = (array_search($field,$include)!==false);
                    
                //_LOG("$field:[$need]",__FILE__,__LINE__);
                
                if (($need)&&($bExclude))
                    $need = (array_search($field,$exclude)===false);

                if ($need)
                    $fields[]=$field;
            }        
        }else if ($bInclude){
            $_f = $include;
            for($i=0;$i<count($_f);$i++){
                $need = true;
                $field = $_f[$i];
                
                if ($bExclude)
                    $need = (array_search($field,$exclude)===false);

                if ($need)
                    $fields[]=$field;
            }        

        }else
            return '';
        //_LOG('['.print_r($fields,true).']',__FILE__,__LINE__);
      
        $res = '';
        
        for($i=0;$i<count($fields);$i++)
            $res.=($res===''?$CR:','.$CR).$pref.'.'.$fields[$i].' '.$pref.'_'.$fields[$i];
        return $res.' '.$CR;
    }
        
    public static function real_escape($string,$base=null){
        $db = self::db($base);
        if (!$db) 
            return $string;
        else    
            return $db->real_escape_string($string);
    }
    
    public static function esc($string){
        $from = array('"');
        $to   = array('\"');
        return str_replace($from,$to,$string);
    }
    /**  
     * заменяет параметры типа :FIELDNAME в $sql , на их значение value из $FeildNameValue=['FIELDNAME'=>value]
     * тк же можно задать $value [VALUE,TYPE] либо указать в $param['types'=>]
    */
    public static function paramToSql(string $sql,array $FieldNameValue=[],array $param=[]){
        
        $param = array_merge([
            'types'=>[]
        ],$param);


        try {
            
            $fields = array_keys($FieldNameValue);
            self::_haveKeys($sql,$fields);

            uksort($FieldNameValue,function($a,$b){return strlen($a)<strlen($b);});
            $types = $param['types'];

                $from = [];
            $to = [];
            foreach($FieldNameValue as $name=>$value){

                $type = 'int';

                if (gettype($value) === 'array'){
                    $type = $value[1];
                    $value = $value[0];
                }elseif (isset($types[$name])){
                    $type = $types[$name];
                };
                
                $value = self::typePerform($value,$type);
                $from[] = ':'.$name;
                $to[] = $value;
            }

            return str_replace($from,$to,$sql);
            
        } catch (\Exception $e) {
            error_log('Exception ['.__FILE__.':'.__LINE__.'] '.$e->getMessage());
        };
    }
    /** внесение изменений в таблицу 
     * @return {boolean || Exception}
    */
    public static function update(string $base,string $tableName,array $data,string $where,$coding = null){
        
        Base::startTransaction($base);

        try {
            $types  = self::getTypes($tableName,$base);
            $q      = self::generate('update',$tableName,$data,['types'=>$types,'where'=>$where]);
            
            Base::query($q,$base);
            Base::commit($base);

        } catch (\Exception $e) {
            Base::rollback($base);
            throw new \Exception($e->getMessage());
        };
        return true;

    }
    /** проверка, что keys содержит все подставляемые переменные для $sql 
     * Ex: sqlHavekeys('select * from one where id=:id and name=":name"',["id",'name','data']) = true
     * Ex: sqlHavekeys('select * from one where id=:id and name=":name"',["id",'data']) : raise Exception
    */
    private static function _haveKeys(string $sql,array $keys):bool{
        
        //error_log(print_r($keys,true));
        $re = '/(:)([a-zA-Z_0-9]+)/m';
        preg_match_all($re, $sql, $result);
        $result = array_unique($result[2]);
        //error_log(print_r($result));
        foreach($result as $name){
            if (array_search($name,$keys)===false){
                //return false;
                throw new \Exception("field $name not exists");
            }
        }

        return true;
    }
};


?>