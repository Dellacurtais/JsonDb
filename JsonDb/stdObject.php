<?php
class JsonDb_stdObject {
    public static $instance;

    public function __construct(array $arguments = array()) {
        self::$instance = $this;
        if (!empty($arguments)) {
            foreach ($arguments as $property => $argument) {
                $this->{$property} = $argument;
            }
        }
    }

    public function __call($method, $arguments) {
        $arguments = array_merge(array("stdObject" => $this), $arguments); // Note: method argument 0 will always referred to the main class ($this).
        if (isset($this->{$method}) && is_callable($this->{$method})) {
            return call_user_func_array($this->{$method}, $arguments);
        } else {
            throw new Exception("Fatal error: Call to undefined method stdObject::{$method}()");
        }
    }

    public static function __callStatic($method, $arguments) {
        $arguments = array_merge(array("stdObject" => self::$instance), $arguments);
        if (isset(self::$instance->{$method}) && is_callable(self::$instance->{$method})) {
            return call_user_func_array(self::$instance->{$method}, $arguments);
        } else {
            throw new Exception("Fatal error: Call to undefined method stdObject::{$method}()");
        }
    }
}