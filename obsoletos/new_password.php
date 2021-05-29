<?php
include "class/class.user.php";	
$checar = new USER();

$chave = strip_tags($_POST["chave"]);
$umail = strip_tags($_POST["email"]);
$new_password = strip_tags($_POST["new_password"]);
/*
$umail = preg_replace('/[^[:alnum:]]_.-@/','',$umail);
$chave = preg_replace('/[^[:alnum:]]/','',$chave);
$new_password = addslashes($new_password);
*/
$result = $checar->CheckChave($umail,$chave);

/*
if ($result = $checar->CheckChave($umail,$chave)){
	echo '<h1>login efetuado com sucesso</h1>';
} else {
	echo "erro";
}
*/


?>
