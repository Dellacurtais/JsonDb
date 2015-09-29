<?php
abstract class JsonDb_Property {
    protected $uiyewyqueyewuqibcnsabh = array();

    public function __set($name,$value = null){
        $this->uiyewyqueyewuqibcnsabh[$name] = $value;
    }

    public function &__get($name){
        return $this->uiyewyqueyewuqibcnsabh[$name];
    }

    public function __isset($name){
        return isset($this->uiyewyqueyewuqibcnsabh[$name]);
    }

    public function __unset($name){
        unset($this->uiyewyqueyewuqibcnsabh[$name]);
    }
}