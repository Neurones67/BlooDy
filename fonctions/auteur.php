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
	private $adnaissance;
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
		$sql='SELECT anom,aprenom,biographie,adnaissance,aphoto,avalide FROM auteurs WHERE aid='.$aid;
		$req=$this->mysql->query($sql);
		if($data=$req->fetch_object())
		{
			$this->aid=$aid;
			$this->anom=$data->anom;
			$this->aprenom=$data->aprenom;
			$this->biographie=$data->biographie;
			$this->adnaissance=$data->adnaissance;
			$this->avalide=$data->avalide;
			if(!empty($data->aphoto))
			{
				$this->aphoto=$data->aphoto;
			}
			else
			{
				$this->aphoto=str_replace(ROOT,'',AVATARS.'ANONYME.JPG');
			}
		}
	}
	
	public function recherche($nom,$prenom="")
	{
		$filter='AND';
		if(empty($prenom))
		{
			$prenom=$nom;
			$filter='OR';
		}
		$nom=strtoupper($this->mysql->real_escape_string($nom));
		$prenom=strtoupper($this->mysql->real_escape_string($prenom));
		$sql='SELECT aid,anom,aprenom,biographie,adnaissance,aphoto,avalide FROM auteurs WHERE UPPER(anom) LIKE "%'.$nom.'%" '.$filter.' UPPER(aprenom) LIKE "%'.$prenom.'%"';
		return queryToArray($this->mysql->query($sql));
	}
	public function rechercheForm()
	{
		$template="";
		if(isset($_POST['motcle']) and !empty($_POST['motcle']))
		{
			$motcle=$_POST['motcle'];
			$data=$this->recherche($motcle);
			$template='<ul>';
			foreach($data as $auteur)
			{
				$template.='<li><a href="/auteur-'.$auteur['aid'].'.html">'.$auteur['anom'].' '.$auteur['aprenom'].'</a> Né le '.$auteur['adnaissance'].' <!--<a href="/auteur-'.$auteur['aid'].'.html?delete=true">Supprimer</a>--></li>';
			}
			$template.='</ul>';
		}
		return $template;
	}
	public function ajout($nom,$prenom="",$biographie="",$datenaissance="",$aphoto="")
	{
		$nom=$this->mysql->real_escape_string($nom);
		$prenom=$this->mysql->real_escape_string($prenom);
		$biographie=$this->mysql->real_escape_string($biographie);
		$datenaissance=$this->mysql->real_escape_string($datenaissance);
		$aphoto=$this->mysql->real_escape_string($aphoto);
		$sql='INSERT INTO auteurs(anom,aphoto,biographie,aprenom,adnaissance) VALUES("'.$nom.'","'.$aphoto.'","'.$biographie.'","'.$prenom.'","'.$datenaissance.'")';
		if($this->mysql->query($sql))
		{
			return $this->mysql->insert_id; // Renvoi l'identifiant de l'auteur qu'on vient d'ajouter
		}
		else
		{
			return false;
		}
	}
	public function ajoutForm()
	{
		$template="";
		if(isset($_POST['nomAuteur'],$_POST['prenomAuteur'],$_POST['jourNaissance'],$_POST['moisNaissance'],$_POST['anneeNaissance'],$_POST['biographie']) and !empty($_POST['nomAuteur']))
		{
			$nom=$_POST['nomAuteur'];
			$prenom=$_POST['prenomAuteur'];
			$biographie=$_POST['biographie'];
			$myear=intval($_POST['anneeNaissance'])%2100;
			if($myear<100)
			{
				$myear=$myear+1900;
			}
			$mmonth=intval($_POST['moisNaissance'])%13;
			$mday=intval($_POST['jourNaissance'])%31;
			$mdate='"'.$myear.'-'.$mmonth.'-'.$mday.'"';
			if($this->ajout($nom,$prenom,$biographie,$mdate))
			{
				$template="<div class='message'>L'auteur a bien été ajouté !</div>";
			}
			else
			{
				$template="<div class='erreur'>Erreur lors de l'enregistrement de l'auteur";
			}
		}
		return $template;
	}
	public function affichAuteur($template,$auteur)
	{
		$template=str_replace('{{NOMAUTEUR}}',$auteur->anom,$template);
		$template=str_replace('{{PRENOMAUTEUR}}',$auteur->aprenom,$template);
		$template=str_replace('{{DATEAUTEUR}}',$auteur->adnaissance,$template);
		$template=str_replace('{{IDAUTEUR}}',$auteur->aid,$template);
		$template=str_replace('{{BIOGRAPHIE}}',$auteur->biographie,$template);
		$template=str_replace('{{PHOTO}}',requestObject('Image')->image_redim($data->aphoto,200,200),$template);

		return $template;
	}
	public function afficheComplet()
	{
		$param=requestObject('Param');
		$aid=intval($param->getValue());
		if($aid>0)
		{
			$template=file_get_contents(PARTIAL.'auteur_complet.xhtml');
			$oauteur=new Auteur($aid);
			$template=$this->affichAuteur($template,$oauteur);
			return $template;
		}
	}
}
