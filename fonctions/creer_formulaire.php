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
	echo "<h2>Inscription</h2>\n";
	echo "<form id=\"formulaire_inscription\" method=\"post\" action='' >\n";

	# Ajout de fieldset afin de guider l'utilisateur
	echo "<fieldset>\n\t<legend>Les informations indispensables</legend>\n"; 

	creer_label_champ("Pseudonyme","login");
	creer_label_champ("Mot de passe","password");
	creer_label_champ("Confirmation du mot de passe","password2");
	creer_label_champ("E-mail","email");
	creer_label_champ("Confirmation de l'adresse e-mail","email2");

	# Ajout de l'envoi d'une image représentant l'utilisateur
	echo "</fieldset>\n";
	echo "<input type='reset' value='Annuler' />\t";	
	echo "<input type='submit' value='OK'>\n"; 
	
	echo "</form>\n";
}
function creer_formulaire_ajout_auteur()
{
	echo "<h2>Ajout d'un auteur</h2>\n";
	echo "<form id=\"formulaire_ajout_auteur\" method=\"post\" action='' >\n";
	echo "<fieldset>\n\t<legend>Les informations indispensables</legend>\n";

	creer_label_champ("Nom de l'auteur", "nomAuteur");
	creer_label_champ("Prénom de l'auteur", "prenomAuteur");
	echo "<dt><label for=\"jourNaissance\" >Date de naissance (JJ/MM/AAAA)</label></dt>\n";
	echo "\t<dd><input id='jourNaissance' name='jourNaissance' type='text' /> / ";
	echo "\t<input id='moisNaissance' name='moisNaissance' type='text' /> / ";
	echo "\t<input id='anneeNaissance' name='anneeNaissance' type='text' /></dd>\n";

	echo "</fieldset>\n";
	
	echo "<fieldset>\n\t<legend>Les informations complémentaires (facultatives)</legend>\n";

	# C'est ici qu'il manque l'upload d'image
	
	echo "<dt><label for='biographie'>Sa biographie</label></dt>\n";
	echo "<dd><textarea id='biographie' rows='5' cols='60'>Veuillez entrer la biographie de l'auteur</textarea></dd>\n";
	echo "</fieldset>\n";

	echo "<input type='reset' value='Annuler' />\t";	
	echo "<input type='submit' value='OK'>\n"; 

	echo "</form>\n";
}
function creer_formulaire_ajout_bd()
{
	echo "<h2>Ajout d'une Bande Dessinée</h2>\n";
	echo "<form id=\"formulaire_ajout_bd\" method=\"post\" action='' >\n";
	echo "\t<fieldset>\n\t\t<legend>Les informations indispensables</legend>\n";
	creer_label_champ("Nom de la Bande dessinée", "nomBD");

	echo "</fieldset>\n<fieldset>\n\t<legend>Les informations complémentaires (facultatives)</legend>\n";
	creer_label_champ("Nom de l'auteur", "nomAuteur");
	creer_label_champ("Prénom de l'auteur", "prenomAuteur");
	creer_label_champ("Numéro ISBN","noISBN");
	creer_label_champ("Numéro EAN13","noEAN13");
	echo "<dt><label for=\"jourPublication\" >Date de publication (JJ/MM/AAAA)</label></dt>\n";
	echo "\t<dd><input id='jourPublication' name='jourPublication' type='text' /> / ";
	echo "<input id='moisPublication' name='moisPublication' type='text' /> / ";
	echo "<input id='anneePublication' name='anneePublication' type='text' /></dd>\n";
	
	# C'est ici qu'il manque l'upload d'image

	echo "<dt><label for='synopsis'>Le synopsis</label></dt>\n";
	echo "<dd><textarea id='synopsis' rows='5' cols='60'>Veuillez entrer le synopsis de la Bande dessinée</textarea></dd>\n";
	echo "</fieldset>\n";

	echo "<input type='reset' value='Annuler' />\t";	
	echo "<input type='submit' value='OK'>\n"; 

	echo "</form>\n";
}
function creer_formulaire_recherche()
{
	echo "<h2>Recherche avancée</h2>\n";
	echo "<form id=\"formulaire_recherche_avancee\" method=\"post\" action='' >\n";
	echo "\t<fieldset>\n\t\t<legend>Les informations indispensables</legend>\n";
	echo "\t\tOù rechercher :\t Dans ma base : <input type='radio' name ='emplacement' value='baseutilisateur' /> 
		Dans la base globale : <input type='radio' value='baseglobale' />\n"
	creer_label_champ("Nom de la Bande dessinée", "nomBD");
	creer_label_champ("Nom de l'auteur", "nomAuteur");
	echo "\t</fieldset>\n";
	echo "\t<fieldset>\n\t\t<legend>Options avancées</legend>\n";
	echo "\t</fieldset>\n";

	echo "<input type='reset' value='Annuler' />\t";	
	echo "<input type='submit' value='OK'>\n"; 
	echo "</form>";
}
?>
