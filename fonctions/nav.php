<?php
/* Fichier de gestion de la navigation
 * Par Marc
 * Licence GPLv3
 * 16/09/10
 * Cette Classe permet de centraliser toutes les fonctions relatives à gestion de la navigation, comme affichage d'un menu ou pas, de la pagination, la gestion d'affichage etc...
*/
class Nav
{
	private $user;
	public function Nav()
	{
		$this->user=requestObject('Utilisateurs');
	}
	public function LogStatus() // Retourne le lien de connexion ou de gestion de profil en fonction du statut du visiteur (enregistré ou pas)
	{
		if($this->user->getLogged())
		{
			return '<a href="/Profil/">Profil</a>';
		}
		else
		{
			return '<a href="/Connexion.html">Connexion</a>';
		}
	}
	public function UserErrorHandler($message,$errors) // Permet d'afficher les erreurs (tableau errors) sous forme de liste avec le message message
	{
		if(count($errors)>0)
		{
			$res='<div class="error">'.$message.' : <ul>';
			foreach($errors as $error)
			{
				$res.='<li>'.$error.'</li>';
			}
			$res.='</ul></div>';
			return $res;
		}
	}
	// Génère les lignes de pagination, l'url de destination doit contenir {{PAGE}} qui sera remplacé par le numéro de la page, 
	// $page, est la page actuelle, permet sa mise en valeur
	// $nbpages et le nombre de page qu'on doit afficher
	public function pagination($url,$page,$nbpages) 
	{
		$cache='<div class="pagination">';
		if($page>1)
		{
			$cache.='<a href="'.str_replace('{{PAGE}}',$page-1,$url).'"><-- Page Précédente - </a>';
		}
		for($i=1;$i<=$nbpages;$i++)
		{

			if($i>1)
			{
				$cache.=' - ';
			}
			if($i==$page)
			{
				$cache.='<span class="thispage">'.$i.'</span> ';
			}
			else
			{
				$cache.='<a href="'.str_replace('{{PAGE}}',$i,$url).'">'.$i.'</a> ';
			}
		}
		if($page<$nbpages)
		{
			$cache.='<a href="'.str_replace('{{PAGE}}',$page+1,$url).'"> - Page Suivante --></a>';
		}
		$cache.='</div>';
		return $cache;
	}
	
	// Afficher un lien si un utilisateur est un admin.
	public function adminLink()
	{
		if($this->user->hasAdminAccess())
		{
			return '<a href="/Admin/">Panneau d\'Administration</a>';
		}
	}
	public function menuGauche()
	{
		// Permet de swticher entre le menu connecté et non connecté
		if(this->user->estConnecte())
		{
			$template=trim(file_get_contents(PARTIAL.'menuGaucheCo.xhtml'));
		}
		else
		{
			$template=trim(file_get_contents(PARTIAL.'menuGauche.xhtml'));
		}
		return $template;
	}
}
