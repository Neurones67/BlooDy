function erase(nam)
{
		document.id_form.id_Connexion.value = "";
}
function verificationMotDePasse()
{
	var paragraphe = document.getElementById("erreurMotDePasse");
	var old_contenu = paragraphe.firstChild;
        paragraphe.removeChild(old_contenu);
	if(document.getElementById('password').value != document.getElementById('password2').value)
	{
                //Ajout de l'information incorrect
                var info = document.createTextNode("Erreur: Mots de passe différents");
                paragraphe.appendChild(info);
	}
	else
	{
                //Ajout de l'information correct
                var info = document.createTextNode("Mot de passe correct");
                paragraphe.appendChild(info);
	} 
}
