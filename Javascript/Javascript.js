function Erase()
{
	if (!document.getElementById) 
	{
	return;	
	}
	var monObjet = document.getElementById("id_Identifiant");
	monObjet.value = "";
}
