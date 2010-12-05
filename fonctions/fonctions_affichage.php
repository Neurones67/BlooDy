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
			echo "<td><a href='/livre-".$tBD[0].".html' >".$tBD[1]."</a></td>\n";
	
			// Boucle pour mettre le reste des informations dans le tableau	
			for($k=2; $k <= 6 ; $k++)
				echo "<td>".$tBD[$k]."</td>";

			echo "</tr>\n"; // fin de la ligne
		}
		echo "</table>\n";
	}
	public function consulter_sa_base()
	{
		$user=requestObject('Utilisateurs');
		if(!$user->estConnecte())
		{
			return;
		}
		$livre =requestObject('Livre');
		$tBDs = $livre->listBDutil();
		// Les titres de chaque colonne

		$res = "";
		$res .= "<table id='bds'><tr><th>Genre</th><th>Nom de la Bande Dessinée</th><th>Nom de l'auteur</th><th>Prénom de l'auteur</th>\n";
		$res .= "<th>Nom de l'éditeur</th><th>ISBN</th><th>EAN13</th>";
		$res .= "<th>Supprimer de ma collection</th>";
		$res .= "</tr>\n";

		foreach($tBDs as $tBD)
		{
			$res .= "<tr>"; 
			$res .= "<td><img src='/images/genre_'".$tBD['gnom']."'.png alt='image_genre' /></td>";
			$res .= "<td><a href='/livre-".$tBD['lid'].".html' >".$tBD['nom']."</a></td>\n";
			$res .= "<td><a href='/auteur-" . $tBD['aid'] . ".html' />" . $tBD['anom'] . "</a></td><td>". $tBD['aprenom'] . "</a></td><td>" . $tBD['enom']."</td><td>" . $tBD['isbn'] . "</td>\n";
			$res .= "<td>".$tBD['ean13']."</td>\n";
			$res .= "<td><input type='checkbox' name='BD[]' value='" . $tBD['lid'] . "' /></td>";
			$res .= "</tr>\n";
		}
		$res .= "</table>\n";

		return $res;
	}
	public function consultation_bd()
	{
		$livre =requestObject('Livre');
		$tBDs = $livre->listBD();
		$user=requestObject('Utilisateurs');
		// Les titres de chaque colonne

		$res = "";
		$res .= "<table id='bds'><tr><th>Genre</th><th>Nom de la Bande Dessinée</th><th>Nom de l'auteur</th><th>Prénom de l'auteur</th>\n";
		$res .= "<th>Nom de l'éditeur</th><th>ISBN</th><th>EAN13</th>";
		if($user->estConnecte())
			$res .= "<th>Ajouter à ma collection</th>";
		$res .= "</tr>\n";

		foreach($tBDs as $tBD)
		{
			$res .= "<tr>"; 
			$res .= "<td><img src='/images/genre_'".$tBD['gnom']."'.png alt='image_genre' /></td>";
			$res .= "<td><a href='/livre-".$tBD['lid'].".html' >".$tBD['nom']."</a></td>\n";
			$res .= "<td><a href='/auteur-" . $tBD['aid'] . ".html' />" . $tBD['anom'] . "</a></td><td>". $tBD['aprenom'] . "</a></td><td>" . $tBD['enom']."</td><td>" . $tBD['isbn'] . "</td>\n";
			$res .= "<td>".$tBD['ean13']."</td>\n";
			
			if($user->estConnecte())
			{
				if($tBD['etat']<1) // Si on a pas la BD
				{
					$res .= "<td><input type='checkbox' name='BD[]' value='" . $tBD['lid'] . "' /></td>";
				}
				else
				{
					$res .= "<td>;)</td>";
				}
			}

			$res .= "</tr>\n";
		}
		$res .= "</table>\n";

		return $res;
	}
}
?>
