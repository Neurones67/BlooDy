<?php
/*
	Script pour tester certaines choses sur le site
	Have fun !
	
	Conseil : Gardez vos tests commentés, ne les supprimez pas, vous aurez surement besoin de les refaire :)
*/
// Test des mails : Résultat au 22/10/10 : Ça marche :)
/*
include('mail.php');
$object=new EMail();
$object->send('marc@moonscoop-fan.com','Test d\'envoi de mails','hééhooo ça marche ? :D',"Marc");
*/

// Test de la gestion d'erreur : Résultat au 22/10/10 : Ça marche !
/*
bd@web:~/public_html/fonctions$ cat mysql_errors 
[22/10/2010 - 17:26:54] Erreur MySQL : La table 'bd.trucs' n'existe pas
Backtrace:
#0  DB->query(SELECT * from trucs) called at [/home/bd/public_html/fonctions/test.php:19]
*/
/*
include('db.php');
include('password.php');
$mysql=new DB($host,$username,$password,$base);
$mysql->query('SELECT * from trucs');
*/
