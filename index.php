<?php 
	require_once('fonctions/fonctions.php');
	echo('<?xml version=\'1.0\' encoding="UTF-8" ?>'); ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Bienvenue sur le site !</title>
	<meta http-equiv="Content-Type" content="text/html ; charset=UTF-8" />
	<link rel="stylesheet" media="screen" type="text/css" href="styles/style.css" />
</head>
<body>
<!-- Début du site Internet -->
<div id="body">
<?php	
	include_once('partial/entete.php');
	include_once('partial/menuConnexion.php');
	include_once('partial/menuGauche.php');
	include_once('partial/menuDroite.php');
	include_once('partial/corps.php');
	include_once('partial/piedDePage.php');
?>	
</div>
</body>
</html>

