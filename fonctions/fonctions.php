<?php
/*
	Fonctions principales pour Bloody
	Par Marc
	Licence GPLv3
	11/07/10
*/
// Démarrage de session
session_start();

// Permet de calculer la différence entre deux temps en ms.
function microtime_diff($a, $b) {
	list($a_dec, $a_sec) = explode(" ", $a);
	list($b_dec, $b_sec) = explode(" ", $b);
	return $b_sec - $a_sec + $b_dec - $a_dec;
}
// Définition des constantes
define('ROOT', $_SERVER['DOCUMENT_ROOT'].'/'); // Répertoire racine du site
define('FONCTIONS', ROOT.'fonctions/'); // Répertoire où se trouvent les fonctions
define('STYLES', ROOT.'styles/'); // Répertoire où se trouvent les documents de style (css/images)
define('JS', ROOT.'js/'); // Répertoire où se trouvent les scripts Javascript
define('PARTIAL',STYLES.'partial/'); // Répertoire où se trouvent les templates HTML partiels (juste une partie d'une page)
define('PROOT',STYLES.'root/'); // Répertoire où se trouvent les template HTML des pages à la racine du site

// On inclu les informations d'identifications à la base de donnée
include('password.php');
//Inclusion des différentes classes écrites
require_once('templates.php');
require_once('param.php');
require_once('db.php');
require_once('utilisateurs.php'); 
require_once('livre.php'); 

// Initialisation de la variable globale qui va stocker les différents objets (pour un accès direct depuis n'importe où)
$GLOBALS['object']=array();
// fonctions de base

// Permet de demander l'instance d'une classe, si déjà chargée, la retourne, sinon, la créer, la stoque, et la retourne
function requestObject($objectName)
{
	if(!isset($GLOBALS['object'][$objectName]))
	{
		$GLOBALS['object'][$objectName]=new $objectName;
	}
	return $GLOBALS['object'][$objectName];
}

// Est-ce que l'objet demandé est déjà enregistré ?
function isRegisteredObject($objectName)
{
	return isset($GLOBALS['object'][$objectName]);
}

// Enregistre l'objet demandé (écrase l'objet si déjà enregistré)
function registerObject($objectName)
{
	$GLOBALS['object'][$objectName] = new $objectName;
	return $GLOBALS['object'][$objectName];
}

// Enregistre un objet avec un paramètre (écrase l'objet si déjà enregistré)
function registerObjectP($objectName,$param)
{
	$GLOBALS['object'][$objectName]=new $objectName($param);
	return $GLOBALS['object'][$objectName];
}

// Regénère la superglobale $_GET (écrasé par la réécriture d'url)
function gen_get()
{
	global $mysql;
	if($query=strstr($_SERVER["REQUEST_URI"],'html?'))
	{
		$query=str_replace('html?','',$query);
		$query=explode('&',$query);
		foreach($query as $data)
		{
			if($value=strstr($data,'='))
			{
				$key=str_replace($value,'',$data);
				$value=str_replace('=','',$value);
				$var=mysqli_real_escape_string($mysql,$value);
				$_GET[$key]=$var;

			}
		}
	}
}

// Connexion à la base de données
$mysql=new DB($host,$username,$password,$base);
// Enregistrement de l'objet MySQL dans les variables globales (accessibles alors aux objets)
$GLOBALS['object']['MySQL']=$mysql;
// Initialise l'utilisateur (même utilisateur non connecté anonyme)
registerObject('Utilisateurs');
