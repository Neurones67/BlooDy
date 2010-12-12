<?php
class Affichage
{
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
			$res .= "<td><img src='/images/genre_".$tBD['gnom'].".png' alt='image_genre' /></td>";
			$res .= "<td><a href='/livre-".$tBD['lid'].".html' >".$tBD['nom']."</a></td>\n";
			$res .= "<td><a href='/auteur-" . $tBD['aid'] . ".html' />" . $tBD['anom'] . "</a></td><td>". $tBD['aprenom'] . "</a></td><td>" . $tBD['enom']."</td><td>" . $tBD['isbn'] . "</td>\n";
			$res .= "<td>".$tBD['ean13']."</td>\n";
			$res .= "<td><input type='checkbox' name='BD[]' value='" . $tBD['lid'] . "' /></td>";
			$res .= "</tr>\n";
		}
		$res .= "</table>\n";
				
		if($user->estConnecte())
		{
			$res .= "<input type='reset' value='Annuler' />";
			$res .= "<input type='submit' value='Supprimer' />";
		}
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
			$res .= "<td><img src='/images/genre_".$tBD['gnom'].".png' alt='image_genre' /></td>";
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

		if($user->estConnecte())
		{
			$res .= "<input type='reset' value='Annuler' />";
			$res .= "<input type='submit' value='Ajouter' />";
		}
		return $res;
	}
	public function recherche_auteurs($tAuteurs)
	{
			// Les auteurs
			$res = "<h2>Les Auteurs</h2>\n";
			$res .= "<table id='auteurs'><tr><th>Nom de l'auteur</th><th>Prénom de l'auteur</th><th>Date de naissance</th></tr>\n";
	
			foreach($tAuteurs as $tAut)
			{
				$res .= "<tr>"; 
				$res .= "<td><a href='/auteur-".$tAut['aid'].".html' >".$tAut['anom']."</a></td>\n";
				$res .= "<td><a href='/auteur-" . $tAut['aid'] . ".html' />" . $tAut['aprenom'] . "</a></td><td>". $tAut['adnaissance'] . "</td>\n";
				$res .= "</tr>\n";
			}
			$res .= "</table>\n";

			return $res;
	}
	public function recherche_series($tSeries)
	{
			$res = "<h2>Les séries</h2>\n";
	
			// Les Séries
			$res .= "<table id='series' ><tr><th>Nom de la série</th></tr>\n";
	
			foreach($tSeries as $tS)
				$res .= "<tr><td>" . $tS['nom'] . "</td></tr>\n";
			
			$res .= "</table>\n";

			return $res;
	}
	public function recherche_bds($tBDs, $user)
	{
			// Les Bandes Dessinées 
			$res = "<h2>Les bandes dessinées</h2>\n";
	
			$res .= "<table id='bds'><tr><th>Genre</th><th>Nom de la Bande Dessinée</th><th>Nom de l'auteur</th><th>Prénom de l'auteur</th>\n";
			$res .= "<th>Nom de l'éditeur</th><th>ISBN</th><th>EAN13</th>";
			if($user->estConnecte())
				$res .= "<th>Ajouter à ma collection</th>";
			$res .= "</tr>\n";
	
			foreach($tBDs as $tBD)
			{
				$res .= "<tr>"; 
				$res .= "<td><img src='/images/genre_".$tBD['gnom'].".png' alt='image_genre' /></td>";
			$res .= "<td><a href='/livre-".$tBD['lid'].".html' >".$tBD['nom']."</a></td>\n";
				$res .= "<td><a href='/auteur-" . $tBD['aid'] . ".html' />" . $tBD['anom'] . "</a></td><td>". $tBD['aprenom'] . "</a></td><td>" . $tBD['enom']."</td><td>" . $tBD['isbn'] . "</td>\n";
				$res .= "<td>".$tBD['ean13']."</td>\n";
				
				if($user->estConnecte())
				{
					if($tBD['etat']<1) // Si on a pas la BD
						$res .= "<td><input type='checkbox' name='BD[]' value='" . $tBD['lid'] . "' /></td>";
					else
						$res .= "<td>;)</td>";
				}
	
				$res .= "</tr>\n";
			}
			$res .= "</table>\n";
			
			return $res;
	}
	public function resultat_recherche()
	{
		$res = "";
		if(isset($_POST['terme']) and !empty($_POST['terme']))
		{
		
			$motclef = $_POST['terme'];

			$user = requestObject('Utilisateurs');
			$livre = requestObject('Livre');
			$serie = requestObject('Serie');
			$auteur = requestObject('Auteur');
		
			if(isset($_POST['c_auteurs']))
			{
				$tAuteurs = $auteur->recherche($motclef);
				$res = this->recherche_auteurs($tAuteurs);
			}
			if(isset($_POST['c_series']))
			{
				$tSeries = $serie->recherche($motclef);
				$res .= this->recherche_series($tSeries);
			}
			if(isset($_POST['c_bd']))
			{	
				$tBDs = $livre->recherche($motclef);
				$res .= this->recherche_bds($tBDs, $user);
			
				if($user->estConnecte()) // Si l'utilisateur est connecté alors il peut ajouter des BDs
				{
					$res .= "<input type='reset' value='Annuler' />";
					$res .= "<input type='submit' value='Ajouter' />";
				}
			}
		}

		return $res;
	}
}
?>
