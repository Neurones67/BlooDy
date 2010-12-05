<?php
/*
 *	Classe de gestion des Éditeurs
 *	Par Marc
 *	Pour BlooDy
 *	5/12/10
 */
 class Editeur
 {
 	private $eid;
 	private $enom;
 	private $evalide;
 	
 	private $mysql;
 	public function __construct($eid=0)
 	{
 		$this->mysql=requestObject('MySQL');
 		if($eid>0)
 		{
 			$this->init_data($uid);
 		}
 		else
 		{
 			$this->eid=-1;
 			$this->enom="";
 			$this->evalide=-1;
 		}
 	}
 	public function __destruct()
 	{
 		unset($this->eid);
 		unset($this->enom);
 	}
 	
 	private function init_data($eid)
 	{
 		$sql='SELECT enom,evalide FROM editeurs WHERE eid='.$eid;
 		$req=$this->mysql->query($sql);
 		if($data=$req->fetch_object())
 		{
 			$this->eid=$eid;
 			$this->enom=$data->enom;
 			$this->evalide=$data->evalide;
 		}
 	}
 	public function getEid()
 	{
 		return $this->eid;
 	}
 	public function getNom()
 	{
 		return $this->enom;
 	}
 	public function getValide()
 	{
 		return $this->evalide;
 	}
 	public function estValide()
 	{
 		return $this->evalide>0;
 	}
 	
 	public function add($enom)
 	{
 		$enom=$this->mysql->real_escape_string($enom);
 		$sql='INSERT INTO editeurs (enom) VALUES ("'.$enom.'")';
 		return $this->mysql->query($sql);
 	}
 	public function delete($eid)
 	{
 		$eid=intval($eid);
 		$sql='DELETE FROM editeurs WHERE eid='.$eid;
 		return $this->mysql->query($sql);
 	}
 	public function recherche($enom)
 	{
 		$enom=$this->mysql->real_escape_string($enom);
 		$sql="SELECT eid,enom FROM editeurs WHERE UPPER(enom) LIKE UPPER('%".$enom."%')";
 		return queryToArray($this->mysql->query($sql));
 	}
 	public function liste()
 	{
 		$sql='SELECT eid,enom,evalide FROM editeurs';
 		return queryToArray($this->mysql->query($sql));
 	}
 }
