<?php
/*
 *	Classe de gestion des Séries
 *	Par Marc
 *	Pour BlooDy
 *	5/12/10
 */
 class Editeur
 {
 	private $sid;
 	private $snom;
 	private $svalide;
 	
 	private $mysql;
 	public function __construct($sid=0)
 	{
 		$this->mysql=requestObject('MySQL');
 		if($sid>0)
 		{
 			$this->init_data($uid);
 		}
 		else
 		{
 			$this->sid=-1;
 			$this->snom="";
 			$this->svalide=-1;
 		}
 	}
 	public function __destruct()
 	{
 		unset($this->sid);
 		unset($this->snom);
 	}
 	
 	private function init_data($sid)
 	{
 		$sql='SELECT snom,svalide FROM series WHERE sid='.$sid;
 		$req=$this->mysql->query($sql);
 		if($data=$req->fetch_object())
 		{
 			$this->sid=$sid;
 			$this->snom=$data->snom;
 			$this->svalide=$data->svalide;
 		}
 	}
 	public function getsid()
 	{
 		return $this->sid;
 	}
 	public function getNom()
 	{
 		return $this->snom;
 	}
 	public function getValide()
 	{
 		return $this->svalide;
 	}
 	public function estValide()
 	{
 		return $this->svalide>0;
 	}
 	
 	public function add($snom)
 	{
 		$snom=$this->mysql->real_escape_string($snom);
 		$sql='INSERT INTO series(snom) VALUES ("'.$snom.'")';
 		return $this->mysql->query($sql);
 	}
 	public function delete($sid)
 	{
 		$sid=intval($sid);
 		$sql='DELETE FROM series WHERE sid='.$sid;
 		return $this->mysql->query($sql);
 	}
 	public function recherche($snom)
 	{
 		$snom=$this->mysql_real_escape_string($snom);
 		$sql="SELECT sid FROM series WHERE UPPER(snom) LIKE UPPER('%".$snom."%')";
 		return queryToArray($this->mysql->query($sql));
 	}
 	public function liste()
 	{
 		$sql='SELECT sid,snom,svalide FROM series';
 		return queryToArray($this->mysql->query($sql));
 	}
 }
