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
			if(!$this->initData($uid))
			{
				// Si on y arrive pas
				$this->uid=-1;
			}
		}
		elseif(isset($_SESSION['connecte'], $_SESSION['uid']) and $_SESSION['connecte'])
		{
			$uid=intval($_SESSION['uid']);
			if(!$this->initData($uid))
			{
				// Si on y arrive pas
				$this->uid=-1;
				// On détruit la session et ses variables;
				session_destroy();
				session_unset();
			}
		}
		else
		{
			$this->pseudo="Anonyme";
			$this->uetat=-1; // Non connecté
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
	private function initData($uid)
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
	public function estConnecte()
	{
		return $this->uid>0;
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
		$sql='INSERT INTO utilisateurs(pseudo,motdepasse,email,dinscription,ipinscription) VALUES("'.$pseudo.'","'.$password.'","'.$email.'","'.time().'","'.$_SERVER['REMOTE_ADDR'].'")';
		$res=$this->mysql->query($sql);
		return $this->mysql->insert_id;	
	}
	
	// Gère le formulaire d'inscription des membres
	public function registerForm()
	{
		$template="";
		// Gère l'inscription des membres
		$login="";
		$email="";
		if(isset($_POST['login'], $_POST['email'], $_POST['email2'], $_POST['password'], $_POST['password2'])) // Si on a bien renseigné tous les champs
		{
			
			if(!empty($_POST['login']) and !empty($_POST['email']) and !empty($_POST['email2']) and !empty($_POST['password']) and !empty($_POST['password2']))
			{
				$errors=array();
				if(strlen($_POST['login'])>22)
				{
					$errors[]="Pseudo trop long, Limité à 22 caractères (le vôtre en fait ".strlen($_POST['login']).")";
				}
				if($_POST['email'] != $_POST['email2'])
				{
					$errors[]="Les deux adresses emails ne correspondent pas";
				}
				if($_POST['password'] != $_POST['password2'])
				{
					$errors[]="Les deux mots de passe ne correspondent pas";
				}
				$login=$this->mysql->real_escape_string($_POST['login']);
				$email=$this->mysql->real_escape_string($_POST['email']);
				$sql='SELECT pseudo FROM utilisateurs WHERE pseudo="'.$login.'"';
				$req=$this->mysql->query($sql);
				if($req->fetch_array()) // Si l'user existe déjà
				{
					$errors[]="Ce pseudo est déjà utilisé par quelqu'un d'autre, choisissez-en un autre";
				}
				if(!preg_match('/^[-a-z0-9!#$%&\'*+\/=?^_`{|}~]+(\.[-a-z0-9!#$%&\'*+\/=?^_`{|}~]+)*@(([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){1,63}\.)+([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){2,63}$/i',$_POST['email']))
				{
					$errors[]="L'adresse email n'est pas valide";
				}
				if(count($errors)>0)
				{
					$template=requestObject('Nav')->userErrorHandler("Plusieurs erreurs ont été détectés dans ce que vous avez fourni",$errors);
				}
				else
				{
					$password=stripslashes($_POST['password']);
					if($this->register($login,$password,$email))
					{
						$template='<div class="message">Vous êtes maintenant enregistrés sur le site de BlooDy, vous pouvez maintenant vous <a href="/connexion.html">connecter</a></div>';
					}
					else
					{
						$errors[]="Enregistrement échoué pour une raison inconnue, contacter le webmaster";
						$template=requestObject('Nav')->userErrorHandler("Une erreur est survenue",$errors);
					}
				}
			}
			else
			{
					$errors=array();
					if(empty($_POST['login']))
					{
						$errors[]="Votre identifiant";
					}
					if(empty($_POST['email']))
					{
						$errors[]="Votre email";
					}
					if(empty($_POST['email2']))
					{
						$errors[]="La confirmation de votre email";
					}
					if(empty($_POST['password']))
					{
						$errors[]="Votre mot de passe";
					}
					if(empty($_POST['password2']))
					{
						$errors[]="La confirmation de votre mot de passe";
					}
					$template=requestObject('Nav')->userErrorHandler("Vous avez oublié de renseigner les champs suivants",$errors);
			}
		}
		return $template;
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
			$this->initData($data->uid);
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
				return 'Connexion échouée';
			}
		}
		else
		{
			return 'Vous n\'avez pas remplis tous les champs';
		}
	}
}
