<?php
spl_autoload_register(function ($class) {
    $base_dir = defined('JSON_SRC_DIR') ? JSON_SRC_DIR : __DIR__;
    if (strpos($class,'JsonDb_') !== false) {
        $class = str_replace("JsonDb_", "", $class);
        $file = "{$base_dir}/{$class}.php";
        if (file_exists($file)) {
            require $file;
        }
    }
    if (strpos($class,'ModelJdb_') !== false) {
        $Patch = explode("_",$class);
        $file = "{$base_dir}/{$Patch[0]}/{$Patch[1]}.php";
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
require_once "Functions.php";
class JsonDbJdb {
    public static function getTable($table){
        $base_dir = defined('JSON_SRC_DIR') ? JSON_SRC_DIR : __DIR__;
        $file = "{$base_dir}ModelJdb/$table.php";
        if (file_exists($file)) {
            require_once $file;
        }
    }
}