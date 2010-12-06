<?php
/*
	Classe de gestion d'un livre (ou album)
	Pour BlooDy
	Licence GPL
	22/10/10
*/
class Livre
{
	// Déclaration des attributs
	private $lid;
	private $nom;
	private $isbn;
	private $ean13;
	private $date_publication;
	private $lvalide;
	private $description;
	private $auteurn;
	private $auteurp;
	private $aid;
	private $serie;
	private $ajuser;
	private $editeur;
	private $ajdate;
	private $genre;
	private $genreid;
	private $couverture;

	// Besoin d'une connexion MySQL
	private $mysql;
	
	public function __construct($lid=0)
	{
		$this->mysql=requestObject('MySQL');
		if($lid!=0)
		{
			if(!$this->initData($lid)) // On initialise les attributs
			{
				// Si il n'existe pas
				$this->lvalide=-1; // Permet de dire que le livre n'existe pas
			}
		}
	}
	public function __destruct()
	{
		unset($this->nom);
		unset($this->lid);
		unset($this->ean13);
		unset($this->date_publication);
		unset($this->lvalide);
		unset($this->description);
		unset($this->aid);
		unset($this->auteurn);
		unset($this->auteurp);
		unset($this->editeur);
		unset($this->ajuser);
		unset($this->ajdate);
		unset($this->serie);
		unset($this->genre);
		unset($this->genreid);
		unset($this->couverture);
	}
	public function getValide()
	{
		return $this->lvalide>0;
	}
	private function initData($lid)
	{
		// Initialise les attributs du livre avec le lid donné à partir de la base de donnée
		$lid=intval($lid);
		$sql='SELECT l.lid,l.nom,l.isbn,l.ean13,l.date_publication,l.lvalide,l.description,l.couverture,a.aid,a.anom, a.aprenom,s.snom,g.gnom,g.gid,e.enom,ajdate,u.pseudo FROM livres l JOIN auteurs a ON l.aid=a.aid LEFT JOIN series s ON l.serie=s.sid LEFT JOIN genre g ON l.genre=g.gid LEFT JOIN editeurs e ON e.eid=l.editeur LEFT JOIN utilisateurs u ON l.ajuid=u.uid WHERE lid='.$lid;
		$req=$this->mysql->query($sql);
		if($data=$req->fetch_object())
		{
			$this->remplirAttributs($data);
			return true;
		}
		else
		{
			return false;
		}
	}
	// Rempli les attributs de l'objet avec le résultat d'une requête SQL
	private function remplirAttributs($data)
	{
		$this->lid=$data->lid;
		$this->nom=$data->nom;
		$this->isbn=$data->isbn;
		$this->ean13=$data->ean13;
		$this->date_publication=$data->date_publication;
		$this->description=$data->description;
		$this->lvalide=$data->lvalide;
		$this->auteurn=$data->anom;
		$this->auteurp=$data->aprenom;
		$this->genre=$data->gnom;
		$this->ajuser=$data->pseudo;
		$this->ajdate=$data->ajdate;
		$this->editeur=$data->enom;
		$this->serie=$data->snom;
		$this->genreid=$data->gid;
		$this->aid=$data->aid;
		$this->couverture=$data->couverture;
	}
	// Ajoute un livre à la base de données avec l'ID de l'auteur et l'ID de la série (peut être vide éventuellement)
	private function ajoutLivre($nom,$isbn,$ean13,$date_publication,$description,$aid,$sid,$uid,$genre)
	{
		// Protection des variables
		$nom=$this->mysql->real_escape_string($nom);
		$isbn=$this->mysql->real_escape_string($isbn);
		$ean13=$this->mysql->real_escape_string($ean13);
		$date_publication=$this->mysql->real_escape_string($date_publication);
		$description=$this->mysql->real_escape_string($description);
		$genre=intval($genre);
		// Forçage du type entier (si autre chose qu'une représentation d'un nombre => 0)
		$aid=intval($aid);
		$sid=intval($sid);
		$uid=intval($uid); // uid utilisateur qui a ajouté le livre
		$sql='INSERT INTO livres(nom,isbn,ean13,date_publication,description,aid,serie,ajuid,ajdate,genre,lvalide) VALUES ("'.$nom.'","'.$isbn.'","'.$ean13.'","'.$date_publication.'","'.$description.'","'.$aid.'","'.$sid.'","'.$aid.'","'.time().'","'.$genre.'",1)';
		// Si on arrive à executer la requête
		if($this->mysql->query($sql))
		{
			$lid=$this->mysql->insert_id; // Renvoi l'identifiant du livre qu'on vient d'ajouter
			$this->ajoutCollection($lid,$aid,'','',''); // Rajout dans la collection de l'utilisateur
			return $lid;
		}
		else
		{
			return false;
		}
	}
	// Permet de modifier les données d'un livre
	private function update($lid,$nom,$isbn,$ean13,$date_publication,$description,$aid,$sid,$genre)
	{
		$lid=intval($lid);
		$nom=$this->mysql->real_escape_string($nom);
		$isbn=$this->mysql->real_escape_string($isbn);
		$ean13=$this->mysql->real_escape_string($ean13);
		$date_publication=$this->mysql->real_escape_string($date_publication);
		$description=$this->mysql->real_escape_string($description);
		$description=intval($genre);
		$aid=intval($aid);
		$sid=intval($sid);
		$livre=new Livre($lid);
		if($livre->getValide()) // Si le livre existe
		{
			$sql='UPDATE livres SET nom="'.$nom.'",isbn="'.$isbn.'",ean13="'.$ean13.'",date_publication="'.$date_publication.'",description="'.$description.'",aid='.$aid.',serie='.$sid.',genre='.$genre.' WHERE lid='.$lid.'';
			return $this->mysql->query($sql);
		}
		else
		{
			return false;
		}
	}
	// Permet de traiter le formulaire d'ajout/modification d'une BD
	public function ajoutForm()
	{
		$template=trim(file_get_contents(PARTIAL.'ajout_bd.xhtml'));
		$param=requestObject('Param');
		$lid=intval($param->getValue());
		if(isset($_POST['lid'],$_POST['nomBD'], $_POST['nomAuteur'], $_POST['prenomAuteur'],$_POST['noISBN'],$_POST['noEAN13'],$_POST['genre'],$_POST['jourPublication'],$_POST['moisPublication'],$_POST['anneePublication'],$_POST['synopsis']) and !empty($_POST['nomBD']) and !empty($_POST['nomAuteur']))
		{
		
			$lid=intval($_POST['lid']);
			$auteur=requestObject('Auteur');
			$tab=$auteur->recherche($_POST['nomAuteur'],$_POST['prenomAuteur']);
			if(count($tab)>0) // l'auteur existe
			{
				$aid=$tab[0]['aid'];
			}
			else
			{
				$aid=requestObject('Auteur')->ajout($_POST['nomAuteur'],$_POST['prenomAuteur']);
			}
			$date_publication=mktime(0,0,0,intval($_POST['moisPublication']),intval($_POST['jourPublication']),$_POST['anneePublication']);
			if($lid==0) // BD inexistante => création
			{

				if($lid=$this->ajoutLivre($_POST['nomBD'],$_POST['noISBN'],$_POST['noEAN13'],$date_publication,$_POST['synopsis'],$aid,'',requestObject('Utilisateurs')->getUid(),$_POST['genre']))
				{
					$template.="Livre enregistré sous l'identifiant ".$lid;
				}
				else
				{
					$template.="Echec de l'enregistrement";
				}
			}
			else
			{
				// BD existante => modification
				if($this->update($lid,$_POST['nomBD'],$_POST['noISBN'],$_POST['noEAN13'],$date_publication,$_POST['synopsis'],$aid,'',$_POST['genre']))
				{
					$template.="BD mise à jour avec succès";
				}
				else
				{
					$template.="Echec de la mise à jour";
				}
			}
		}
		$livre=new Livre($lid);
		$template=$this->affichLivre($template,$livre);
		return $template;
	}
	// Permet d'ajouter un livre à sa collection
	private function ajoutCollection($lid,$uid,$date_dachat="",$etat=1,$emplacement="")
	{
		// Protection des variables
		$uid=intval($uid);
		$lid=intval($lid);
		$date_dachat=intval($date_dachat); // C'est un timestamp
		$etat=intval($etat);
		$emplacement=$this->mysql->real_escape_string($emplacement);
		
		// Est-ce que le livre existe vraiment ?
		$livre=new Livre($lid);
		if($livre->getValide())
		{
			$sql='INSERT INTO appartient(uid,lid,date_achat,etat,emplacement) VALUES("'.$uid.'","'.$lid.'","'.$date_dachat.'","'.$etat.'","'.$emplacement.'")';
			return $this->mysql->query($sql);
		}
		else
		{
			unset($livre);
			return false;
		}
	}
	public function ajoutCollectionGroupe()
	{
		$user=requestObject('Utilisateurs');
		$template="";
		if(!$user->estConnecte()) // Si l'utilisateur n'est pas connecté, c'est pas la peine de continuer...
		{
			return;
		}
		$uid=$user->getUid();
		if(isset($_POST['BD']) and !empty($_POST['BD']))
		{
			$sql='INSERT INTO appartient(uid,lid,date_achat,etat,emplacement) VALUES';
			for($i=0;$i<count($_POST['BD']);$i++)
			{
				$lid=intval($_POST['BD'][$i]);
				$sql.='("'.$uid.'","'.$lid.'","","1","")';
				if($i<(count($_POST['BD'])-1))
				{
					$sql.=',';
				}
			}
			if(isset($lid))
			{
				if($this->mysql->query($sql))
				{
					$template='<div class="message">Les BDs ont bien été rajoutées à votre collection !</div>';
				}
				else
				{
					$template='<div class="erreur">Erreur lors de l\'enregistrement des BDs dans votre collection';
				}
			}
		}
		return $template;
	}
	public function supprCollectionGroupe()
	{
		$user=requestObject('Utilisateurs');
		$template="";
		if(!$user->estConnecte())
		{
			return;
		}
		$uid=$user->getUid();
		if(isset($_POST['BD']) and !empty($_POST['BD']))
		{
			$sql='DELETE FROM appartient WHERE uid='.$uid.' AND lid IN (';
			for($i=0;$i<count($_POST['BD']);$i++)
			{
				$lid=intval($_POST['BD'][$i]);
				$sql.=$lid;
				if($i<(count($_POST['BD'])-1))
				{
					$sql.=',';
				}
			}
			$sql.=')';
			if(isset($lid))
			{
				if($this->mysql->query($sql))
				{
					$template='<div class="message">Les BDs ont bien été supprimées de votre collection !</div>';
				}
				else
				{
					$template='<div class="erreur">Erreur lors de la suppresion des BDs de votre collection';
				}
			}
		}
		return $template;
	}
	// Permet de rempalcer les tags d'un template par les données contenues dans l'objet $livre
	public function affichLivre($template,$livre)
	{
		$template=str_replace('{{LID}}',$livre->lid,$template);
		$template=str_replace('{{NOM}}',$livre->nom,$template);
		$template=str_replace('{{ISBN}}',$livre->isbn,$template);
		$template=str_replace('{{EAN13}}',$livre->ean13,$template);
		$template=str_replace('{{DATEPUB}}',$livre->date_publication,$template);
		$template=str_replace('{{JOURPUB}}',date('d',$livre->date_publication),$template);
		$template=str_replace('{{MOISPUB}}',date('m',$livre->date_publication),$template);
		$template=str_replace('{{ANNEEPUB}}',date('Y',$livre->date_publication),$template);
		$template=str_replace('{{GENRES}}',$this->creer_liste_genres($livre->genreid),$template);
		$template=str_replace('{{NOMAUTEUR}}',$livre->auteurn,$template);
		$template=str_replace('{{PRENOMAUTEUR}}',$livre->auteurp,$template);
		$template=str_replace('{{IDAUTEUR}}',$livre->aid,$template);
		$template=str_replace('{{DESCRIPTION}}',$livre->description,$template);
		$template=str_replace('{{SYNOPSIS}}',$livre->description,$template);
		$template=str_replace('{{GENRE}}',$livre->genre,$template);
		$template=str_replace('{{AJUSE}}',$livre->ajuser,$template);
		$template=str_replace('{{AJDATE}}',$livre->ajdate,$template);
		$template=str_replace('{{EDITEUR}}',$livre->editeur,$template);
		$template=str_replace('{{SERIE}}',$livre->serie,$template);
		$template=str_replace('{{COUVERTURE}}',$livre->couverture,$template);
		return $template;
	}
	
	public function affichLivreComplet()
	{
		// Récupération de l'identifiant du livre :
		$param = requestObject('Param');
		$lid=intval($param->getValue());
		
		// Récupération de l'objet et donc des paramètres
		$livre = new Livre($lid);
		
		if($livre->getValide()>0) // Si le livre existe
		{
			// Récupération du template
			$template=file_get_contents(PARTIAL.'livre_complet.xhtml');
			// Remplacement des balises
			$template=$this->affichLivre($template,$livre);
		}
		else
		{
			 // Le livre n'existe pas
			 $template='<div class="error">Le livre demandé n\'existe pas</div>';
		}
		return $template;
	}
	public function listGenres()
	{
		$sql='SELECT gid,gnom FROM genre ORDER BY gid';
		return queryToArray($this->mysql->query($sql));
	}
	public function creer_liste_genres($selected=0)
	{
		$liste = $this->listGenres();
		$res = "<select id='genre' name='genre'>\n";
		foreach($liste as $val)
		{
			if($val['gid']==$selected)
				$filter=' selected="selected" ';
			else
				$filter="";
			$res .= "<option value='".$val['gid']."'".$filter.">".$val['gnom']."</option>\n";
		}
		$res .= "</select>\n";
		return $res;
	}

	public function listBD()
	{
		$uid=requestObject('Utilisateurs')->getUid();
		// Permet de lister toutes les BDs présentes dans la base de données
		$sql='SELECT l.lid,l.nom,l.isbn,l.ean13,l.date_publication,l.lvalide,l.description,a.aid,a.anom,a.aprenom,s.snom,g.gnom,e.enom,ajdate,ap.date_achat,ap.etat,ap.emplacement FROM livres l JOIN auteurs a ON l.aid=a.aid LEFT JOIN series s ON l.serie=s.sid LEFT JOIN genre g ON l.genre=g.gid LEFT JOIN editeurs e ON e.eid=l.editeur LEFT JOIN utilisateurs u ON l.ajuid=u.uid LEFT JOIN appartient ap ON ap.lid=l.lid AND ap.uid='.$uid.'';
		return queryToArray($this->mysql->query($sql));
	}
	public function listBDutil($uid=0)
	{
		if($uid==0)
		{
			$uid=requestObject('Utilisateurs')->getUid();
		}
		$sql='SELECT l.lid,l.nom,l.isbn,l.ean13,l.date_publication,l.lvalide,l.description,a.aid,a.anom,a.aprenom,s.snom,g.gnom,e.enom,ajdate,ap.date_achat,ap.etat,ap.emplacement FROM livres l JOIN auteurs a ON l.aid=a.aid LEFT JOIN series s ON l.serie=s.sid LEFT JOIN genre g ON l.genre=g.gid LEFT JOIN editeurs e ON e.eid=l.editeur LEFT JOIN utilisateurs u ON l.ajuid=u.uid JOIN appartient ap ON ap.lid=l.lid AND ap.uid='.$uid;
		return queryToArray($this->mysql->query($sql));
	}
	public function recherche($motcle)
	{
		// Fonction qui permet de faire une recherche à partir de n'importe quel champ de type texte dans la base de données
		// Protection du motclé :
		$motcle=$this->mysql->real_escape_string($motcle);
		$sql='SELECT l.lid,l.nom,l.isbn,l.ean13,l.date_publication,l.lvalide,l.description,a.aid,a.aprenom,a.anom,s.snom,g.gnom,e.enom,ajdate FROM livres l JOIN auteurs a ON l.aid=a.aid LEFT JOIN series s ON l.serie=s.sid LEFT JOIN genre g ON l.genre=g.gnom LEFT JOIN editeurs e ON e.eid=l.editeur LEFT JOIN utilisateurs u ON l.ajuid=u.uid WHERE l.nom LIKE "%'.$motcle.'%"';
		return queryToArray($this->mysql->query($sql));
	}
}
?>
