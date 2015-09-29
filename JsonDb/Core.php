<?php
class JsonDb_Core {
    public static $instance;

    protected $DirModels = null;
    protected $DateFormt = "dd/mm/yyyy";

    public static function getInstance(){
        if(static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function setDir($dir){
        if (is_null($dir)){
            throw new Exception("JsonDB_Core::setDir[{$this->DirModels}] no is a valid directory");
        }
        $this->DirModels = $dir;
    }

    public function getDir(){
        if (is_null($this->DirModels)){
            throw new Exception("JsonDB_Core::getDir is a null directory");
        }
        return $this->DirModels;
    }

    public function getDateFormat(){
        return $this->DateFormt;
    }

    public function setDateFormat($date){
        $this->DateFormt = $date;
    }
}