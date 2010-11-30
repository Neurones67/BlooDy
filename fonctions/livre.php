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
	private $auteur;
	private $serie;
	private $ajuser;
	private $editeur;
	private $ajdate;
	private $genre;

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
		unset($this->auteur);
		unset($this->editeur);
		unset($this->ajuser);
		unset($this->ajdate);
		unset($this->serie);
		unset($this->genre);
	}
	public function getValide()
	{
		return $this->lvalide;
	}
	private function initData($lid)
	{
		// Initialise les attributs du livre avec le lid donné à partir de la base de donnée
		$lid=intval($lid);
		$sql='SELECT l.nom,l.isbn,l.ean13,l.date_publication,l.lvalide,l.description,a.anom,s.snom,g.gnom,e.enom,ajdate FROM livres l JOIN auteurs a ON l.aid=a.aid LEFT JOIN series s ON l.serie=s.sid LEFT JOIN genre g ON l.genre=g.gnom LEFT JOIN editeurs e ON e.eid=l.editeur LEFT JOIN utilisateurs u ON l.ajuid=u.uid WHERE lid='.$lid;
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
		$this->lid=$lid;
		$this->nom=$data->nom;
		$this->isbn=$data->isbn;
		$this->ean13=$data->ean13;
		$this->date_publication=$data->date_publication;
		$this->lvalide=$data->lvalide;
		$this->auteur=$data->anom;
		$this->genre=$data->gnom;
		$this->ajuser=$data->pseudo;
		$this->ajdate=$data->ajdate;
		$this->editeur=$data->enom;
		$this->serie=$data->snom;
	}
	// Ajoute un livre à la base de données avec l'ID de l'auteur et l'ID de la série (peut être vide éventuellement)
	private function ajoutLivre($nom,$isbn,$ean13,$date_publication,$description,$aid,$sid,$uid)
	{
		// Protection des variables
		$nom=$this->mysql->real_escape_string($nom);
		$isbn=$this->mysql->real_escape_string($isbn);
		$ean13=$this->mysql->real_escape_string($ean13);
		$date_publication=$this->mysql->real_escape_string($date_publication);
		$description=$this->mysql->real_escape_string($date_publication);
		// Forçage du type entier (si autre chose qu'une représentation d'un nombre => 0)
		$aid=intval($aid);
		$sid=intval($sid);
		$uid=intval($uid); // uid utilisateur qui a ajouté le livre
		$sql='INSERT INTO livres(nom,isbn,ean13,date_publication,description,aid,sid,ajid,ajdate) VALUES ("'.$nom.'","'.$isbn.'","'.$ean13.'","'.$date_publication.'","'.$description.'","'.$aid.'","'.$sid.'"."'.$ajid.'","'.time().'")';
		// Si on arrive à executer la requête
		if($this->mysql->query($sql))
		{
			return $this->mysql->insert_id; // Renvoi l'identifiant du livre qu'on vient d'ajouter
		}
		else
		{
			return false;
		}
	}
	// Permet d'ajouter un livre à sa collection
	private function ajoutCollection($lid,$uid,$date_dachat,$etat,$emplacement)
	{
		// Protection des variables
		$uid=intval($uid);
		$lid=intval($lid);
		$date_dachat=intval($date_dachat); // C'est un timestamp
		$etat=intval($etat);
		$emplacement=$this->mysql->real_escape_string($emplacement);
		
		// Est-ce que le livre existe vraiment ?
		$livre=new Livre($uid);
		if($livre->getValide>0)
		{
			$sql='INSERT INTO appartient(uid,lid,date_achat,etat,emplacement) VALUES("'.$uid.'","'.$lid.'","'.$date_achat.'","'.$etat.'","'.$emplacement.'")';
			return $this->mysql->query($sql);
		}
		else
		{
			unset($livre);
			return false;
		}
	}
	// Permet de rempalcer les tags d'un template par les données contenues dans l'objet $livre
	private static function affichLivre($template,$livre)
	{
		$template=str_replace('{{LID}}',$livre->lid,$template);
		$template=str_replace('{{SNOM}}',$livre->nom,$template);
		$template=str_replace('{{ISBN}}',$livre->isbn,$template);
		$template=str_replace('{{EAN13}}',$livre->ean13,$template);
		$template=str_replace('{{DATEPUB}}',$livre->date_publication,$template);
		$template=str_replace('{{ANOM}}',$livre->auteur,$template);
		$template=str_replace('{{GENRE}}',$livre->genre,$template);
		$template=str_replace('{{AJUSE}}',$livre->ajuser,$template);
		$template=str_replace('{{AJDATE}}',$livre->ajdate,$template);
		$template=str_replace('{{EDITEUR}}',$livre->editeur,$template);
		$template=str_replace('{{SERIE}}',$livre->serie,$template);
		return $template;
	}
	
	public static function affichLivreComplet()
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
	public static function listGenres()
	{
		$sql='SELECT gid,gnom FROM genre ORDER BY id';
		$res=array();
		if($req=$this->mysql->query($sql))
		{
			$res[]=$req->fetch_assoc();
		}
		return $res;
	}

	public static function creer_liste_genres()
	{
		$liste = listeGenres();
		$res = "<select id='genre' name='genre'>\n";
		foreach($liste as $val)
			$res += "<option value='".$val['gid']."'>".$val['gnom']."</option>\n";
		$res += "</select>\n";
		return $res;
	}

	public static function listBD()
	{
		// Permet de lister toutes les BDs présentes dans la base de données
		$sql='SELECT l.lid,l.nom,l.isbn,l.ean13,l.date_publication,l.lvalide,l.description,a.anom,s.snom,g.gnom,e.enom,ajdate FROM livres l JOIN auteurs a ON l.aid=a.aid LEFT JOIN series s ON l.serie=s.sid LEFT JOIN genre g ON l.genre=g.gnom LEFT JOIN editeurs e ON e.eid=l.editeur LEFT JOIN utilisateurs u ON l.ajuid=u.uid';
		$res=array();
		if($req=$this->mysql->query($sql))
		{
			$res[]=$req->fetch_assoc();
		}
		return $res:
	}
}
?>
