<?php
/*
	Classe de gestion des Templates
	Par Marc
	Licence GPLv3
	11/08/10
*/
class Templates
{
	function DynamicLoad($template) // Charge dynamiquement les classes et les fonctions
	{
		if(preg_match_all('/{{(\w+):(\w+)}}/Uis',$template,$result))
		{		
			foreach($result[1] as $key => $class)
			{
				if(class_exists($class) and method_exists($object,$result[2][$key]))
				{
					$object=requestObject($class);
					$template=str_replace($result[0][$key],$object->$result[2][$key](),$template);
				}
			}
		}
		
		if(preg_match_all('/{{(\w+):(\w+)\|(.+)}}/Uis',$template,$result))
		{
			foreach($result[1] as $key => $class)
			{	
					
				if(class_exists($class) and method_exists($object,$result[2][$key]))
				{
					$object=requestObject($class);
					if($result[3][$key] != NULL and method_exists($object,$result[2][$key]))
					{
						$template=str_replace($result[0][$key],$object->$result[2][$key]($result[3][$key]),$template);	
					}
				}
				
			}
		}
		return $template;
	}
	function header_gen($page_title)
	{
		$header=trim(file_get_contents(PARTIAL.'header.xhtml'));
		$header=str_replace('{{Title}}',$page_title,$header);
		return $header;
	}
	function footer()
	{
		$footer=trim(file_get_contents(PARTIAL.'footer.xhtml'));
		return $footer;
	}
	function RequestTemplate($template)
	{

		$titre=$template;
		if(preg_match('/^(.*)-(.*)$/isU',$template,$results))
		{
			//echo $results[2];
			$template=$results[1];
			registerObjectP('Param',$results[2]);
		}
		if(file_exists(PROOT.$template.'.xhtml'))
		{
			$template=trim(file_get_contents(PROOT.$template.'.xhtml'));
		}
		elseif(file_exists(PROOT.$template.'/index.xhtml'))
		{
			if(!preg_match('/\/$/is',$_SERVER['REQUEST_URI']))
			{
				header('301 Moved Permanently');
				header('location: /'.$template.'/');
			}
			$template=trim(file_get_contents(PROOT.$template.'/index.xhtml'));
		}
		else
		{
			$template=trim(file_get_contents(PROOT.'404.xhtml'));
			header('404 Not Found');
		}
		if(preg_match('/{{HEADER:(.*)}}/U',$template))
		{
			$template=preg_replace('/{{HEADER:(.*)}}/',$this->header_gen('$1'),$template);
		}
		if(preg_match('/{{SIMPLEHEADER:(.*)}}/U',$template))
		{
			$template=preg_replace('/{{SIMPLEHEADER:(.*)}}/U',$this->simple_header_gen('$1'),$template);
		}
		if(preg_match('/{{Redirect:(.*)}}/isU',$template,$redirect))
		{
			header('location: '.$redirect[1]);
			exit();
		}
		
		if(strstr($template,'{{FOOTER}}'))
		{
			$template=str_replace('{{FOOTER}}',$this->footer(),$template);
		}
		$template=$this->DynamicLoad($template);
		if(strstr($template,'{{SCRIPT}}'))
		{
			$template=str_replace('{{SCRIPT}}',$_SERVER['REDIRECT_URL'],$template);
		}
		if(strstr($template,'{{LOGIN}}'))
		{
			$template=str_replace('{{LOGIN}}',requestObject('Utilisateurs')->getLogin(),$template);
		}
		return $template;
	}
}
