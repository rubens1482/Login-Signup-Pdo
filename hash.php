<?php
/**
 * We just want to hash our password using the current DEFAULT algorithm.
 * This is presently BCRYPT, and will produce a 60 character result.
 *
 * Beware that DEFAULT may change over time, so you would want to prepare
 * By allowing your storage to expand past 60 characters (255 would be good)
 */
	// se foi clicado no botão "limpar"
	if(isset($_POST['limpar'])) {
		?>
		<!-- exibe o formulario de gerar o hash -->
		
		<?php
	} else {
		
	}
	 if(isset($_POST['esqueciasenha'])) { 
		 // se foi clicado no botão esqueciasenha e o campo hash estiver com caracter...
		 if (isset($_POST['hash']) && $_POST['hash'] == '') {
			  // caso contrário, exibe a mensagem de erro
			echo "O campo a ser gerado a senha está vazio!";
			
		 } else {
			;
			// gera o hash
			 $hash = $_POST['hash'];
			 // e exibe o hash gerado
			 echo "O texto correspondente a valor digitado é: <br>";
			 echo "HASH: " . password_hash($hash, PASSWORD_DEFAULT);
			 ?>
			 <form method="post" action="#">
				 <input type="submit" name="limpar" value="limpar dados">
			 </form>
			 <?php
		 }
	 } else {
		 // caso contrário, mostra que não foi clicado no botão "esqueciasenha"
		
	 }
	 
	
 ?>
  <form method="post" action="#">
			<div class="input_form_login">
				<label for="email_aluno">Senha:</label>
				<input type="text" id="hash" name="hash" placeholder="seu hash">
			</div> 

			<div class="input_form_login">
				<input type="submit" name="esqueciasenha" value="gerar_hash">
				<input type="submit" name="limpar" value="limpar dados">
			</div>
		</form>
