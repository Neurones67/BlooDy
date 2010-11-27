<?php

# Cette page sert à faire les formulaires et tester les scripts php qui s'y rapportent

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

	<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Bienvenue sur le site !</title>
	<meta http-equiv="Content-Type" content="text/html ; charset=UTF-8" />
	<link rel="stylesheet" media="screen" type="text/css" href="LeStyle.css" />
</head>
<body>

<?php 
	include_once('fonctions/creer_formulaire.php');
	
	// Test de création de formulaire d'inscription
	creer_formulaire_inscription();

	echo "<hr />";
	// Test de création de formulaire d'ajout d'un auteur
	creer_formulaire_ajout_auteur();

	echo "<hr />";
	// Test de création de formulaire d'ajout d'une Bande dessinée
	creer_formulaire_ajout_bd();

	echo "<hr />";
	// Test de création de formulaire de recherche avancée
	creer_formulaire_recherche();
?>
</body>
</html>

