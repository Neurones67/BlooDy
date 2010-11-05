<?php
/*
	Fichier d'appel de toutes les pages du site
	Par Marc
	Licence GPLv3
	11/07/10
*/
// Début du calcul du temps de chargement
$debut = microtime();

// Appel de tous les éléments fondamentaux du site
require_once('fonctions/fonctions.php');

if(isset($_GET['mode']) and $_GET['mode']=='404')
{
	if(strstr($_SERVER['REQUEST_URI'],'.html') or preg_match('/\/$/is',$_SERVER['REQUEST_URI']))
	{
		$page=str_replace('/','',$_SERVER['REQUEST_URI']);
		$page=str_replace('.html','',$page);
		$_GET['page']=$page;
	}
	else
	{
		$_GET['page']='404';
	}
}

if(!isset($_GET['page']))
{
	$_GET['page']='index';
}
$page=$_GET['page'];
unset($_GET['page']);
gen_get();
$template=RequestObject('Templates');
echo $template->RequestTemplate($page);
if(isset($_GET['debug']))
{
	echo 'Page générée en '.microtime_diff($debut, microtime()).' secondes, avec '.requestObject('MySQL')->count().' requêtes SQL';
}
?>
