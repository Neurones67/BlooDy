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
	public function __construct($uid=0)
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
			$this->pseudo="Anonyme";
			$this->uetat=255; // Non connecté
		}
	}
	public function __destruct()
	{
		unset($this->uid);
		unset($this->email);
		unset($this->description);
		unset($this->bdpublique);
		unset($this->accueiltype);
		unset($this->ipinscription);
		unset($this->uetat);
		unset($this->cvalidation);
	}
	private function init_data($uid)
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
			$this->ipinscription=$data->ipinscription;
			$this->uetat=$data->uetat;
			$this->cvalidation=$data->cvalidation;
			return true;
		}
		else
		{
			return false;
		}
	}
	private function passhash($password)
	{
		// Permet de chiffrer un mot de passe
		return sha1(md5($password));
	}
	private function genConfirmCode()
	{
		// Génère une chaine aléatoire de 5 caractères pour les codes de confirmations
		return substr(sha1(md5(sha1(mt_rand().time().'BlooDy'))),5,5);
	}
	private function register($pseudo,$password,$email)
	{
		// Enregistre l'utilisateur avec les paramètres indiqués et retourne l'identifiant (uid) nouvellement créer
		$pseudo=$this->mysql->real_escape_string($pseudo);
		$password=$this->passhash($password);
		$email=$this->mysql->real_escape_string($email);
		$sql='INSERT INTO utilisateurs(pseudo,motdepasse,email) VALUES("'.$pseudo.'","'.$password.'","'.$email.'"';
		$res=$this->mysql->query($sql);
		return $this->mysql->insert_id;	
	}
	private function auth($pseudo,$password)
	{
		// Authentifie un utilisateur avec son pseudo et son mot de passe
		$pseudo=$this->mysql->real_escape_string($pseudo);
		$password=$this->passhash($password);
		$sql='SELECT uid FROM utilisateurs WHERE pseudo="'.$pseudo.'" AND motdepasse="'.$password.'"';
		$req=$this->mysql->query($sql);
		if($data=$req->fetch_object())
		{
			$_SESSION['connecte']=true;
			$_SESSION['uid']=$data->uid;
			$this->init_data($uid);
			return true;
		}
		else
		{
			return false;
		}
	}
	public function connexion()
	{
		if(isset($_POST['id_Connexion'],$_POST['id_MotDePasse']) and !empty($_POST['id_Connexion']) and !empty($_POST['id_MotDePasse']))
		{
		
			if($this->auth($_POST['id_Connexion'],$_POST['id_MotDePasse']))
			{
				return 'Connexion réussie';
			}
			else
			{
				return 'Connexion echouée';
			}
		}
		else
		{
			return 'Vous n\'avez pas remplis tous les champs';
		}
	}
}
