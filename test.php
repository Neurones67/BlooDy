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
	creer_formulaire_inscription();
?>
</body>
</html>
