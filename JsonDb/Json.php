<?php
abstract class JsonDb_Json extends JsonDb_Property {
    protected $_File;
    protected $_FileInfo;
    protected $_Name;

    protected $_TipoModel = "JSON";

    /*
     * Relative Data
     */
    protected $_Relative;
    protected $_GetRelative;

    /*
     * Set Error
     */
    protected $_Error = null;
    protected $_Errors = array();

    /*
     * Data Object
     */
    protected $_Data;
    protected $_DataJson;
    protected $_DataResult = null;
    protected $_RemoveId = null;
    protected $_RemoveRelative = null;

    /*
     * NextId
     */
    protected $_NextId;

    /*
     * ID object
     */
    protected $_Id = null;

    /*
     * Dir of File
     */
    protected $_Dir;

    /*
     * Set Types do Validation
     */
    protected $_Types;

    /*
     * set Plugin
     */
    protected $Plugin;

    /*
     * Stats Files
     */
    protected $_InfoDataBase;
    protected $_fSize = 0;
    protected $_fTime = 0;

    /*
     * Loaded Json
     */
    protected $_LoadDatas = false;

    /*
     * Result Find Type
     */
    protected $_ResultType = null;

    /*
     * Default Values
     */
    protected $_Defaults;

    /*
     * Description
     */
    protected $_Descriptions;

    /*
     * Sets Json
     */
    protected $_SetsJson;

    /*
     * Set Size/Value
     */
    protected $_SizeVal;

    /*
     * DateFormat
     */
    protected $FormatDate = "dd/mm/yyyy";

    /*
     * External Validation
     */
    protected $_ExternValidation = array();

    /*
     * Construc Class
     */
    public function __construct(){
        $this->_SetsJson = JsonDb_JsonSets::getInstance();

        $this->_Name = str_replace("ModelJdb_","",get_class($this));

        $this->_Dir = JsonDb_Core::getInstance()->getDir();

        $this->_File = $this->_Dir.$this->_Name.".json";
        $this->_FileInfo = $this->_Dir.$this->_Name."_Info.json";

        $this->setDatas();
        $this->hasMany();
    }


    /*
     * Json Load
     */
    protected function loadJson(){
        if (!file_exists($this->_FileInfo)) {
            file_put_contents($this->_FileInfo, $this->toJsonArray(array("nextId" => 1)));
        }

        if (!file_exists($this->_File)) {
            $this->_DataJson = $this->toJsonArray(array());
            file_put_contents($this->_File, $this->_DataJson);
            $this->_fTime = time();
            $this->_fSize = 2;
            $this->_Data = array();
        } else {
            $this->_DataJson = file_get_contents($this->_File);
            $this->_InfoDataBase = json_decode(file_get_contents($this->_FileInfo));

            $this->_Data = json_decode($this->_DataJson, 1);

            $this->_NextId = $this->_InfoDataBase->nextId;

            $this->_fTime = filemtime($this->_File);
            $this->_fSize = filesize($this->_File);
        }
        $this->_LoadDatas = true;
    }

    /*
     * Save Config Db
     */
    public function infoDatabase(){

    }

    /*
     * Override Function on Model
     */
    public function setDatas(){}

    /*
     * Override Function on Model
     */
    public function hasMany(){}

    /*
     * Set New Property
     */
    public function setData($name,$type,$sizeVal = null,$default = null,$description = null){
        if ($name == "_Id" || $name == "Id" || $name == "id" || $name == "_id"){
            $this->_Notice("'_Id' and 'Id' in ".$this->_Name." is a property reserved for JsonDb");
            return;
        }

        if (!$this->_SetsJson->checkTypes($type)){
            $this->_Notice("'{$type}' not is a valid type on '{$name}'");
            return;
        }

        $this->__set($name,null);
        $this->_Types[$name] = $type;
        $this->_SizeVal[$name] = $sizeVal;
        $this->_Defaults[$name] = $default;
        $this->_Descriptions[$name] = $description;
    }

    /*
     * Set Relative
     */
    public function setMany($Relative,$from,$to){
        $this->_Relative["_".$Relative] = array($from,$to);
        $this->__set("_".$Relative,null);
    }

    /*
     * Set Relative Docs Json
     */
    public function getRelative($name){
        $this->_GetRelative[$name] = true;
    }

    /*
     * set External Validation
     */
    public function setValidation($key,$function){
        $this->_ExternValidation[$key] = $function;
    }

    /*
     * Get Rows
     */
    public function getRows(){
        $rows = array();
        foreach ($this->uiyewyqueyewuqibcnsabh as $key=>$Value){
            if (!isset($this->_Relative[$key]))
                $rows[] = $key;
        }
        return $rows;
    }

    /*
     * Array to Json
     */
    protected function toJsonArray($array){
        return json_encode($array);
    }

    /*
     * Result to Json
     */
    public function toJson(){
        if (is_null($this->_DataResult)){
            return $this->_DataJson;
        }else{
            return $this->toJsonArray($this->_DataResult);
        }
    }

    /*
     * Get Data Result
     */
    public function toArray(){
        if (is_null($this->_DataResult)) {
            return array();
        }else{
            return $this->_DataResult;
        }
    }

    /*
     * Check Id
     */
    public function checkId($id){
        if (!$this->_LoadDatas) $this->loadJson();
        if (isset($this->_Data[$id])){
            return true;
        }else{
            return false;
        }
    }

    /*
     * getId
     */
    public function getId($id){
        if (!$this->_LoadDatas) $this->loadJson();
        return $this->_Data[$id];
    }

    /*
     * Save Many
     */
    public function saveMany($Obj){
        if (!is_array($Obj)){
            return null;
        }

        if (!$this->_LoadDatas) $this->loadJson();
        foreach ($Obj as $_IDSAVE=>$Data){
            $save = false;
            foreach ($Data as $key=>$Value) {
                $this->__set($key,$Value);
                if (isset($this->_Types[$key])) {
                    if ($this->_Validation($key, $this->_Types[$key])) {
                        if (isset($this->_ExternValidation[$key])) {
                            if ($this->_CheckValidation($key, $Data->$key)) {
                                $this->_Data[$_IDSAVE][$key] = $Data->$key;
                            } else {
                                $this->_Error = true;
                            }
                        } else {
                            $this->_Data[$_IDSAVE][$key] = $Data->$key;
                        }
                    } else {
                        $this->_Error = true;
                    }
                } else {
                    if (!isset($this->_Relative[$key])) {
                        if (isset($this->_ExternValidation[$key])) {
                            if ($this->_CheckValidation($key, $Data->$key)) {
                                $this->_Data[$_IDSAVE][$key] = $Data->$key;
                            } else {
                                $this->_Error = true;
                            }
                        } else {
                            $this->_Data[$_IDSAVE][$key] = $Data->$key;
                        }
                    }

                }
                $save = true;
            }
            if (is_null($this->_Error) && $save) {
                file_put_contents($this->_File, $this->_DataJson = $this->toJsonArray($this->_Data));
            }
        }
    }

    /*
     * Save Data
     */
    public function save(){
        if (!$this->_LoadDatas) $this->loadJson();

        if (!is_null($this->_Error)){
            $this->_Notice("Unable to save data in ".$this->_Name);
            return $this->_Error;
        }

        $_IDSAVE = null;
        $_UpdateDataResult = null;
        if (!is_null($this->_Id)){
            $_IDSAVE = $this->_Id;
            $_UpdateDataResult = true;
        }else{
            if (is_null($this->_NextId)) $this->_NextId = 1;
            $_IDSAVE = $this->_NextId;
            $this->_NextId++;
        }

        $this->_Id = $_IDSAVE;
        $this->_Data[$_IDSAVE] = array();
        $this->_Data[$_IDSAVE]['_Id'] = $_IDSAVE;
        $Parents = null;
        foreach ($this->uiyewyqueyewuqibcnsabh as $key=>$Value){
            if (isset($this->_Types[$key])){
                if ($this->_Validation($key,$this->_Types[$key])){
                    if (isset($this->_ExternValidation[$key])){
                        if ($this->_CheckValidation($key,$this->$key)){
                            $this->_Data[$_IDSAVE][$key] = $this->$key;
                        }else{
                            $this->_Error = true;
                        }
                    }else{
                        $this->_Data[$_IDSAVE][$key] = $this->$key;
                    }
                }else{
                    $this->_Error = true;
                }
            }else{
                if (!isset($this->_Relative[$key])){
                    if (isset($this->_ExternValidation[$key])){
                        if ($this->_CheckValidation($key,$this->$key)){
                            $this->_Data[$_IDSAVE][$key] = $this->$key;
                        }else{
                            $this->_Error = true;
                        }
                    } else {
                        $this->_Data[$_IDSAVE][$key] = $this->$key;
                    }
                }

            }
			
			$ModelRelative = substr($key,1);
            if (isset($this->_Relative[$key]) && $this->_GetRelative[$ModelRelative]){
                $fromRelative = $this->_Relative[$key][0];
                $toRelative = $this->_Relative[$key][1];				
                $Class = "ModelJdb_".$ModelRelative;
				if (is_array($this->$key)){
                    $Parent = new $Class();
					$isNeedSaveParent = false;
                    foreach ($this->$key as $k => $obj) {
						if (is_array($obj)){
							$Parent = new $Class();
							foreach ($obj as $kid => $vp) {
								$Parent->$kid = $vp;								
							}
							$Parent->$toRelative = $this->$fromRelative;
							$Parents[] = $Parent;
						}else{
							$Parent->$k = $obj;
							$isNeedSaveParent = true;
						}
                    }
					if ($isNeedSaveParent){
						$Parent->$toRelative = $this->$fromRelative;
						$Parents[] = $Parent;
					}
                }
            }
        }

        if (is_null($this->_Error)){
            file_put_contents($this->_File,$this->_DataJson = $this->toJsonArray($this->_Data));
            file_put_contents($this->_FileInfo, $this->toJsonArray(array("nextId" => $this->_NextId)));
            if (!is_null($Parents)) {
                foreach ($Parents as $Parent) {
                    $_idParent = $Parent->save();
                    if (!is_int($_idParent)) {
                        $this->_Notice($_idParent);
                    }
                }
            }
            if ($_UpdateDataResult){
                $this->_DataResult = $this->_Data[$_IDSAVE];
            }
            return $_IDSAVE;
        }else{
            $this->_NextId--;
        }

        return false;
    }

    /*
     * Remove Keys
     */
    public function remove($Relative = true){
        $this->_Data = array_diff_key($this->_Data, $this->_RemoveId);
        $this->removeRelativeData($Relative);
        file_put_contents($this->_File,$this->_DataJson = $this->toJsonArray($this->_Data));
        $this->_RemoveId = null;
        $this->_RemoveRelative = null;
        $this->_DataResult = array();
    }

    /*
     * Remove Many Keys
     */
    public function removeData($array){
        if (is_array($array)) {
            if (!$this->_LoadDatas) $this->loadJson();
            foreach ($array as $key) {
                $$this->_Data = array_diff_key($this->_Data, $key);
                file_put_contents($this->_File, $this->_DataJson = $this->toJsonArray($this->_Data));
            }
        }
    }

    /*
     * Get Data
     */
    public function find($Arr = true){
        if (!$this->_LoadDatas) $this->loadJson();
        if ($Arr) {
            return $this->_Data;
        } else {
            $this->_DataResult = $this->_Data;
            return $this;
        }
    }

    /*
     * findBy - find by key
     */
    protected function findBy($var,$val,$like = null){
        $this->_DataResult = array();
        $this->_RemoveId = array();

        if ($var == "Id") $var = "_Id";

        $_Return = false;
        foreach ($this->_Data as $_id=>$Args){
            if(!is_null($like)){
                if (isset($Args[$var]) && strpos($Args[$var],$val) !== false){
                    $this->_DataResult[$_id] = $Args;
                    list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                    if ($Model) $this->_DataResult[$_id][$Model] = $ModelValue;
                    $_Return = true;
                    $this->_RemoveId[$_id] = true;
                }
            } else {
                if (isset($Args[$var]) && $Args[$var] == $val) {
                    $this->_DataResult[$_id] = $Args;
                    list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                    if ($Model) $this->_DataResult[$_id][$Model] = $ModelValue;
                    $_Return = true;
                    $this->_RemoveId[$_id] = true;
                }
            }
        }

        if (!$_Return)
            return null;
        else
            return $this;
    }

    /*
     * findOneBy - find one by key
     */
    protected function findOneBy($var,$val,$like = null){
        $this->_DataResult = null;
        $this->_RemoveId = array();
        $Model = null;
        if ($var == "Id") $var = "_Id";

        $_Return = false;
        foreach ($this->_Data as $_id=>$Args){
            if(!is_null($like)){
                if (isset($Args[$var]) && strpos($Args[$var],$val) !== false){
                    $this->_DataResult = $Args;
                    $this->_Id = $_id;
                    list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                    if ($Model) $this->_DataResult[$Model] = $ModelValue;
                    $_Return = true;
                    $this->_RemoveId[$_id] = true;
                    break;
                }
            } else {
                if (isset($Args[$var]) && $Args[$var] == $val){
                    $this->_DataResult = $Args;
                    $this->_Id = $_id;
                    list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                    if ($Model) $this->_DataResult[$Model] = $ModelValue;
                    $_Return = true;
                    $this->_RemoveId[$_id] = true;
                    break;
                }
            }
        }

        if (is_null($this->_DataResult)) return array();

        foreach ($this->_DataResult as $key=>$var){
            if ($Model == $key){
                $i = 0;
                foreach ($var as $k=>$v){
                    $var[$i] = (object)$v;
                    $i++;
                }
                $this->__set($key,$var);
            }else {
                $this->__set($key, $var);
            }
        }

        if (!$_Return)
            return null;
        else
            return $this;
    }

    /*
     * Group by key
     */
    protected function groupBy($var, $val = "", $array = array(null,null,null)){
        $this->_DataResult = array();
        $this->_RemoveId = array();
        list($var2,$val2,$like) = $array;
        $_Return = false;
        if (is_array($val) && count($val) == 3){
            list($var2,$val2,$like) = $val;
            foreach ($this->_Data as $_id => $Args) {
                if (is_null($like)) {
                    if (isset($Args[$var2]) && $val2 == $Args[$var2]) {
                        $this->_DataResult[$Args[$var]][$_id] = $Args;
                        list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                        if ($Model) $this->_DataResult[$Args[$var]][$_id][$Model] = $ModelValue;
                        $_Return = true;
                        $this->_RemoveId[$_id] = true;
                    }
                } else {
                    if (isset($Args[$var2]) && strpos($Args[$var2], $val2) !== false) {
                        $this->_DataResult[$Args[$var]][$_id] = $Args;
                        list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                        if ($Model) $this->_DataResult[$Args[$var]][$_id][$Model] = $ModelValue;
                        $_Return = true;
                        $this->_RemoveId[$_id] = true;
                    }
                }
            }
        }else {
            foreach ($this->_Data as $_id => $Args) {
                if (is_null($val2)) {
                    if (isset($Args[$var]) && $Args[$var] == $val) {
                        $this->_DataResult[$Args[$var]][$_id] = $Args;
                        list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                        if ($Model) $this->_DataResult[$Args[$var]][$_id][$Model] = $ModelValue;
                        $_Return = true;
                        $this->_RemoveId[$_id] = true;
                    }else if (isset($Args[$var]) && empty($val)){
                        $this->_DataResult[$Args[$var]][$_id] = $Args;
                        list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                        if ($Model) $this->_DataResult[$Args[$var]][$_id][$Model] = $ModelValue;
                        $_Return = true;
                        $this->_RemoveId[$_id] = true;
                    }
                } else {
                    if (is_null($like)) {
                        if (isset($Args[$var2]) && $val2 == $Args[$var2] && $Args[$var] == $val) {
                            $this->_DataResult[$Args[$var]][$_id] = $Args;
                            list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                            if ($Model) $this->_DataResult[$Args[$var]][$_id][$Model] = $ModelValue;
                            $_Return = true;
                            $this->_RemoveId[$_id] = true;
                        }
                    } else {
                        if (isset($Args[$var2]) && strpos($Args[$var2], $val2) !== false && $Args[$var] == $val) {
                            $this->_DataResult[$Args[$var]][$_id] = $Args;
                            list($Model,$ModelValue)  = $this->getRelativeData($_id,$Args,$var);
                            if ($Model) $this->_DataResult[$Args[$var]][$_id][$Model] = $ModelValue;
                            $_Return = true;
                            $this->_RemoveId[$_id] = true;
                        }
                    }
                }
            }
        }

        if (!$_Return)
            return null;
        else
            return $this;
    }

    /*
     * Find Relatives Data
     */
    protected function getRelativeData($_id,$Args,$var){
        if ($this->_GetRelative){
            foreach ($this->_GetRelative as $Key=>$v){
                $Model = "_".$Key;
                if (isset($this->_Relative[$Model])){
                    if (!is_object($v)){
                        $Class = "ModelJdb".$Model;
                        $this->_GetRelative[$Key] = new $Class();
                    }
                    $fromKey = $this->_Relative[$Model][0];
                    $toKey = "findBy".$this->_Relative[$Model][1];
                    if ($fromKey != "_Id") {
                        $this->_RemoveRelative[$Key] = array($this->_Relative[$Model][1],$Args[$var][$fromKey]);
                        $Models = $this->_GetRelative[$Key]->$toKey($Args[$var][$fromKey]);
                        if (is_object($Models)) {
                            return array($Model, $Model->toArray());
                        }
                        return array(null,null);
                    }else{
                        $this->_RemoveRelative[$Key] = array($this->_Relative[$Model][1],$_id);
                        $Models = $this->_GetRelative[$Key]->$toKey($_id);
                        if (is_object($Models)) {
                            return array($Model,$Models->toArray());
                        }
                        return array(null,null);
                    }
                }
            }
        }
    }

    /*
     * Remove Relative Data
     */
    protected function removeRelativeData($Relative){
        if ($Relative && $this->_GetRelative){
            foreach ($this->_RemoveRelative as $key=>$arr){
                $find = "findBy".$arr[0];
                $this->_GetRelative[$key]->$find($arr[1])->remove();
            }
        }
    }

    /*
     * OrderBy - order array
     */
    protected function orderByDefault($OrderBy,$Order){
        if (!is_array($this->_DataResult)){
            $this->_DataResult = array();
        }

        $Order = $Order == "DESC" ? $Order : "ASC";
        $OrderObj = new JsonDb_stdObject();
        $OrderObj->OrderBy = $OrderBy;
        $OrderObj->Order = $Order;
        $OrderObj->cmp_obj = function($stdObject,$a, $b){
            if ($a[$stdObject->OrderBy] == $b[$stdObject->OrderBy]) {
                return 0;
            }
            if ($stdObject->Order == "ASC")
                return ($a[$stdObject->OrderBy] < $b[$stdObject->OrderBy]) ? -1 : 1;
            else
                return ($a[$stdObject->OrderBy] < $b[$stdObject->OrderBy]) ? 1 : -1;
        };

        if ($this->_ResultType == "normal") {
            if (empty($OrderBy))
                $this->_Notice($this->Plugin->getTranslate("'orderBy' require a name of column"));

            usort($this->_DataResult, array(&$OrderObj, 'cmp_obj'));
        }else{
            if (empty($OrderBy)){
                if ($Order == "ASC")
                    ksort($this->_DataResult);
                else
                    krsort($this->_DataResult);
            }else{
                foreach ($this->_DataResult as $k=>$Arr){
                    usort($Arr, array(&$OrderObj, 'cmp_obj'));
                    $this->_DataResult[$k] = $Arr;
                }
            }
        }

        return $this;
    }

    /*
     * Format Byte - Used in FileSize
     */
    public function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        /*$bytes /= (1 << (10 * $pow));*/
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /*
     * Set Data Format
     * yyyy-mm-dd
     * mm/dd/yyyy
     * dd/mm/yyyy
     */
    public function setDateFormat(){
        $this->FormatDate = JsonDb_Core::getInstance()->getDateFormat();
    }

    /*
    * Validation
    */
    protected function _Validation($key,$type){
        $this->setDateFormat();
        if (!empty($this->_Defaults[$key]) && empty($this->$key)){
            $this->$key = $this->_Defaults[$key];
            return true;
        }
        switch($type){
            case 'int':
                if (!is_int($this->$key)) {
                    $this->_Notice("'{$key}' not is a int");
                    $this->_Errors[$key] = true;
                    return false;
                }
                break;
            case 'float':
                if (!is_int($this->$key+0)) {
                    $this->_Notice("'{$key}' not is a float");
                    $this->_Errors[$key] = true;
                    return false;
                }
                break;
            case 'varchar':

                break;
            case 'date':
                if (!JsonDb_is_date($this->$key,$this->FormatDate)){
                    $this->_Notice("'{$key}' not is a date");
                    $this->_Errors[$key] = true;
                    return false;
                }
                break;
            case 'singleOption':
                if (!in_array($this->$key,$this->_SizeVal[$key])){
                    $this->_Notice("'{$key}' not is valid value");
                    $this->_Errors[$key] = true;
                    return false;
                }
                break;
            case 'multOptions':
                if (is_array($this->$key)){
                    foreach ($this->$key as $k=>$v) {
                        if (!in_array($v,$this->_SizeVal[$key])) {
                            $this->_Notice("'{$key}' not is valid value");
                            $this->_Errors[$key] = true;
                            return false;
                        }
                    }
                }else{
                    $this->_Notice("'{$key}' not is valid value");
                    $this->_Errors[$key] = true;
                    return false;
                }
                break;
            case 'unique':
                $FindUnique = str_replace(array("{","}"),"",json_encode(array($key => $this->$key)));
                if (strpos($this->_DataJson,$FindUnique) !== false) {
                    $this->_Notice("'{$key}' is a unique, and '{$this->$key}' has already been found in the register");
                    $this->_Errors[$key] = true;
                    return false;
                }
                break;
        }

        if (!in_array($type,array("multOptions",'singleOption'))) {
            if (!is_null($this->_SizeVal[$key]) && isset($this->$key{$this->_SizeVal[$key]})) {
                $this->_Notice("'{$key}' can not be greater than " . $this->_SizeVal[$key]);
                $this->_Errors[$key] = true;
                return false;
            }
        }
        return true;
    }

    public function _CheckValidation($key,$value){
           if (is_array($this->_ExternValidation[$key])){
               $Class = $this->_ExternValidation[$key][0];
               $Function = $this->_ExternValidation[$key][1];
               if (!is_object($Class)){
                   trigger_error("JsonDb::Validation[{$key}] {$Class} is not a object",E_USER_NOTICE);
                   return false;
               }
               if (!method_exists($Class,$Function)){
                   trigger_error("JsonDb::Validation[{$key}] {$Function} not exist in {$Class}",E_USER_NOTICE);
                   return false;
               }
               if ($Class->$Function($value)){
                   return true;
               }else{
                   $this->_Errors[$key] = true;
                   return false;
               }
           }else{
               $Function = $this->_ExternValidation[$key];
               if (!function_exists($Function)){
                   trigger_error("JsonDb::Validation[{$key}] {$Function} function not exist!",E_USER_NOTICE);
                   return false;
               }
               if ($Function($value)){
                   return true;
               }else{
                   $this->_Errors[$key] = true;
                   return false;
               }
           }
    }

    public function getErrors(){
        if (empty($this->_Errors)){
            return false;
        }
        return $this->_Errors;
    }

    /*
     * Set Notice
     */
    protected function _Notice($var){
        $this->_Error = $var;
        trigger_error("JsonDB::_Error - {$this->_Error}",E_USER_NOTICE);
    }

    /*
     * Reset
     */
    public function newData(){
        $this->_Id = null;
        $this->setDatas();
        return $this;
    }
	
	
	/**
	*	Models to Cvs
	*/
	public function getCvs(){
		$NewJson = JsonDb_JsonCvs();
		if (count($this->_DataResult) > 0){
			$NewJson->headerDownload($this->_Name.".cvs");
			$NewJson->toCvs(current($this->_DataResult),$this->_DataResult)	;
			die();
		}else{
			$NewJson->headerDownload($this->_Name.".cvs");
			$NewJson->toCvs(current($this->_Data),$this->_Data)	;
			die();	
		}
	}
	
    /*
     * Call Functions
     */
    public function __call($_Name, $arguments){
        $by = null;
        $count = count($arguments);
        if (substr($_Name, 0, 6) == 'findBy') {
            $by = substr($_Name, 6, strlen($_Name));
            $method = 'findBy';
            if ($count > 2) {
                $this->_Notice("Set a valid argument to the method 'findBy'.");
                return null;
            }
        } else if (substr($_Name, 0, 9) == 'findOneBy') {
            $by = substr($_Name, 9, strlen($_Name));
            $method = 'findOneBy';
            if ($count > 2) {
                $this->_Notice("Set a valid argument to the method 'findOneBy'.");
                return null;
            }
        } else if (substr($_Name, 0, 7) == 'groupBy') {
            $by = substr($_Name, 7, strlen($_Name));
            $method = 'groupBy';
            if ($count > 2) {
                $this->_Notice("Set a valid argument to the method 'groupBy'.");
                return null;
            }
        } else if (substr($_Name, 0, 7) == 'orderBy') {
            $by = substr($_Name, 7, strlen($_Name));
            $method = 'orderByDefault';
            if ($count > 1) {
                $this->_Notice("Set a valid argument to the method 'orderBy'.");
                return null;
            }
        }

        if (!isset($method) || !method_exists($this,$method)) {
            $this->_Notice("The method ".$_Name." not exist.");
            return null;
        }

        switch ($method) {
            case "findBy":
                $this->_ResultType = "normal";
                if (!$this->_LoadDatas) $this->loadJson();
                return $this->$method($by,$arguments[0],$arguments[1]);
                break;
            case "findOneBy":
                $this->_ResultType = "normal";
                if (!$this->_LoadDatas) $this->loadJson();
                return $this->$method($by,$arguments[0],$arguments[1]);
                break;
            case "groupBy":
                $this->_ResultType = "group";
                if (!$this->_LoadDatas) $this->loadJson();
                return $this->$method($by,$arguments[0],$arguments[1]);
                break;
            case "orderByDefault":
                if (!$this->_LoadDatas) $this->loadJson();
                return $this->$method($by,$arguments[0]);
                break;
        }

    }
}