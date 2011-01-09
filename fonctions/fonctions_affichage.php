<?php
class Affichage
{
	public function consulter_sa_base()
	{
		$livre = requestObject('Livre');
		$affichage = requestObject('Affichage');
		$user = requestObject('Utilisateurs');
		if(!$user->estConnecte())
		{
			return;
		}
		$tBDs = $livre->listBDutil();
		// Les titres de chaque colonne

		if($affichage->tableauEstVide($tBDs))
		{
			return "<p>Vous n'avez pas encore de bande dessinée, je vous conseille de consulter notre base de donnée ! :)</p>\n";
		}

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
					$res .= "<td>X</td>";
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
	public function creerLiensLettres()
	{
		$nomPage = $_SERVER['REDIRECT_URL'];
		$res = "<p>";
		$tCar = array("0","1","2","3","4","5","6","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		foreach($tCar as $lettre)
		{
			if(isset($_GET['lettre']) && $_GET['lettre'] != $lettre)
			{
				$res .= "<a href='" . $nomPage . "?lettre=$lettre&page=1'>$lettre</a>";
			}
			else
			{
				$res .= $lettre;
			}

			if($lettre != "z")
				$res .= " | "; // Pour faire joli
		}
		$res .= "</p>";
		return $res;
	}
	public function recherche_series($tSeries)
	{
			$res = "<h2>Les séries</h2>\n";
	
			// Les Séries
			$res .= "<table id='series' ><tr><th>Nom de la série</th></tr>\n";
	
			foreach($tSeries as $tS)
				$res .= "<tr><td>" . $tS['snom'] . "</td></tr>\n";
			
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
						$res .= "<td>X</td>";
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
			$affichage = requestObject('Affichage');
		
			if(isset($_POST['c_auteurs']))
			{
				$tAuteurs = $auteur->recherche($motclef);
				if($affichage->tableauEstVide($tAuteurs))
				{
					$res .= "<h2>Les auteurs</h2>\n\t<p>Pas d'auteurs correspondant</p>\n";
				}
				else
				{
					$res .= $affichage->recherche_auteurs($tAuteurs);
				}
			}
			if(isset($_POST['c_series']))
			{
				$tSeries = $serie->recherche($motclef);
				if($affichage->tableauEstVide($tSeries))
				{
					$res .= "<h2>Les Séries</h2>\n\t<p>Pas de série correspondant à ce terme</p>\n";
				}
				else
				{
					$res .= $affichage->recherche_series($tSeries);
				}
			}
			if(isset($_POST['c_bd']))
			{	
				$tBDs = $livre->recherche($motclef);
				if($affichage->tableauEstVide($tBDs))
				{
					$res .= "<h2>Les bandes dessinées</h2>\n\t<p>Pas de BD correspondant à ce terme</p>\n";
				}
				else
				{
					$res .= $affichage->recherche_bds($tBDs, $user);
			
					if($user->estConnecte()) // Si l'utilisateur est connecté alors il peut ajouter des BDs
					{
						$res .= "<input type='reset' value='Annuler' />";
						$res .= "<input type='submit' value='Ajouter' />";
					}
				}
			}
		}

		return $res;
	}
	public function afficherUtilisateurs()
	{
		$user = requestObject('Utilisateurs');
		$affichage = requestObject('Affichage');
		$res = "";
		$utilisateurs = $user->listeUtilisateurs();
		if($affichage->tableauEstVide($utilisateurs))
		{
			$res .= "<h2>Les utilisateurs</h2>\n\t<p>Il n'y a pas d'utilisatuers ici ! Vous n'existez même pas ! oups?</p>";
		}
		else
		{
			$res .= $affichage->recherche_amis($utilisateurs, $user);
		}
		return $res;
	}
	public function tableauEstVide($t)
	{
		$i = 0;
		foreach($t as $truc)
		{
			$i++;
		}
		return $i == 0;
	}
	public function recherche_amis($tamis, $user)
	{
		$image = requestObject('Image');

		$res = "<table id='amis'><tr><th>Avatar</th><th>Pseudo</th><th>Date d'inscription</th><th>État</th>\n";
		if($user->estConnecte())
			$res .= "<th>Ajouter à mes amis</th>";
		$res .= "</tr>\n";
	
		foreach($tamis as $tami)
		{
			if(!empty($tami['avatar']))
				$adresseImage = $image->image_redim($tami['avatar'], 32, 32);
			else
				$adresseImage = '/avatars/ANONYME.JPG';

			$res .= "<tr>"; 
			$res .= "<td><img src='$adresseImage' alt='avatar de " . $tami['pseudo'] . "' />\n"; 
			$res .= "<td>" . $tami['pseudo'] . "</td><td>" . date("d.m.y \à H\hm",$tami['dinscription']) . "</td>\n";
			
			if($tami['uetat'] == 0)
				$res .= "<td>Connecté</td>\n";
			else
				$res .= "<td>Non connecté</td>\n";

			if($user->estConnecte())
			{
				if(!empty($tami['date_ajout'])) // Si la personne ne fait pas encore partie des amis
					$res .= "<td><input type='checkbox' name='amis[]' value='" . $tami['uid'] . "' /></td>";
				else
					$res .= "<td>Vous possédez déjà cette personne</td>";
			}
				$res .= "</tr>\n";
		}
		$res .= "</table>\n";
			
		if($user->estConnecte()) // Si l'utilisateur est connecté alors il peut ajouter des amis
		{
			$res .= "<input type='reset' value='Annuler' />";
			$res .= "<input type='submit' value='Ajouter' />";
		}
		return $res;
	}
	public function affichage_compact_bd($tBD)
	{
		$image = requestObject('Image');

		$res = "";
		if(empty($tBD))
			$res .= "<p>Vous n'avez rien à afficher</p>";
		else
		{
			foreach($tBD as $BD)
			{
				$adresseImage = $image->image_redim($BD['couverture'], 100, 100);
				$res .= "<div class='affichage_compact'>\n";
				$res .= "\t<p>" . $BD['nom'] . "</p>\n";
				$res .= "\t<img src='$adresseImage' alt='Image de " . $BD['nom'] . "' />\n";
				$res .= "\t<p>Genre :" . $BD['gnom'] . "</p>\n";
				$res .= "\t<p>Auteur :" . $BD['anom'] . "</p>\n";
				$res .= "</div>";
			}
		}
		return $res;
	}
}
?>
