<?php
/*
	Fonctions de gestion des auteurs
	Par Marc
	Pour Bloody
	01/12/2010
*/
class Auteur
{
	private $aid;
	private $anom;
	private $aprenom;
	private $biographie;
	private $adatenaissance;
	private $aphoto;
	private $avalide;
	
	// Besoin d'une connexion MySQL
	private $mysql;
	
	public function __construct($aid=0)
	{
		$this->mysql=requestObject('MySQL');
		if($aid!=0)
		{
			if(!$this->initData($aid)) // On initialise les attributs
			{
				// Si il n'existe pas
				$this->avalide=-1; // Permet de dire que l'auteur n'existe pas
			}
		}
	}
	
	private function initData($aid)
	{
		$aid=intval($aid);
		$sql='SELECT anom,aprenom,biographie,adatenaissance,aphoto,avalide FROM auteurs WHERE aid='.$aid;
		$req=$this->mysql->query($sql);
		if($data=$req->fetch_object())
		{
			$this->aid=$data->aid;
			$this->anom=$data-->anom;
			$this->aprenom=$data->aprenom;
			$this->biographie=$data->biographie;
			$this->adatenaissance=$data->adatenaissance;
			$this->avalide=$data->avalide;
		}
	}
	
	private function recherche($motcle)
	{
		$motcle=$this->mysql->real_escape_string($motcle);
		$sql='SELECT aid,anom,aprenom,biographie,adatenaissance,aphoto,avalide FROM auteurs WHERE anom LIKE "%'.$motcle.'%" OR aprenom LIKE "%'.$motcle.'%"';
		return queryToArray($this->mysql->query($sql));
	}
}
