<?php
/*
	Classe de gestion d'un livre (ou album)
	Par Marc
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
	
	// Besoin d'une connexion MySQL
	private $mysql;
	
	public function __construct($lid=0)
	{
		$this->mysql=requestObject('MySQL');
		if($lid!=0)
		{
			if(!$this->init_data($lid)) // On initialise les attributs
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
	}
	public function getValide()
	{
		return $this->lvalide;
	}
	private function init_data($lid)
	{
		// Initialise les attributs du livre avec le lid donné à partir de la base de donnée
		$sql='SELECT l.nom,l.isbn,l.ean13,l.date_publication,l.lvalide,l.description,a.anom FROM livres l JOIN auteurs a ON l.aid=a.aid';
		$req=$this->mysql->query($sql);
		if($data=$req->fetch_object())
		{
			$this->lid=$lid;
			$this->nom=$data->nom;
			$this->isbn=$data->isbn;
			$this->ean13=$data->ean13;
			$this->date_publication=$data->date_publication;
			$this->lvalide=$data->lvalide;
			$this->auteur=$data->anom;
			return true;
		}
		else
		{
			return false;
		}
	}
	// Ajoute un livre à la base de données avec l'ID de l'auteur et l'ID de la série (peut être vide éventuellement)
	private function ajout_livre($nom,$isbn,$ean13,$date_publication,$description,$aid,$sid,$uid)
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
	private function ajout_collection($lid,$uid,$date_dachat,$etat,$emplacement)
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
}
