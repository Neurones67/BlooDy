<?php

// On souhaite creer un formulaire

/* On souhaite un label puis un champ de texte associé */
function creer_label_champ($label,$nom,$tailleChamp=null)
{
	$ligne="<dl><dt><label for=\"".$nom."\" >".$label."</label></dt><dd>";
	$ligne.="<input id=\"".$nom."\" name=\"".$nom."\" type=\"text\" ";
	if(isset($tailleChamp))
		$ligne.="size=\"".$tailleChamp."\" ";
	
	$ligne.="/></dd></dl>\n"; //on termine le input et on ferme toutes les balises
	echo $ligne;
}

/* On souhaite une selection */
/* tValeur est un tableau associatif value => nom affiché */
function creer_selection($tValeur,$multiple,$nomSelection,$taille)
{
	$ligne="<select ";
	if(isset($taille))
		$ligne.="size=\"".$taille."\" ";
	if(isset($multiple))
		$ligne.="multiple=\"".$multiple."\" ";
	if(isset($nomSelection))
		$ligne.="name=\"".$nomSelection."\" ";
	$ligne.=">\n";

	foreach($tValeur as $key => $value)
		$ligne.="<option value=\"".$key."\">".$value."</option>\n";
	
	//fermeture du select
	$ligne.="</select>\n";
	
	echo $ligne;
}

?>
