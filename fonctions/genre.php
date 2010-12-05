<?php
/*
 *	Classe de gestion des Genres
 *	Par Marc
 *	Pour BlooDy
 *	5/12/10
 */
 class Genre
 {
 	private $gid;
 	private $gnom;
	
 	private $mysql;
 	public function __construct($gid=0)
 	{
 		$this->mysql=requestObject('MySQL');
 		if($gid>0)
 		{
 			$this->init_data($uid);
 		}
 		else
 		{
 			$this->gid=-1;
 			$this->gnom="";
 		}
 	}
 	public function __destruct()
 	{
 		unset($this->gid);
 		unset($this->gnom);
 	}
 	
 	private function init_data($gid)
 	{
 		$sql='SELECT gnom,svalide FROM genre WHERE gid='.$gid;
 		$req=$this->mysql->query($sql);
 		if($data=$req->fetch_object())
 		{
 			$this->gid=$gid;
 			$this->gnom=$data->gnom;
 		}
 	}
 	public function getgid()
 	{
 		return $this->gid;
 	}
 	public function getNom()
 	{
 		return $this->gnom;
 	}
 	
 	public function add($gnom)
 	{
 		$gnom=$this->mysql->real_escape_string($gnom);
 		$sql='INSERT INTO genre(gnom) VALUES ("'.$gnom.'")';
 		return $this->mysql->query($sql);
 	}
 	public function delete($gid)
 	{
 		$gid=intval($gid);
 		$sql='DELETE FROM genre WHERE gid='.$gid;
 		return $this->mysql->query($sql);
 	}
 	public function recherche($gnom)
 	{
 		$gnom=$this->mysql->real_escape_string($gnom);
 		$sql="SELECT gid,gnom FROM genre WHERE UPPER(gnom) LIKE UPPER('%".$gnom."%')";
 		return queryToArray($this->mysql->query($sql));
 	}
 	public function liste()
 	{
 		$sql='SELECT gid,gnom,svalide FROM genre';
 		return queryToArray($this->mysql->query($sql));
 	}
 }
