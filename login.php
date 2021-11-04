<?php
require_once "config/config_user.php";
?>
<!DOCTYPE html>
<html lang="pt_Br">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> Sistema de Livro Caixa em PHP </title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="assets/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script> 
<style type="text/css">
	.login-form {
		width: 385px;
		margin: 70px auto;
	}
    .login-form form {        
    	margin-bottom: 15px;
		margin-top: 15px;
        background: #e3f6ce;
        box-shadow: 5px 5px 5px 5px rgba(0.5, 2, 6, 0.4);
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .login-btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .input-group-addon .fa {
        font-size: 18px;
    }
    .login-btn {
        font-size: 15px;
        font-weight: bold;
    }
	.social-btn .btn {
		border: none;
        margin: 10px 3px 0;
        opacity: 1;
	}
    .social-btn .btn:hover {
        opacity: 0.9;
    }
	.social-btn .btn-primary {
        background: #507cc0;
    }
	.social-btn .btn-info {
		background: #64ccf1;
	}
	.social-btn .btn-danger {
		background: #df4930;
	}
    .or-seperator {
        margin-top: 20px;
        text-align: center;
        border-top: 1px solid #ccc;
    }
    .or-seperator i {
        padding: 0 10px;
        background: #f7f7f7;
        position: relative;
        top: -11px;
        z-index: 1;
    }   
</style>
</head>
<body>
</br>
<div class="login-form">
	
    
		<!-- CODIGO PHP PARA MOSTRAR O FORMULARIO DE RECUPERAÇÃO DE SENHA  -->
		<?php
			if(isset($_GET['recuperar']) && $_GET['recuperar'] == 'sim'){
		?>
			
			
		<!--  CAMPO "E-MAIL" PARA RECUPERAR -->
		<form  method="post" id="login-recover" action="recuperar.php">
			<h2 class="text-center">Password Recovery</h2>
			<div id="error">
			<!--  MENSAGEM DE ERRO -->
			<?php
				if(isset($status))
				{
					?>
					<div class="alert alert-danger">
					   <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $status; ?> 
					</div>
					<?php
				}
			?>
			</div><!--   -->
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input type="text" class="form-control" name="txt_email" placeholder="E-mail" >	
				</div>
			</div>
			<!--  CAMPO "AÇÃO" PARA VALIDAR A RECUPERAÇÃO -->
			<div class="form-group">
				<input type="hidden" class="form-control" name="acao"  value="recuperar">
			</div>
			<!--  BOTÃO "ENVIAR" PARA RECUPERAR SENHA -->    
			<div class="form-group">
				<button type="submit" name="esqueciasenha" class="btn btn-primary login-btn btn-block">Recovery password</button>
			</div>
			<div class="or-seperator"><i>or</i></div>
			<!--  BOTÃO COMANDO REMEMBER ME -->
			<div class="clearfix">
				<label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label>
				<a href="login.php" class="pull-right">Login</a>
			</div>
		</form>
	
		<!-- COMANDO "ELSE" PARA MOSTRAR O FORMULÁRIO DE LOGIN  -->	
		<?php } else { ?>
		<form  method="post" id="login-form">
			<h2 class="text-center">Users Login</h2> 
			<div id="error">
			<!--  MENSAGEM DE ERRO -->
				<?php
					if(isset($status))
					{
						?>
						<div class="alert alert-danger">
						   <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $status; ?> !
						</div>
						<?php
					}
				?>
			</div>
			<!--  CAMPO FORMULÁRIO LOGIN - E-MAIL -->
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" class="form-control" name="txt_uname_email" placeholder="Username" required="required">				
				</div>
			</div>
			<!-- CAMPO FORMULÁRIO LOGIN - SENHA  -->
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input type="password" class="form-control" name="txt_password" placeholder="Password" required="required">
				</div>
			</div> 
			<!-- BOTÃO SUBMIT PARA LOGIN NO SISTEMA  -->			
			<div class="form-group">
				<button type="submit" name="btn-login" class="btn btn-primary login-btn btn-block">Sign in</button>
			</div>
			<!--  SEPARADOR E CAMPOS PARA "REMEMBER ME"  E "ESQUECI MINHA SENHA"-->
			<div class="or-seperator"><i>or</i></div>
			<div class="clearfix">
				<label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label>
				<a href="?recuperar=sim" class="pull-right">Esqueci Minha Senha</a>
			</div>
			<?php } ?>    
		</form>
	<!-- BOTÃO PARA CRIAR CONTA  -->
    <p class="text-center text-muted small">Don't have an account? <a href="sign-up.php">Sign up here!</a></p>
</div>
</body>
</html>                            