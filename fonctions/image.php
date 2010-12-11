<?php
/*
	Classe de gestion des images
	Par Marc
	Pour Bloody
	8/12/10
*/
class Image
{
	private $formats; // Formats acceptés (mime)
	public function Image()
	{
		$this->formats=array('image/jpeg','image/pjpeg','image/png','image/gif');
	}
	// Permet de récupérer le fichier uploadé et vérifie si c'est bien une image, si oui, retourne son chemin temporaire, sinon il renvoie false.
	public function uploadImage()
	{
		$res=false;
		if ((isset($_FILES['nomfichier']) and ($_FILES['nomfichier']['error'] == UPLOAD_ERR_OK))) 
		{   
			$name=$_FILES['nomfichier']['name'];
			$type=$_FILES['nomfichier']['type'];
			$patht=$_FILES['nomfichier']['tmp_name'];
			if(file_exists($patht) and in_array($type,$this->formats) and in_array(image_type_to_mime_type(exif_imagetype($patht)),$this->formats))
			{
				$res=$_FILES['nomfichier'];
			}
			else
			{
				$res=false;
			}
		}
		return $res;
	}
	public function uploadAvatar()
	{
		$template="";
		$user=requestObject('Utilisateurs');
		if($file=$this->uploadImage())
		{
			$npath=AVATARS.$user->getUid().'_'.basename($file['name']);
			$info = pathinfo($file['name']);
			$pathredim=AVATARS.$user->geTUid().'_'.basename($file['name'],'.'.$info['extension']).'_32_32'.'.'.$info['extension'];
			move_uploaded_file($file['tmp_name'],$npath);
			$this->redim_img($npath,32,32,$pathredim);
			$pathnav=str_replace(ROOT,'/',$pathredim);
			$user->updateAvatar($user->getUid(),$pathnav);
			$template='<div class="message">Votre avatar a bien été enregistré : <img src="'.$pathnav.'" alt="avatar" /> </div>';
		}
		else
		{
			$template="<div class='error'>Une erreur est survenue, pas de fichiers ? Pas bon format ?</div>";
		}
		return $template;
			
	}
	public function uploadCover()
	{
		$template="";
		if($file=$this->uploadImage())
		{
			$npath=COUVERTURES.'/'.basename($file['name']);
			$info = pathinfo($file['name']);
			$pathredim=COUVERTURES.basename($file['name'],'.'.$info['extension']).'32_32'.'.'.$info['extension'];
			move_uploaded_file($file['tmp_name'],$npath);
			$this->redim_img($npath,32,32,$pathredim);
			$pathnav=str_replace(ROOT,'/',$pathredim);
			$template='<div class="message">La couverture a bien été enregistré : <img src="'.$pathnav.'" alt="avatar" /> </div>';
		}
		else
		{
			$template="<div class='error'>Une erreur est survenue, pas de fichiers ? Pas bon format ?</div>";
		}
		return $template;
			
	}
	// Fonction pour redimentionner une image, Merci à Cédric :)
	function redim_img($adresseimage, $max_l, $max_h, $emplacement)
	{
		$infosimage = getimagesize($adresseimage);
		$h_image = $infosimage[1]; // On stocke la hauteur de l'image dans une variable plus facile à manipuler
		$l_image = $infosimage[0]; // On stocke la largeur de l'image dans une variable plus facile à manipuler
		$diff_h = ($h_image - $max_h); // On stocke la différence de hauteur entre la hauteur de l'image et la hauteur voulue
		$diff_l = ($l_image - $max_l); // On stocke la différence de largeur entre la largeur de l'image et la largeur voulue
	
	
		// Si les dimmensions de l'image de départ sont inférieurs aux dimmensions de l'image souhaitée, on retourne l'image originale
		if ($diff_h <= 0 && $diff_l <= 0)
		{
			copy($adresseimage, $emplacement);
			return true;
		}
	
		// Sinon, on la redimensionne !
		else
		{
	
			// Dans les conditons suivantes, on détermine la nouvelle hauteur & largeur de l'image (flemme d'expliquer le fonctionnement...)
			if ($diff_h < $diff_l)
			{
				$new_l = round($l_image*(($max_l)/$l_image));
				$new_h = round($h_image*(($max_l)/$l_image));
			}
			elseif ($diff_h > $diff_l)
			{
				$new_l = round($l_image*(($max_h)/$h_image));
				$new_h = round($h_image*(($max_h)/$h_image));
			}
			else
			{
				$new_l = $max_l;
				$new_h = $max_h;
			}
	
			// Le traitement de l'image commence ici
	
			// On vérifie si l'image est une image JPEG
			if ($infosimage['mime'] == 'image/jpeg' || $infosimage == 'image/pjpeg')
			{
				$image = imagecreatefromjpeg($adresseimage);
			}
	
			// On vérifie si l'image est une image PNG
			elseif ($infosimage['mime'] == 'image/png')
			{
				$image = imagecreatefrompng($adresseimage);
			}
	
			// On vérifie si l'image est une image GIF
			elseif ($infosimage['mime'] == 'image/gif')
			{
				$image = imagecreatefromgif($adresseimage);
			}
	
			$image_redim = imagecreatetruecolor($new_l, $new_h);
			imagecopyresampled($image_redim, $image, 0, 0, 0, 0, $new_l, $new_h, $l_image, $h_image);
			imagedestroy($image);		
	
			// On vérifie si l'image est une image JPEG
			if ($infosimage['mime'] == 'image/jpeg' || $infosimage == 'image/pjpeg')
			{
				imagejpeg($image_redim , $emplacement, 80);
			}
	
			// On vérifie si l'image est une image PNG
			elseif ($infosimage['mime'] == 'image/png')
			{
				imagepng($image_redim , $emplacement);
		    	}
	
			// On vérifie si l'image est une image GIF
			elseif ($infosimage['mime'] == 'image/gif')
			{	
				imagegif($image_redim , $emplacement);
		    	}
			imagedestroy($image_redim);
			return true;
		}
		
	} // Fin de la fonction
}
