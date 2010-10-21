<?php
/*
	Fonctions principales pour Génération Taraddicts
	Par Neurones67
	Licence GPLv3
	11/07/10
*/
// Démarrage de session
session_start();
function microtime_diff($a, $b) {
	list($a_dec, $a_sec) = explode(" ", $a);
	list($b_dec, $b_sec) = explode(" ", $b);
	return $b_sec - $a_sec + $b_dec - $a_dec;
}
// Définition des constantes
define('ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
define('FONCTIONS', ROOT.'fonctions/');
define('STYLES', ROOT.'styles/');
define('JS', ROOT.'js/');
define('TEMPLATES',STYLES.'templates/');
define('PARTIAL',TEMPLATES.'partial/');
define('PROOT',TEMPLATES.'root/');

require_once('password.php');
//Inclusion des différentes classes écrites
require_once('db.php');
require_once('utilisateurs.php'); 

$GLOBALS['object']=array();
// fonctions de base

// Demande d'objet commun
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
