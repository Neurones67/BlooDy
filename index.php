<?php 
	require_once('fonctions/fonctions.php');
	echo('<?xml version=\'1.0\' encoding="UTF-8" ?>'); ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Bienvenue sur le site !</title>
	<meta http-equiv="Content-Type" content="text/html ; charset=UTF-8" />
	<link rel="stylesheet" media="screen" type="text/css" href="LeStyle.css" />
</head>
<body>
<-- Début du site internet -->
<div id="body">
	<div id="head">
		<img src="http://github.com/images/modules/header/logov3-hover.png" alt="image de rien du tout ." />
	</div>
	<div id="menuConnexion">
		<input id="id_Connexion" type="text" /><input id="id_MotDePasse" type="password" />
		<input type="submit" id="btn_Connexion" value="Connexion" />
		<a class="lien_MotDePassePerdu">Mot de passe perdu ?</a>
	</div>
	<div id="menuGauche">
	</div>
	<div id="corps">
	</div>
	<div id="menuDroite">
	</div>
</div>
<div id="foot">
</div>
</body>
</html>

