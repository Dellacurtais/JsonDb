<?php
###########################
########_Setup.php#########
###########################
###########################

// Inclue o Arquivo
require_once("../JsonDb/JsonDbJdb.php");
//Define o diretorio para os modelos
JsonDb_Core::getInstance()->setDir("../JsonDb/ModelJdb/");

###########################
####GenerateModel.php######
###########################
###########################
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



###############################################
##############WorkData.php#####################
###############################################

//After creating your model, you can insert, read, update, and remove data
//Model User Generate On File "GenerateModel.php"

# View Full Documentation On http://jsondb.inclouds.com.br/

# Insert Data - http://jsondb.inclouds.com.br/index.php/Page/inserir-dados
# Validation Values - http://jsondb.inclouds.com.br/index.php/Page/validacao
# Search Data - http://jsondb.inclouds.com.br/index.php/Page/buscar-dados
# Order Data - http://jsondb.inclouds.com.br/index.php/Page/ordenar-dados
# Update Data - http://jsondb.inclouds.com.br/index.php/Page/alterar-dados
# Remove Data - http://jsondb.inclouds.com.br/index.php/Page/remover-dados
# Others - http://jsondb.inclouds.com.br/index.php/Page/outras-funcoes

//Insert New Data
	$User = new ModelJdb_Users();
	$User->Nome = "Felipe";
	$User->Sobrenome = "Smith";
	$User->Nascimento = "18/10/1995";
	$User->Sexo = "m";
	$User->Email = "felipesmith@exemplo.com.br";
	$User->Password = sha1("123456");
	$User->save();
	
	// Check Errors
	$Errors = $User->getErrors();
	if (is_array($Errors)){
		print_r($Errors);
	}

//Inser New Data With Relative Model
	$User = new ModelJdb_Users();
	$User->getRelative("Adress");

	$User->Nome = "Felipe";
	$User->Sobrenome = "Smith";
	$User->Nascimento = "18/10/1995";
	$User->Sexo = "m";
	$User->Email = "felipesmith@exemplo.com.br";
	$User->Password = sha1("123456");
		$User->_Adress['Adress'] = "16?18?";
		$User->_Adress['City'] = "QD 34 LT 10";
		$User->_Adress['State'] = "Seu Pai";
		$User->_Adress['Country'] = "Meu Braço";
		$User->_Adress['ZipCode'] = "Quebrou";
	$User->save();
	
//Inser New Data With Relative Model (Many Data)
	$User = new ModelJdb_Users();
	$User->getRelative("Adress");

	$User->Nome = "Felipe";
	$User->Sobrenome = "Smith";
	$User->Nascimento = "18/10/1995";
	$User->Sexo = "m";
	$User->Email = "felipesmith@exemplo.com.br";
	$User->Password = sha1("123456");
		$User->_Adress[0]['Adress'] = "16?18?";
		$User->_Adress[0]['City'] = "QD 34 LT 10";
		$User->_Adress[0]['State'] = "Seu Pai";
		$User->_Adress[0]['Country'] = "Meu Braço";
		$User->_Adress[0]['ZipCode'] = "Quebrou";
		$User->_Adress[1]['Adress'] = "16?18?";
		$User->_Adress[1]['City'] = "QD 34 LT 10";
		$User->_Adress[1]['State'] = "Seu Pai";
		$User->_Adress[1]['Country'] = "Meu Braço";
		$User->_Adress[1]['ZipCode'] = "Quebrou";
	$User->save();	
	
//Update Data
	$Model = new ModelJdb_Users();
	$Model->findOneByNome("Felipe");
	$Model->Nome = "Felipe Da Silva";
	$Model->save();
	
//Remove Data
	$Model = new ModelJdb_Users();
	$Model->findOneByNome("Felipe");
	if ($Model instanceof ModelJdb_Users){
		$Model->remove(false); 
	}

//FindBy
$User = new ModelJdb_Users();
$User->getRelative("Adress");
$Data = $User->findByNome("Felipe")->toArray();
echo "<pre>";
print_r($Data);
echo "</pre>";


//http://jsondb.inclouds.com.br/index.php/Page/exportar-para-cvs

//////////////////////Export All

//$User = new ModelJdb_Users();

/**
* $Delimiter is Optional 
* Ex: $User->getCvs(";"); 
* Ex: $User->getCvs(",");
*/

//$User->getCvs();

//////////////////////Export Results

//$User = new ModelJdb_Users();
//$User->findByNome("Felipe");

/**
* $Delimiter is Optional 
* Ex: $User->getCvs(";"); 
* Ex: $User->getCvs(",");
*/

//$User->getCvs();