function erase(nam)
{
		document.id_form.id_Connexion.value = "";
}
function verificationMotDePasse()
{
	var paragraphe = document.getElementById("erreurMotDePasse");
	var old_contenu = paragraphe.firstChild;
        paragraphe.removeChild(old_contenu);
	paragraphe.style.color="red";
	if(document.getElementById('password').value != document.getElementById('password2').value)
	{
                //Ajout de l'information incorrect
                var info = document.createTextNode("Mots de passe différents");
	}
	else
	{
                //Ajout de l'information correct
		if(document.getElementById('password2').value == "")
		{
			 var info = document.createTextNode("Mots de passe différents");
		}
		else
		{
		        var info = document.createTextNode("Mot de passe correct");
		        paragraphe.style.color="green"
		}
	}
	paragraphe.appendChild(info); 
}
function verificationEmail()
{
	var paragraphe = document.getElementById("erreurEmail");
	var old_contenu = paragraphe.firstChild;
	var expr = new RegExp('^[-a-z0-9!#$%&\'*+/=?^_`{|}~]+(\.[-a-z0-9!#$%&\'*+/=?^_`{|}~]+)*@(([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){1,63}\.)+([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){2,63}$',"i");
	var old_contenu = paragraphe.firstChild;
        paragraphe.removeChild(old_contenu);
	paragraphe.style.color="red";
	if(!document.getElementById("email").value.match(expr))
	{
		var info = document.createTextNode("Adresse e-mail incorrecte")
	}
	else if(document.getElementById('email2').value != "")
	{
		if(document.getElementById('email').value != document.getElementById('email2').value)
		{
		        //Ajout de l'information incorrect
		        var info = document.createTextNode("Les deux adresses e-mail ne sont pas identiques");
			paragraphe.style.color="red";
		}
		else
		{
		        //Ajout de l'information correct
			var info = document.createTextNode("Adresse e-mail correcte");
		        paragraphe.style.color="green";
		} 
	}
	paragraphe.appendChild(info);
}

function cachevisible(id)
{
	var p = document.getElementById(id);
	if(p.style.display!='block')
	{
		p.style.display='block';
	}
	else
	{
		p.style.display='none';
	}
}
