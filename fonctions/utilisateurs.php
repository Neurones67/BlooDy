<?php
/*
	Fichier de gestion des utilisateurs
	Par Marc
*/
class Utilisateurs
{
	// Variables de l'utilisateur
	private $uid;
	private $pseudo;
	private $email;
	private $description;
	private $bdpublique;
	private $accueiltype;
	private $ipinscrition;
	private $dinscription;
	private $avatar;
	/*
		État de l'utilisateur:
		<0 => Non valide, non enregistré
		0  => Enregistré, pas encore validé
		1  => Enregistré, validé
		2  => Enregistré, demande de changement de mot de passe/email actif
	*/
	private $uetat;
	private $cvalidation;
	
	// variables utiles
	private $mysql; // Connexion à la base de données
	private $connexionm;
	public function __construct($uid=0)
	{
		// Demande de l'objet MySQL
		$this->mysql=requestObject('MySQL');
		$this->connexionm="";
		if($uid!=0) // Si on demande un utilisateur existant (0 étant l'utilisateur non connecté anonyme)
		{
			// on initialise les données de l'utilisateur
			if(!$this->initData($uid))
			{
				// Si on y arrive pas
				$this->uid=-1;
				$this->uetat=-1;
			}
		}
		elseif(isset($_SESSION['connecte'], $_SESSION['uid']) and $_SESSION['connecte'])
		{
			$uid=intval($_SESSION['uid']);
			if(!$this->initData($uid))
			{
				// Si on y arrive pas
				$this->uid=-1;
				$this->uetat=-1;
				// On détruit la session et ses variables;
				session_destroy();
				session_unset();
			}
		}
		else
		{
			$this->pseudo="Anonyme";
			$this->uetat=-1; // Non connecté
			$this->uid=-1;
		}
	}
	public function __destruct()
	{
		unset($this->uid);
		unset($this->pseudo);
		unset($this->email);
		unset($this->description);
		unset($this->bdpublique);
		unset($this->accueiltype);
		unset($this->ipinscription);
		unset($this->dinscription);
		unset($this->uetat);
		unset($this->cvalidation);
		unset($this->connexionm);
		unset($this->avatar);
	}
	private function initData($uid)
	{
		$uid=intval($uid);
		$sql='SELECT uid,pseudo,email,avatar,description,bdpublique,accueiltype,ipinscription,uetat,cvalidation,dinscription FROM utilisateurs WHERE uid='.$uid;
		$req= $this->mysql->query($sql);
		if($data=$req->fetch_object())
		{
			$this->uid=$uid;
			$this->pseudo=$data->pseudo;
			$this->email=$data->email;
			$this->avatar=$data->avatar;
			$this->description=stripslashes($data->description);
			$this->bdpublique=$data->bdpublique;
			$this->accueiltype=$data->accueiltype;
			$this->ipinscription=$data->ipinscription;
			$this->uetat=$data->uetat;
			$this->cvalidation=$data->cvalidation;
			$this->dinscription=$data->dinscription;
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
	public function getUid()
	{
		return $this->uid;
	}
	public function getLogin()
	{
		return $this->pseudo;
	}
	public function getEmail()
	{
		return $this->email;
	}
	public function getAvatar()
	{
		return $this->avatar;
	}
	public function getDescription()
	{
		return $this->description;
	}
	private function passhash($password)
	{
		// Permet de chiffrer un mot de passe
		return sha1(md5($password));
	}
	private function genConfirmCode()
	{
		// Génère une chaine aléatoire de 10 caractères pour les codes de confirmations
		return substr(sha1(md5(sha1(mt_rand().time().'BlooDy'))),0,10);
	}
	private function register($pseudo,$password,$email)
	{
		// Enregistre l'utilisateur avec les paramètres indiqués et retourne l'identifiant (uid) nouvellement créer
		$pseudo=$this->mysql->real_escape_string($pseudo);
		$password=$this->passhash($password);
		$email=$this->mysql->real_escape_string($email);
		$sql='INSERT INTO utilisateurs(pseudo,motdepasse,email,dinscription,ipinscription,uetat) VALUES("'.$pseudo.'","'.$password.'","'.$email.'","'.time().'","'.$_SERVER['REMOTE_ADDR'].'",1)';
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
	public function connexionM()
	{
		$template=$this->connexionm;
		$this->connexionm="";
		return $template;
	}
	private function auth($pseudo,$password)
	{
		// Authentifie un utilisateur avec son pseudo et son mot de passe
		$pseudo=$this->mysql->real_escape_string($pseudo);
		$password=$this->passhash($password);
		$sql='SELECT uid FROM utilisateurs WHERE pseudo="'.$pseudo.'" AND motdepasse="'.$password.'" AND uetat>0';
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
	private function updatePassword($id,$npassword)
	{
		if(!$this->estConnecte())
			return;
		$npassword=$this->passhash($npassword);
		$sql='UPDATE utilisateurs SET motdepasse="'.$npassword.'" WHERE uid='.$id;
		return $this->mysql->query($sql);
	}
	public function updateAvatar($id,$navatar)
	{
		if(!$this->estConnecte())
			return;
		$navatar=$this->mysql->real_escape_string($navatar);
		$sql='UPDATE utilisateurs SET avatar="'.$navatar.'" WHERE uid='.$id;
		return $this->mysql->query($sql);
	}
	public function updateProfil()
	{
		if(!$this->estConnecte())
			return;
		$template="";
		if(isset($_POST['description']) and !empty($_POST['description']))
		{
			$description=$this->mysql->real_escape_string(addslashes($_POST['description']));
			$sql='UPDATE utilisateurs SET description="'.$description.'" WHERE uid='.$this->uid.'';
			if($this->mysql->query($sql))
			{
				$template="<div class='message'>Mise à jour de votre profil réussie !</div>";
				$this->description=stripslashes($_POST['description']);
			}
			else
			{
				$template="<div class='erreur'>Echec de la mise à jour de votre profil</div>";
			}
		}
		return $template;
	}
	public function passwordForm()
	{
		if(!$this->estConnecte())
			return;
		$template="";
		if(isset($_POST['ancienpass'],$_POST['password'],$_POST['password2']) and !empty($_POST['ancienpass']) and !empty($_POST['password']) and !empty($_POST['password2']))
		{
			$errors=array();
			$pass=$this->passhash($_POST['ancienpass']);
			$req='SELECT uid FROM utilisateurs WHERE pseudo="'.$this->pseudo.'" AND motdepasse="'.$pass.'"';
			$req=$this->mysql->query($req);
			if($data=$req->fetch_array())
			{
				if($_POST['password']==$_POST['password2'])
				{
					if($this->updatePassword($this->uid,$_POST['password']))
					{
						$template='<div class="message">Mot de passe changé avec succès !</div>';
					}
					else
					{
						$errors[]='Impossible de changer le mot de passe';
					}
				}
				else
				{
					$errors[]='Les mots de passes ne correspondent pas';
				}
			}
			else
			{
				$errors[]='Mot de passe incorrect';
			}
			$template.=requestObject('Nav')->userErrorHandler('Le script ne peut pas changer votre mot de passe',$errors);
		}
		return $template;
	}
	public function emailForm()
	{
		if(!$this->estConnecte())
			return;
		$template="";
		$errors=array();
		if(isset($_POST['email'],$_POST['email2'])and !empty($_POST['email']) and !empty($_POST['email2']))
		{

			if(!preg_match('/^[-a-z0-9!#$%&\'*+\/=?^_`{|}~]+(\.[-a-z0-9!#$%&\'*+\/=?^_`{|}~]+)*@(([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){1,63}\.)+([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){2,63}$/i',$_POST['email']))
			{
				$errors[]="L'adresse email n'est pas valide";
			}
			else if($_POST['email']==$_POST['email2'])
			{
				if($this->updateEmail($this->uid,$_POST['email']))
				{
					$template='<div class="message">Adresse email changée avec succès !</div>';
				}
				else
				{
					$errors[]='Impossible de changer l\'adresse email';
				}
			}
			else
			{
				$errors[]='Les deux adresses email ne correspondent pas';
			}
			$template.=requestObject('Nav')->userErrorHandler('Le script ne peut pas changer votre adresse email',$errors);
		}
		return $template;
	}
	private function updateEmail($id,$email)
	{
		if(!$this->estConnecte())
			return;
		$email=$this->mysql->real_escape_string($email);
		$sql='UPDATE utilisateurs SET email="'.$email.'" WHERE uid='.$id.'';
		return $this->mysql->query($sql);
	}
	public function liste() // Permet de faire la liste des utilisateurs
	{
		$sql='SELECT uid,pseudo,email FROM utilisateurs ORDER BY uid';
		return queryToArray($this->mysql->query($sql));
	}
	public function ajoutAmi($duid) // Permet d'ajouter un ami
	{
		$uid=$this->getUid();
		$duid=intval($duid);
		$sql1='SELECT a.duid FROM amis a WHERE (a.euid='.$uid.' AND a.duid='.$duid.') OR (a.euid='.$duid.' AND a.duid='.$uid.')';
		$res1=$this->mysql->query($sql1);
		if($res1->fetch_row())
		{
			// On est déjà ami avec la personne.
		}
		else
		{
			// on est pas déjà ami, on ajoute.
			$sql2='INSERT INTO amis(euid,duid,date_ajout) VALUES('.$uid.','.$duid.','.time().')';
			return $this->mysql->query($sql2);
		}
	}
	public function ajoutPlusieursAmis()
	{
		if(isset($_POST['amis']) and !empty($_POST['amis']))
		{
			$res=true;
			foreach($_POST['amis'] as $ami)
			{
				if(!$this->ajoutAmi($ami))
				{
					$res=false;
				}
			}
			if($res)
			{
				$template='<div class="message">Amis rajoutés :)</div>';
			}
			else
			{
				$template='<div class="error">Au moins un ami n\'a pas été ajouté :(</div>';
			}
			return $template;
		}
		return "";
	}
	public function connexion()
	{
		if(isset($_POST['id_Connexion'],$_POST['id_MotDePasse']) and !empty($_POST['id_Connexion']) and !empty($_POST['id_MotDePasse']))
		{
		
			if($this->auth($_POST['id_Connexion'],$_POST['id_MotDePasse']))
			{
				$template= '<div class="message">Connexion réussie</div>';
			}
			else
			{
				$template= '<div class="erreur">Connexion échouée</div>';
			}
		}
		else
		{
			$template= '<div class="erreur">CVous n\'avez pas remplis tous les champs</div>';
		}
		if($this->uetat>1)
		{
			$sql='UPDATE utilisateurs SET uetat=1,cvalidation="" WHERE uid='.$this->getUid();
			$this->mysql->query($sql);
		}
		$this->connexionm=$template;
	}
	public function motdepasseperdu()
	{
		$template="";
		if(isset($_POST['email'],$_POST['pseudo']) and !empty($_POST['email']) and !empty($_POST['pseudo']))
		{
			$email=$this->mysql->real_escape_string($_POST['email']);
			$pseudo=$this->mysql->real_escape_string($_POST['pseudo']);
			
			$sql='SELECT uid FROM utilisateurs WHERE UPPER(pseudo)=UPPER("'.$pseudo.'") AND LOWER(email)=LOWER("'.$email.'")';
			$req=$this->mysql->query($sql);
			if($data=$req->fetch_row())
			{
				$user=new Utilisateurs($data[0]);
				$hash=$user->genConfirmCode();
				$sql='UPDATE utilisateurs SET cvalidation="'.$hash.'",uetat=3 WHERE uid='.$user->getUid();
				$this->mysql->query($sql);
				$email=trim(file_get_contents(PARTIAL.'mail_lostpassword'));
				$email=str_replace('{{PSEUDO}}',$user->getLogin(),$email);
				$email=str_replace('{{IP}}',$_SERVER['REMOTE_ADDR'],$email);
				$email=str_replace('{{CONFIRMCODE}}',$hash,$email);
				$mailo=new Email();
				$mailo->send($user->getEmail(),"Redéfinition de votre mot de passe",$email,$user->getLogin());
				$template='<div class="message">Un e-mail avec les informations concernants la redéfinition de votre mot de passe vient de vous être envoyé</div>';
			}
			else
			{
				$template='<div class="erreur">Utilisateur introuvable</div>';
			}
		}
		elseif(isset($_GET['confirmcode']) and !empty($_GET['confirmcode']))
		{
			$hash=$this->mysql->real_escape_string($_GET['confirmcode']);
			$sql='SELECT uid FROM utilisateurs WHERE cvalidation="'.$hash.'"';
			$req=$this->mysql->query($sql);
			if($data=$req->fetch_row())
			{
				$uid=$data[0];
				$user=new Utilisateurs($uid);
				if(isset($_POST['password'],$_POST['password2']) and !empty($_POST['password']) and !empty($_POST['password2']))
				{
					$user->updatePassword($uid,$_POST['password']);
					$sql='UPDATE utilisateurs SET uetat=1,cvalidation="" WHERE uid='.$uid;
					$this->mysql->query($sql);
					$template="<div class='message'>Mot de passe mis à jour avec succès ! Vous pouvez maintenant vous connecter</div>";
				}
				else
				{
					$template=file_get_contents(PARTIAL.'password_form.xhtml');
				}
			}
			else
			{
				$template="<div class='erreur'>Aucune demande de redéfinition de mot de passe trouvée pour ce code";
			}
		}
		else
		{
			$template=file_get_contents(PARTIAL.'motdepasseperdu.xhtml');
		}
		return $template;
	}
				
	public function deconnexion()
	{
		$this->uid=-1;
		// On détruit la session et ses variables;
		session_destroy();
		session_unset();
		unset($this);
	}
	public function affichUser($template,$user)
	{
		$template=str_replace('{{UID}}',$user->uid,$template);
		$template=str_replace('{{PSEUDO}}',$user->pseudo,$template);
		$template=str_replace('{{EMAIL}}',$user->email,$template);
		$template=str_replace('{{DESCRIPTION}}',htmlspecialchars($user->description),$template);
		$template=str_replace('{{AVATAR}}',$user->avatar,$template);
		$template=str_replace('{{DATEINSCR}}',$user->date_publication,$template);
		$template=str_replace('{{JOURINSCR}}',date('j',$user->dinscription),$template);
		$template=str_replace('{{MOISINSCR}}',date('n',$user->dinscription),$template);
		$template=str_replace('{{ANNEEINSCR}}',date('Y',$user->dinscription),$template);
		return $template;
	}
	public function listeUtilisateurs($nicedisplay=false)
	{
		$filter="";
		$filter2="";
		if($nicedisplay)
		{
			if(isset($_GET['startl'],$_GET['page']) and !empty($_GET['startl']) and !empty($_GET['page']))
			{
				$startl=$this->mysql->real_escape_string($_GET['startl']);
				$page=intval($_GET['start1']);
				$page=$page > 0 ? $page : 1;
			}
		}
		if($this->estConnecte())
		{
			$uid=$this->getUid();
			$sql='SELECT u.uid,u.pseudo,u.email,u.dinscription,u.ipinscription,u.uetat,a.date_ajout,u.avatar FROM utilisateurs u LEFT JOIN amis a ON (a.euid=u.uid AND a.duid='.$uid.') OR (a.euid='.$uid.' AND a.duid=u.uid) ORDER BY u.pseudo';
		}
		else
		{
			$sql='SELECT u.uid,u.pseudo,u.email,u.dinscription,u.ipinscription,u.uetat,u.avatar FROM utilisateurs u ORDER BY u.pseudo';
		}
		return queryToArray($this->mysql->query($sql));
	}
	public function afficheComplet()
	{
		$param=requestObject('Param');
		$uid=intval($param->getValue());
		if($uid>0)
		{
			$template=file_get_contents(PARTIAL.'membre_complet.xhtml')
			$ouser=new Utilisateurs($uid);
			$template=$this->affichUser($template,$ouser);
			return $remplate;
		}
	}
}
