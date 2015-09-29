<?php

class JsonDb_Create {
    public $table;
    public $row = array();
    public $many = array();

    public $error = null;

    public function __construct($Name){
        $this->table = ucfirst($Name);
        $file = __DIR__."/ModelJdb/".$Name.".php";
        if (file_exists($file)){
            $this->error = "Erro! This Model already exists";
            trigger_error($this->error, E_NOTICE);
        }
    }

    public function setColun($name,$type,$sizeVal = null,$default = null,$description = null){
        if (!$this->error) {

            $this->row[$name] = array($type, $sizeVal, $default, $description);

            if (!JsonDb_JsonSets::getInstance()->checkTypes($type)) {
                $this->error = "'{$type}' not is a valid type on '{$name}'";
                trigger_error($this->error, E_NOTICE);
                return;
            }
            if ($type == "singleOptions" && !is_array($sizeVal) || $type == "multOptions" && !is_array($sizeVal) ){
                $this->error = "'{$type}'on {$name} need a Array on sizeVal and Default value \n setColun(string \$name [, string \$type [, Array \$sizeVal [, string \$default [)";
                trigger_error($this->error, E_NOTICE);
                return;
            }
            if ($type == "singleOptions" && !isset($sizeVal[$default]) || $type == "multOptions" && !isset($sizeVal[$default]) ){
                $this->error = "'Default' value on {$name} not found on sizeVal";
                trigger_error($this->error, E_NOTICE);
                return;
            }
        }
    }

    public function setMany($model,$key1,$key2){
        $this->many[$model] = array($key1,$key2);
    }

    public function save(){

        $i = 1;
        $CreatRows = "";
        foreach ($this->row as $key=>$values){
            if ($i > 1) $CreatRows .= "\n\t\t";

            $CreatRows .= '$this->setData("'.$key.'","'.$values[0].'"';
            if (!is_null($values[1])){
                if (is_array($values[1])){
                    $CreatRows .= ',array("'.implode('","',$values[1]).'")';
                }else{
                    $CreatRows .= ',"'.$values[1].'"';
                }
            }
            if (!is_null($values[2])){
                $CreatRows .= ',"'.$values[2].'"';
            }
            if (!is_null($values[3])){
                $CreatRows .= ',"'.$values[3].'"';
            }
            $CreatRows .= ');';
            $i++;
        }

        $i = 1;
        $CreatMany = "";
        foreach ($this->many as $key=>$values){
            if ($i > 1) $CreatRows .= "\n\t\t";
            $CreatMany .= '$this->setMany("'.$key.'","'.$values[0].'","'.$values[1].'");';
            $i++;
        }

        $base = file_get_contents(__DIR__."/baseModel.txt");
        $base = str_replace('%NAME%',$this->table,$base);
        $base = str_replace('%SET%',$CreatRows,$base);
        $base = str_replace('%MANY%',$CreatMany,$base);
        file_put_contents(__DIR__."/ModelJdb/{$this->table}.php",$base);
    }
}
