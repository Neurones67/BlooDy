<?php
/*
	Fichier de gestion des utilisateurs
	Par Marc
*/
class Utilisateurs
{
	// Variables de l'utilisateur
	private $uid;
	private $email;
	private $description;
	private $bdpublique;
	private $accueiltype;
	private $ipinscrition;
	private $uetat;
	private $cvalidation;
	
	// variables utiles
	private $mysql; // Connexion à la base de données
	public __construct($uid)
	{
		// Demande de l'objet MySQL
		$this->mysql=requestObject('MySQL');
		if($uid!=0) // Si on demande un utilisateur existant (0 étant l'utilisateur non connecté anonyme)
		{
			// on initialise les données de l'utilisateur
			if(!$this->init_data($uid))
			{
				// Si on y arrive pas
				$uid=0;
				// On détruit la session et ses variables;
				session_destroy();
				session_unset();
			}
		}
		else
		{
			$this->login="Anonyme";
			$this->uetat=255; // Non connecté
		}
	}
	private init_data($uid)
	{
		$uid=intval($uid);
		$sql='SELECT uid,email,description,bdpublique,accueiltype,ipinscription,uetat,cvalidation FROM utilisateurs WHERE uid='.$uid;
		$req= $this->mysql->query($sql);
		if($data=$req->fetch_object())
		{
			$this->uid=$uid;
			$this->email=$data->email;
			$this->descrption=$data->description;
			$this->bdpublique=$data->bdpublique;
			$this->accueiltype=$data->accueiltype;
			$this->ipinscription=$data->ipinscription
			$this->uetat=$data->uetat;
			$this->cvalidation=$data->cvalidation;
			return true;
		}
		else
		{
			return false;
		}
	}
	private passhash($password)
	{
		// Permet de chiffrer un mot de passe
		return sha1(md5($password));
	}
	private auth($login,$user)
	{
		// Authentifie un utilisateur avec son login et son mot de passe
	}
}
