<?php	
	include "loader/config_loader.php";
	include "model/simulateur.php";
	
	$config = new ConfigLoader();
	$simulateur = new Simulateur();
	$simulateur->load($_GET["uid"]);
	$simulateur->etapeSuivante($config->getProbabilite());
		
	echo "{ \"uid\" : \"".$simulateur->getUid()."\" , \"feux\" : ".json_encode($simulateur->getFeux()).", \"deja_brules\" : ".json_encode($simulateur->getDejaBrules())." }";