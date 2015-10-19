<?php
require_once("_Setup.php");

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