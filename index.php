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
<!-- Début du site Internet -->
<div id="body">
	<div id="head">
		<img src="http://github.com/images/modules/header/logov3-hover.png" alt="image de rien du tout ." />
	</div>
	<div id="menuConnexion">
	<form method="POST" action="/connexion.php">
		<input id="id_Connexion" type="text" name="id_Connexion" />
		<input id="id_MotDePasse" type="password" name="id_MotDePasse" />
		<input id="btn_Connexion" type="submit" value="Connexion" />
		<a class="lien_MotDePassePerdu">Mot de passe perdu ?</a>
		<input id="id_RechercheRapide" type="text" name="id_RechercheRapide" />
		<input id="btn_RechercheRapide" type="submit" value="Rechercher" />
	</form>
	</div>
	<div id="menuGauche">
		<p>Voici le menu de gauche</p>
	</div>
	<div id="corps">
		<p>Voici le corps du site</p>
	</div>
	<div id="menuDroite">
		<p>Voici le menu de suggestions</p>
	</div>
</div>
<div id="foot">
</div>
</body>
</html>

