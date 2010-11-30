<?php

class Affichage
{
	// On passe à cette fonction le tableau contenant les informations utiles à afficher de façon très compacte
	// On passe son identifiant unique (t[0]), son nom, son prénom, sa date de naissance
	public static function affichage_tcompact_auteurs()
	{
		$livre = new Livre();
		$tAut = "A CHANGER !!!!!!!!!"; // C'est vrai.
		// Tout d'abord, on donne les titres de chaque colonne
		echo "<table id='auteurs'><tr><th>Nom de l'auteur</th><th>Prénom de l'auteur</th><th>Date de naissance</th></tr>\n";
		for($i=0; $i < count($tAut) ; $i++)
		{
			echo "<tr>"; // début de la ligne
			echo "<td><a href='/affichage_complet_auteurs.html?id=".$tAut[0]."' >".$tAut[1]."</a>\n";
			echo "</td><td>".$tAut[2]."</td><td>".$tAut[3]."</td>\n";
			echo "</tr>\n"; // fin de la ligne
		}
		echo "</table>\n";
	}

	// Même chose mais avec les collections, on passe l'identifiant unique, le nom de collection, 
	// Le nom de l'auteur, le prénom de l'auteur, et le nom de l'éditeur
	public static function affichage_tcompact_collections()
	{
		$tCol = "A CHANGER !!!!!!!!!!!!!!!!!!!"; // Oui faut pas oublier.
		// Tout d'abord, on donne les titres de chaque colonne
		echo "<table id='collections'><tr><th>Nom de la collection</th><th>Nom de l'auteur</th>\n";
		echo "<th>Prénom de l'auteur</th><th>Nom de l'éditeur</th></tr>\n";

		for($i=0; $i < count($tCol) ; $i++)
		{
			echo "<tr>"; // début de la ligne
			echo "<td><a href='/affichage_complet_collections.html?id=".$tCol[0]."' >".$tCol[1]."</a></td>\n";
			echo "<td>".$tCol[2]."</td><td>".$tCol[3]."</td><td>".$tCol[4]."</td>\n";
			echo "</tr>\n"; // fin de la ligne
		}
		echo "</table>\n";
	}

	// Même chose mais pour l'affichage des BDs :)
	// On passe l'id, le nom de la BD, le nom et prénom de l'auteur, la date de publication, le nom de l'éditeur et le numéro ISBN et d'ean13 
	public static function affichage_tcompact_bds()
	{
		$tBD = "A CHANGER !!!!!!!!!!!!!!!!!!!"; // Oui faut pas oublier.
		// Tout d'abord, on donne les titres de chaque colonne
		echo "<table id='bds'><tr><th>Nom de la Bande Dessinée</th><th>Nom de l'auteur</th><th>Prénom de l'auteur</th>\n";
		echo "<th>Nom de l'éditeur</th><th>ISBN</th><th>EAN13</th></tr>\n";

		for($i=0; $i < count($tBD) ; $i++)
		{
			echo "<tr>"; // début de la ligne
			echo "<td><a href='/affichage_complet_bds.html?id=".$tBD[0]."' >".$tBD[1]."</a></td>\n";
	
			// Boucle pour mettre le reste des informations dans le tableau	
			for($k=2; $k <= 6 ; $k++)
				echo "<td>".$tBD[$k]."</td>";

			echo "</tr>\n"; // fin de la ligne
		}
		echo "</table>\n";
	}
	public static function consultation_BD()
	{
		$livre = new Livre();
		$tBD = $livre->listBD();
		// Les titres de chaque colonne

		echo "<table id='bds'><tr><th>Genre</th><th>Nom de la Bande Dessinée</th><th>Nom de l'auteur</th><th>Prénom de l'auteur</th>\n";
		echo "<th>Nom de l'éditeur</th><th>ISBN</th><th>EAN13</th></tr>\n";

		for($i=0; $i < count($tBD) ; $i++)
		{
			echo "<tr>"; 
			echo "<td><a href='/affichage_complet_bds.html?id=".$tBD['l.lid']."' >".$tBD['l.nom']."</a></td>\n";
			echo "<td>".$tBD['a.anom']."</td><td><a href='/affichage_complet_auteurs.html?id=".$tBD['a.aid']."' />"./*$tBD['a.aprenom'].*/"</td><td>".$tBD['e.enom']."</td><td>".$tBD['l.isbn']."</td>\n";
			echo "<td>".$tBD['l.ean13']."</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}
?>
