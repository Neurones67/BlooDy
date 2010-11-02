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
?>
	
	<div id="menuConnexion">
	<form method="POST" action="/connexion.php">
		<input id="id_Identifiant" type="text" name="id_Connexion" value="Identifiant" />
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
	<div id="menuDroite">
		<p>Voici le menu de suggestions</p>
	</div>
	<div id="corps">
		<p>Voici le corps du site</p>
		<p>Ajout de tout un tas de blabla totalement inutile. Bla.<br />
		La République du Yémen fut déclarée le 22 mai 1990. Ali Abdullah Saleh devint le président du Yémen et Ali Salim al-Beidh le premier ministre du Yémen. Une période transitoire de 30 mois fut fixée afin de fusionner les deux systèmes politiques et économiques. Un conseil présidentiel fut élu par les parlements respectifs des deux anciens pays. Un nouveau parlement, commun aux deux pays, le Parlement provisoire unifié, fut fondé. Il était composé de 159 membres originaires du Nord, de 111 membres originaires du Sud et de 31 membres indépendants nommés par le président du conseil.</p>
	</div>
	<div id="foot">
		<p>Rien de spécial</p>
	</div>
</div>
</body>
</html>

