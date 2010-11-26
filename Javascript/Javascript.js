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
                paragraphe.style.color="red";
                paragraphe.style.fontWeight="bold";
                paragraphe.appendChild(info);
	}
	else
	{
                //Ajout de l'information correct
                var info = document.createTextNode("Mot de passe correct");
                paragraphe.style.color="green";
                paragraphe.style.fontWeight="bold";
                paragraphe.appendChild(info);
	} 
}
function verificationEmail()
{
	var paragraphe = document.getElementById("erreurEmail");
	var old_contenu = paragraphe.firstChild;
        paragraphe.removeChild(old_contenu);
	if(document.getElementById('email').value != document.getElementById('email2').value)
	{
                //Ajout de l'information incorrect
                var info = document.createTextNode("Erreur: Email différents");
                paragraphe.style.color="red";
                paragraphe.style.fontWeight="bold";
                paragraphe.appendChild(info);
	}
	else
	{
                //Ajout de l'information correct
                var info = document.createTextNode("Email correct");
                paragraphe.style.color="green";
                paragraphe.style.fontWeight="bold";
                paragraphe.appendChild(info);
	} 
}
