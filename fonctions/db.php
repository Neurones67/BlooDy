<?php
/*
	Classe de gestion des connexions MySQL héritant de la classe de base de php
	Par Neurones67
	Licence GPLv3
	11/07/10
*/
class DB extends MySQLi
{
	private $reqcount=0;
	private function initParams()
	{
		$this->set_charset('UTF-8');
	}
	function __destruct()
	{
		$this->close();
	}
	function query($req)
	{
		if($this->reqcount==0)
		{
			$this->initParams();
		}
		$this->reqcount++;
		$res = parent::query($req) or die($this->error.' requête : '.$req);
		return $res;
	}
	function count()
	{
		return $this->reqcount;
	}
} 
