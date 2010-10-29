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
	private $nom:
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
			$this->init_data($lid);
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
	private function init_data($lid)
	{
		// Initialise les attributs du livre avec le lid donné à partir de la base de donnée
		$sql='SELECT l.nom,l.ean13,l.date_publication,l.lvalide,l.description,a.anom FROM livres l JOIN auteurs a ON l.aid=a.aid';
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
}
