<?php
require("_Setup.php");
# View Full Documentation On http://jsondb.inclouds.com.br/index.php/Page/jsondb-gerar-modelos

/**
*
* JsonDb_Create Class to create model
* @Arg Name of Model
*/
$Create = new JsonDb_Create("Users");

/**
*
* @method setColun Define the columns of your document
* @Args
*   $Name - Name of Column, 
*   $type - Type Data, 
*   $default - Default value if not set to save or set a Array for single or multiple options
*   $description - Column Description
* $Create->setColun($name,$type,$sizeVal = null,$default = null,$description = null);
*/
$Create->setColun('Nome','varchar',20);
$Create->setColun('Sobrenome','varchar',20);
$Create->setColun('Nascimento','date');
$Create->setColun('Sexo','singleOption',array("--","f","m"),"--");
$Create->setColun('Email','varchar',50);
$Create->setColun('Password','varchar',50);

/*
* @method setMany Define the relational model
* @Args
*   $model - Name relational Model, 
*   $key1 - Column of the Current Model, 
*   $key2 - Column of the Relational Model
* $Create->setMany($model,$key1,$key2);   
*/
$Create->setMany("Adress","_Id","UserId");

//Save Model
$Create->save();

//Create Relational Model
$Create = new JsonDb_Create("Adress");
$Create->setColun('Adress','varchar');
$Create->setColun('City','varchar');
$Create->setColun('State','varchar');
$Create->setColun('Country',"varchar");
$Create->setColun('ZipCode','varchar');
$Create->setColun('UserId','varchar');
$Create->save();