<?php

// On souhaite creer un formulaire

/* On souhaite un label puis un champ de texte associé */
function creer_label_champ($label,$nom,$tailleChamp=null)
{
	$ligne="\t<dt><label for=\"".$nom."\" >".$label."</label></dt>\n";
	$ligne.="\t<dd><input id=\"".$nom."\" name=\"".$nom."\" type=\"text\" ";
	if(isset($tailleChamp))
		$ligne.="size=\"".$tailleChamp."\" ";
	
	$ligne.="/></dd>\n"; //on termine le input et on ferme toutes les balises
	echo $ligne;
}

//fonction pour déterminer si une variable est renseignée et non vide
function isDefined($val)
{
	$res = false;
	if(isset($val) && $val != "")
		$res = true;
	return $res;
}

/* On souhaite une selection */
/* tValeur est un tableau associatif value => nom affiché */
function creer_selection($tValeur,$multiple,$nomSelection,$taille)
{
	$ligne="<select ";
	if(isDefined($taille))
		$ligne.="size=\"".$taille."\" ";
	if(isDefined($multiple))
		$ligne.="multiple=\"".$multiple."\" ";
	if(isDefined($nomSelection))
		$ligne.="name=\"".$nomSelection."\" ";
	$ligne.=">\n";

	foreach($tValeur as $key => $value)
		$ligne.="\t<option value=\"".$key."\">".$value."</option>\n";
	
	//fermeture du select
	$ligne.="</select>\n";
	
	echo $ligne;
}
function creer_formulaire_inscription()
{
	echo "<form id=\"formulaire_inscription\" method=\"post\" action='' >";
	creer_label_champ("Pseudonyme","pseudo");
	creer_label_champ("Mod de passe","modDePasse");
	creer_label_champ("Confirmation du mot de passe","confirmationMotDePasse");
	creer_label_champ("E-mail","email");
	creer_label_champ("Confirmation de l'adresse e-mail","confirmationEmail");
	echo "</form>";
}
?>
