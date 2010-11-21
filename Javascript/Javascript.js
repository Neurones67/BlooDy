function Erase(nam)
{
		document.id_form.id_Connexion.value = "";
}
function VerificationMotDePasse()
{
	
	if(document.formulaire_inscription.motDePasse != document.formulaire_inscription.confirmationMotDePasse)
	{
		var paragraphe = document.getElementById("erreurMotDePasse");
                var old_contenu = paragraphe.firstChild;
                paragraphe.removeChild(old_contenu);
                //Ajout de l'information incorrect
                var info = document.createTextNode("Erreur pas le même mot de passe");
                paragraphe.appendChild(info);
	}
	else
	{
		var paragraphe = document.getElementById("erreurMotDePasse");
		var old_contenu = paragraphe.firstChild;
                paragraphe.removeChild(old_contenu);
                //Ajout de l'information correct
                var info = document.createTextNode("Mot de passe correct");
                paragraphe.appendChild(info);
	} 
}
