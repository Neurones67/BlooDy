<?php
/*
	Classe permettant d'envoyer des emails
	Par Marc
	Pour BlooDy
	Licence GPL
	22/10/10
	
	Cette classe utilise la classe Mail de pear (http://pear.php.net/package/Mail)
	Elle défini les variables par défaut utilisés par le serveur, ici, c'est SMTP sur le port 25 sur localhost (sans authentification)
*/
require_once('Mail.php'); 
class EMail
{
	private $backend;
	private $params;
	private $mailobject;
	private $headers;
	public function __construct()
	{
		$this->backend="smtp";
		$this->params=array(
			"host"=>"localhost",
			"port"=>"25");
		$this->mailobject=Mail::factory($this->backend,$this->params);
		$this->headers=array(
			"From"=> "BlooDy <contact@bd.ceypasbien.com>",
			"Sender"=> "BlooDy <contact@bd.ceypasbien.com>",
			"Reply-To"=> "BlooDy <contact@bd.ceypasbien.com>",
			"Content-Type" => "text/plain; charset=UTF-8",
			"X-Mailer"=> "BlooDy",
			"X-Origin"=> "http://bd.ceypasbien.com");
	}
	public function __destruct()
	{
		unset($this->backend);
		unset($this->mailobject);
		unset($this->params);
		unset($this->headers);
	}
	public function send($recipients,$subject,$message,$user="")
	{
		$lheaders=$this->headers;
		if(!empty($user))
		{
			$lheaders['To']=$user." <".$recipients.">";
		}
		else
		{
			$lheaders['To']=$recipients;
		}
		$lheaders['Subject']=$subject;	
		return $this->mailobject->send($recipients,$lheaders,$message);
	}
	
}
