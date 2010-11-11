function Erase(id)
{
	if (document.getElementById(id) != null)
	{
		var monObjet = document.getElementById(id);
		monObjet.value = "";
	}
}
