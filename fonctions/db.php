<?php
/*
	Classe de gestion des connexions MySQL héritant de la classe de base de php
	Par Marc
	Licence GPLv3
	11/07/10
*/
class DB extends MySQLi
{
	// Atributs
	// Compteur du nombre de requêtes (pour analyse de performances)
	private $reqcount=0;
	
	
	// Fonction pour définir certains paramètres avant de faire la moindre requête
	private function initParams()
	{
		// Définition de l'encodage de caractère à UTF-8, au cas où ce ne serait pas par défaut dans MySQL
		$this->set_charset('UTF-8');
	}
	
	// Destructeur
	function __destruct()
	{
		// Fermeture de la connexion à MySQL (hérité de la classe MySQLi)
		$this->close();
		// Désenregistrement des attributs
		unset($reqcount);
	}
	
	// Execute un requête, retourne son resultat si réussi, le script meurt sinon.
	function query($req)
	{
		// Si c'est la première requête
		if($this->reqcount==0)
		{
			// on ititialise les paramètres
			$this->initParams();
		}
		// On incrémente le compteur de requêtes
		$this->reqcount++;
		// Si la requête n'arrive pas à s'exécuter
		if(!($res = parent::query($req)))
		{
			// On commencer à mettre la sortie dans un buffer (ob = output buffer)
			ob_start();
			// On affiche l'état de la pile d'appel (pour savoir pourquoi ça plante, d'uù le script est lancé)
			debug_print_backtrace();
			// On écrit tout ça dans un fichier de logs "mysql_errors" avec la date, l'erreur, la requête SQL associée et l'état de la pile d'appel dans le buffer
			file_put_contents("mysql_errors",date('[d/m/Y - H:i:s]').' Erreur MySQL : '.$this->error.' pendant la requête '.$req.'
Backtrace:
'.ob_get_contents());
			// On termine la capture de la sortie et on efface ce qu'on a enregistré
			ob_end_clean();
			// Et on termine le script.
			die("Une erreur s'est produite: ". $this->error);
		}
		// Sinon, tout va bien, on renvoie le résultat.
		return $res;
	}
	
	// Renvoi le nombre de requêtes déjà executées
	function count()
	{
		return $this->reqcount;
	}
} 
