<?php
require_once("_Setup.php");

//Export All
$User = new ModelJdb_Users();
/**
* $Delimiter is Optional 
* Ex: $User->getCvs(";"); 
* Ex: $User->getCvs(",");
*/
$User->getCvs();

//Export Results
$User = new ModelJdb_Users();
$User->findByNome("Felipe");
/**
* $Delimiter is Optional 
* Ex: $User->getCvs(";"); 
* Ex: $User->getCvs(",");
*/
$User->getCvs();