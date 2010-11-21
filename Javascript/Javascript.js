function Erase(nam)
{
		document.id_form.id_Connexion.value = "";
}
function VerificationMotDePasse
{
	var paragraphe = document.getElementById("formulaire_inscription.confirmationMotDePasse.erreurMotDePasse");
	if(document.formulaire_inscription.motDePasse != document.formulaire_inscription.confirmationMotDePasse)
	{
                var old_contenu = paragraphe.firstChild;
                paragraphe.removeChild(old_contenu);
                //Ajout de l'information incorrect
                var info = document.createTextNode("Erreur pas le même mot de passe");
                paragraphe.appendChild(info);
	}
	else
	{
		var old_contenu = paragraphe.firstChild;
                paragraphe.removeChild(old_contenu);
                //Ajout de l'information incorrect
                var info = document.createTextNode("Mot de passe correct");
                paragraphe.appendChild(info);
	} 
}
