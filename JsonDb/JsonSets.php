<?php
class JsonDb_JsonSets {
    protected static $instance;
    public $Types;

    public static function getInstance(){
        if(static::$instance === null) {
            static::$instance = new static();
        }
        if (is_null(static::$instance->Types)){
            static::$instance->defaultTypes();
        }
        return static::$instance;
    }

    public function setTypes($type,$name){
        $this->Types[$type] = $name;
    }

    public function GetTypes(){
        return $this->Types;
    }

    public function checkTypes($type){
        if (isset($this->Types[$type]))
            return true;
        else
            return false;
    }

    public function defaultTypes(){
        $this->setTypes("int","Integer");
        $this->setTypes("float","Float");
        $this->setTypes("varchar","Text");
        $this->setTypes("unique","Unique");
        $this->setTypes("date","Date");
        $this->setTypes("singleOption","Single Option");
        $this->setTypes("multOptions","Multiple Choice");
    }
}