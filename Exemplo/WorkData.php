<?php
require_once("_Setup.php");
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
	$User = new ModelJdb_User();
	$User->Nome = "Felipe";
	$User->Sobrenome = "Smith";
	$User->Nascimento = "18/10/1995";
	$User->Sexo = "f";
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

// Verifica Erros
$Errors = $User->getErrors();
if (is_array($Errors)){
    print_r($Errors);
}	
	
//Update Data
	$Model = new ModelJdb_User();
	$Model->findOneByNome("Felipe");
	$Model->Nome = "Felipe Da Silva";
	$Model->save();
	
//Remove Data
	$Model = new ModelJdb_User();
	$Model->findOneByNome("Felipe");
	if ($Model instanceof ModelJdb_Users){
		$Model->remove(false); 
	}