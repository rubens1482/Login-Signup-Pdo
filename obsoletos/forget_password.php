<?php
session_start();
require_once("class/class.user.php");
$altera = new USER();


$email = $_POST['email'];
$chave = $altera->geraChaveAcesso($email);

	if($chave)
		{
			$link = '<a href="http://www.localhost/Login-Signup-Pdo/set_new_password.php?chave='.$chave . '">http://www.localhost/Login-Signup-Pdo/set_new_password.php?chave='. $chave .'</a>';
			//$login->redirect("set_new_password.php?chave=".$chave);
			//echo '<a href="http://www.localhost/Login-Signup-Pdo/set_new_password.php?chave='.$chave . '">http://www.localhost/Login-Signup-Pdo/set_new_password.php?chave='. $chave .'</a>';
			echo $link;
		} else {
		echo "Dados Incorretos";?>
		<label>Já tem cadastro? <a href="index.php"> Efetuar Login </a></label>
		<?php
	}	
?>
