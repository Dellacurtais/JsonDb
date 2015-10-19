<?php
require_once("_Setup.php");

//FindBy
$User = new ModelJdb_Users();
$User->getRelative("Adress");
$Data = $User->findByNome("Felipe")->toArray();

echo "<pre>";
print_r($Data);
echo "</pre>";