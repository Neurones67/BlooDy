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
		unset($reqcount);
	}
	function query($req)
	{
		if($this->reqcount==0)
		{
			$this->initParams();
		}
		$this->reqcount++;
		if(!($res = parent::query($req)))
		{
			ob_start();
			debug_print_backtrace();
			file_put_contents("mysql_errors",date('[d/m/Y - H:i:s]').' Erreur MySQL : '.$this->error.'
Backtrace:
'.ob_get_contents());
			ob_end_clean();
			die("Une erreur s'est produite: ". $this->error);
		}
		return $res;
	}
	function count()
	{
		return $this->reqcount;
	}
} 
