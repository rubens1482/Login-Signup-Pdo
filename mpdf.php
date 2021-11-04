<?php
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
include "config/config_session.php";
include "parameters.php";
include "vendor/mpdf/mpdf/src/Mpdf.php";

// Create an instance of the class:

$mes_hoje = 10;
$ano_hoje = 2021;
$contapd = 1;

$html = '
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<script src="assets/js/bootstrap.js"></script>
		<script src="assets/js/jquery.min.js"></script>
		
		<script src="assets/js/bootstrap.min.js"></script>
		<title> welcome - ' . $userRow['user_name'] . ' </title>
	</head>
	<body> 
	<nav class="navbar navbar-expand-lg navbar-default navbar-fixed-top " >
			<div class="container">
				<div class="navbar-header" >
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"> ok</span>
					<span class="icon-bar"> vamos </span>
					<span class="icon-bar"> terá </span>
				  </button>
				  <a class="navbar-brand" href="http://www.localhost/Login-Signup-Pdo/index.php">Livro Caixa ' . $accountRow['conta'] . '</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse" >
					<ul class="nav navbar-nav">
						<li><a href="?mes=' . date('m') . '&ano=' . date('Y') . '" Hoje &eacute:"<strong> ' . date('d') . ' de ' . mostraMes(date('m')) . '  de ' . date('Y') . '</strong></a></li>	
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						  <span class="glyphicon glyphicon-user"></span> Ir Para <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="list_cat.php"><span class="glyphicon glyphicon-book"></span> Categorias </a></li>
								<li class="divider"></li>
								<li><a href="filtrarpordata.php"><span class="glyphicon glyphicon-calendar"></span> Filtrar por Data </a></li>
								<li class="divider"></li>
								<li><a href="filtrarporfolha.php"><span class="glyphicon glyphicon-list"></span> Filtrar por Folha </a></li>
								<li class="divider"></li>
								<li><a href="profile_user.php"><span class="glyphicon glyphicon-user"></span> Meus Dados </a></h5></li>
								<li class="divider"></li>
							</ul>
						</li>
					</ul>
					<!-- SELECT DO LIVRO ESCOLHIDO -->
					<ul class="nav navbar-nav navbar-left">
						
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						  <span class="glyphicon glyphicon-user"></span>&nbsp;Conta: ' . $accountRow['idconta'] . ' - ' . $accountRow['conta'] .' &nbsp;<span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="profile_user.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Dados da Conta</a></li>
							<li><a href="logout_c.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Alterar Conta</a></li>
						  </ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						  <span class="glyphicon glyphicon-user"></span>&nbsp;Usu&aacute;rio: ' . $userRow['user_name'] . ' &nbsp;<span class="caret"></span></a>
							  <ul class="dropdown-menu">
								<li><a href="profile_user.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Dados do Usuario</a></li>
								<li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Alterar Usuario</a></li>
							  </ul>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav><!-- 	FIM DA NAV BAR PRINCIPAL -->
		<div class="container-fluid" style="margin-top:40px; margin-left:90px; margin-right: 90px; padding: 0; ">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xd-12">	
					<div class="panel panel-primary" >
						<!--  CABEÇALHO DOS BALANCOS -->
						<div class="panel-heading"  >
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xd-6" style="font-family:courier,arial,helvetica;text-align: left;">
									<font color="black" size="3" style="font-family:courier,arial,helvetica;"><strong> BALANCOS DE MOVIMENTO - LIVRO Nº ' . $livro . '</strong></font>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xd-6" style="text-align: right;">
									<font color="black" size="3" style = "font-family:courier,arial,helvetica;"><strong> DEMOSTRATIVO MENSAL </strong></font>
								</div>
							</div>	
						</div>
						<!--  INICIO DO CORPO DO BALANCO -->
						' ; 
						 //codigo php para busca dos dados do balanço mensal'
							$mes_hoje = 10;
							$ano_hoje = 2021;
							$contapd = 1;
							$mostrar = new MOVS;
							$dados = $mostrar->bal_pormes($contapd,$mes_hoje, $ano_hoje);
							
							foreach($dados as $linha){
							$saldo_aa = $linha['saldo_ano_ant'];
							$saldo_ant = $linha['saldo_anterior_mes'];
							$entradas_m = $linha['credito_mes'];
							$saidas_m = $linha['debito_mes'];
							$resultado_mes = $linha['saldo_atual_mes'];
							$ent_acab = $linha['credito_acum_ano'];
							$sai_acab = $linha['debito_acum_ano'];
							$bal = $linha['bal_mes'];
							$bal_bal = $linha['bal_acum'];
							$saldo_acab = $linha['saldo_acum_ano'];
							//$saldo_acab = $saldo_aa + $ent_acab - $sai_acab;
							
						} 
						$html .= '
						<div class="panel-body" >
							<div class="row">
								<!--  BALANÇO MENSAL -->
								<div class="col-sm-6" style=" border-top: 1px dashed #f00; border-bottom: 1px dashed #f00;">
									<!--  SALDO ANTERIOR  BALANÇO MENSAL -->
									<div class="row" >
										<div class="col-sm-12" style="text-align: left; border-bottom: 1px dashed #f00; background: #DCDCDC;" >
											<strong><span style="font-family:courier,arial,helvetica;font-size:14px; color: ' . "#006400" . ' ">BALANCO MENSAL </span></strong>
										</div>
									</div>
									<div class="row" >
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;" >
											<strong><span style="font-size:14px; color: ' . "#006400" . '">Saldo Anterior:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color: ' . "#006400" . '"> ' . formata_dinheiro($saldo_ant) . ' </span></strong>
										</div>
									</div>
									<!--  ENTRADAS BALANÇO MENSAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong><span style="font-size:14px; color: ' . "#0000FF" . ' ">Entradas:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong><span style="font-size:14px; color: ' . "#0000FF"  . '"> ' . formata_dinheiro($entradas_m) . ' </span></strong>
										</div>
									</div>
									<!--  SAIDAS BALANÇO MENSAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color: ' . "#C00" . ' ">Saidas:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color:<?php echo "#C00" "> ' . formata_dinheiro($saidas_m) . '</span></strong>
										</div>
									</div>
									<!--  BALANÇO BALANÇO MENSAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong><span style="font-size:14px; color:if($bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ">Balanco:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong>
												<span style="font-family:courier,arial,helvetica;font-size:14px; color:if($bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ">
													' . formata_dinheiro($bal) . '
												</span>
											</strong>
										</div>
									</div>
									<!--  SALDO ATUAL BALANÇO MENSAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color: ' . "#006400" . '">Saldo Atual:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color:<?php echo "#006400" "> ' . formata_dinheiro($resultado_mes) . ' </span></strong>
										</div>
									</div>		
								</div>
								<!--  FIM DO BALANCO MENSAL -->
								<!-- INICIO DO BALANÇO ANUAL  -->	
								<div class="col-sm-6" style=" border-top: 1px dashed #f00; border-bottom: 1px dashed #f00;">
									
									<div class="row" >
										<div class="col-sm-12" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00; background: #DCDCDC;" >
											<strong><span style="font-size:14px; color: ' . "#006400" . '">BALANCO ANUAL</span></strong>
										</div>
									</div>
									<!--  SALDO ANTERIOR  BALANÇO ANUAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;">
											<strong><span style="font-family:courier,arial,helvetica;font-size:14px; color: ' . "#006400" . ' ">Saldo Anterior:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($saldo_aa) ?></span></strong>
										</div>
									</div>
									<!--  ENTRADAS  BALANÇO ANUAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong><span style="font-size:14px; color:<?php echo "#0000FF" ?>">Entradas:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong><span style="font-size:14px; color:<?php echo "#0000FF" ?>"><?php print formata_dinheiro($ent_acab) ?></span></strong>
										</div>
									</div>
									<!--  SAIDAS BALANÇO ANUAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color:<?php echo "#C00" ?>">Saidas:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color:<?php echo "#C00" ?>"><?php print formata_dinheiro($sai_acab) ?></span></strong>	
										</div>
									</div>
									<!--  BALANÇO BALANÇO ANUAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong><span style="font-size:14px; color:<?php if($bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ?>">Balanco:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00; background: #DCDCDC;">
											<strong>
												<span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php if($bal_bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ?>">
													<?php echo formata_dinheiro($bal_bal) ?>
												</span>
											</strong>
										</div>
									</div>
									<!--  SALDO ATUAL BALANÇO ANUAL -->
									<div class="row">
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;">
											<strong><span style="font-size:14px; color:<?php echo "#006400" ?>">Saldo Atual:</span></strong>
										</div>
										<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
											<strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($saldo_acab) ?></span></strong>
										</div>
									</div>
								</div>
								<!--  FIM DO BALANCO ANUAL -->
							</div>
							<br>
							<!--  FORMULARIO DE ESCOLHA DO MES E ANO SELECIONADOS -->
						</div>	
					</div>	
				</div>
			</div>
		</div>
	</body>
</html> ' ;

// este comando tem que ficar um pouco antes do <tr> da tabela html.
	$mostrar = new MOVS;
	$dados = $mostrar->bal_pormes($contapd,$mes_hoje, $ano_hoje);
	
	foreach($dados as $linha){
	$saldo_aa = $linha['saldo_ano_ant'];
	$saldo_ant = $linha['saldo_anterior_mes'];
	$entradas_m = $linha['credito_mes'];
	$saidas_m = $linha['debito_mes'];
	$resultado_mes = $linha['saldo_atual_mes'];
	$ent_acab = $linha['credito_acum_ano'];
	$sai_acab = $linha['debito_acum_ano'];
	$bal = $linha['bal_mes'];
	$bal_bal = $linha['bal_acum'];
	$saldo_acab = $linha['saldo_acum_ano'];
	
	}
	
	
		

// Write some HTML code:
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
$mpdf->WriteHTML($html);
// Output a PDF file directly to the browser
$mpdf->Output();
?>