<?php
/*
	Classe de stockage des paramètres
	Par Marc
	Licence GPLv3
	2/08/10
*/
class Param
{
	var $value;
	function Param($arg="")
	{
		$this->value=$arg;
	}
	function __destructor()
	{
		unset($this->value);
	}
	function getValue()
	{
		return $this->value;
	}
	function setValue($arg)
	{
		$this->value=$arg;
	}
}

