<?php
require_once("_Setup.php");

//http://jsondb.inclouds.com.br/index.php/Page/exportar-para-cvs

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