<?php
error_reporting(E_ERROR | E_WARNING);
	require_once("config/session.php");
	require_once("class/class.user.php");
	include "config/config_session.php";
	include "parameters.php";
$mostrar = new MOVS;
// ENTRADAS ANTES DA DATA INICIAL  "CORRIGIDO"

// SALDO ANTERIOR CASO O MÊS SEJA JANEIRO OU OUTROS MESES...

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link href="assets/css/bootstrap.css" rel="stylesheet">
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
	  $( function() {
		  $( "#datamov, #datamov, #datapicker, #data_i, #data_f" ).datepicker({
			altField: "#actualDate",
			dateFormat: "dd-mm-yy",
			altFormat: "dd-mm-YY",
			showWeek: true,	
			changeMonth: true,
			changeYear: true
		});
		$( ".selector" ).datepicker({
		altFormat: "dd-mm-yy",
		altField: "#actualDate"
		});
	  } );
	</script>
	
	<script type="text/javascript">
	  $(function () {
		// toolip
		$('[data-toggle="tooltip"]').tooltip();

	 // datatables
	$('#tb_home').dataTable( {
		"lengthMenu": [[2, 7, 10, 12, 15, 25, -1], [2, 7, 10, 12, 15, 25, "All"]],
		"pageLength": 7,
		"pagingType": "full_numbers",
		"order": [[ 3, "asc" ]],
		"order": [[ 2, "asc" ]],
		"info":     true
	} );
	  })
	</script>
	
	<script type="text/javascript" language="javascript">
		function valida_form (){
			if(document.getElementById("data_i").value == ""){
			alert('Por favor, preencha o campo Data Inicial');
			document.getElementById("data_i").focus();
			return false
			} else {
				if(document.getElementById("data_f").value == ""){
					alert('Preencha o campo Data Final');
					document.getElementById("data_f").focus();
					return false
				}
			}
		}
	</script>
	<!-- 	SCRIPT JAVASCRIPT PARA DATATABLES CSS -->
	<title> welcome - <?php print($userRow['user_email']); ?></title>
</head>
<body> 
<!-- NAVBAR PRINCIPAL COM NOME DA CONTA, DATA ATUAL, MENU DA CONTA E MENU DO USUARIO -->
	<nav class="navbar navbar-default navbar-fixed-top " >
		<div class="container">
			<div class="navbar-header" >
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"> ok</span>
				<span class="icon-bar"> vamos </span>
				<span class="icon-bar"> terá </span>
			  </button>
			  <a class="navbar-brand" href="http://www.localhost/Login-Signup-Pdo/home.php">Livro Caixa <?php print $accountRow['conta'] ?></a>
			</div>
			<div id="navbar" class="navbar-collapse collapse" >
				<ul class="nav navbar-nav">
					<li><a href="?mes=<?php echo date('m')?>&ano=<?php echo date('Y')?>"><?php print "Hoje &eacute:"?><strong> <?php echo date('d')?> de <?php echo mostraMes(date('m'))?> de <?php echo date('Y')?></strong></a></li>	
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span> Ir Para <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home </a></li>
							<li class="divider"></li>
							<li><a href="list_cat.php"><span class="glyphicon glyphicon-book"></span> Categorias </a></li>
							<li class="divider"></li>
							
							<li><a href="filtrarporfolha.php"><span class="glyphicon glyphicon-list"></span> Filtrar por Folha </a></li>
							<li class="divider"></li>
							<li><a href="profile_user.php"><span class="glyphicon glyphicon-user"></span> Meus Dados </a></h5></li>
							<li class="divider"></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span>&nbsp;Conta: <?php echo $accountRow['idconta'] . "-" . $accountRow['conta']; ?>&nbsp;<span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Dados da Conta</a></li>
						<li><a href="logout_c.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Alternar Conta</a></li>
					  </ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span>&nbsp;Usu&aacute;rio: <?php echo $userRow['user_name']; ?>&nbsp;<span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Dados do Usuario</a></li>
							<li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sair </a></li>
						  </ul>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>	
	<!-- FIM DA NAVBAR DEFAULT -->
	
	<!-- INICIO DA DIV BALANÇO DE MOVIMENTO FILTRAR POR DATA -->
	<?php
	if (isset($_GET['filtrar_data'])){
		$data_i = $_GET['data_i'];
		$data_f = $_GET['data_f'];
		
		$stmt = $mostrar->bal_pordata($contapd, invertData($data_i), invertData($data_f));
		foreach($stmt as $linha){
		$saldoanterior =  $linha['saldo_ant_per'];
		$entradas_per =  $linha['credito_per'];
		$saidas_per =  $linha['debito_per'];
		$saldo_atual =  $linha['saldo_atual_per'];
		$bal =  $linha['bal_per'];
		$saldo_aa = $linha['saldo_ant_per'];
		$ent_acab = $linha['credito_acum_per'];
		$sai_acab = $linha['debito_acum_per'];
		$bal_bal = $linha['bal_acum_per'];
		$saldo_acab = $saldo_aa + $ent_acab + $sai_acab;
		}
	} else {
				
		$stmt = $mostrar->bal_pormes($contapd, $mes_hoje, $ano_hoje );
		foreach($stmt as $linha){
		$saldoanterior =  $linha['saldo_anterior_mes'];
		$entradas_per = $linha['credito_mes'];
		$saidas_per = $linha['debito_mes'];
		$saldo_atual = $linha['saldo_atual_mes'];
		$bal =  $linha['bal_mes'];
		$saldo_aa = $linha['saldo_ano_ant'];
		$ent_acab = $linha['credito_acum_ano'];
		$sai_acab = $linha['debito_acum_ano'];
		$bal_bal = $linha['bal_acum'];
		$saldo_acab = $saldo_aa + $bal_bal;
		}
	}
	?>
	<div class="container-fluid" style="margin-top:40px; margin-left:90px; margin-right: 90px; padding: 0; ">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xd-12">
				<div class="panel panel-danger" >
					<!--  CABEÇALHO DOS BALANCOS -->
					<div class="panel-heading"  > 
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xd-6" style="text-align: left;">
								<font color="black" size="3" style="font-family:courier,arial,helvetica;"><strong>BALANCOS DE MOVIMENTO</strong></font>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xd-6" style="text-align: right;">
								<font color="black" size="3" style = "font-family:courier,arial,helvetica;"><strong>FILTRAGEM POR DATA</strong></font>
							</div>
						</div>	
					</div>
					<!--  INICIO DO CORPO DO BALANCO -->
					<div class="panel-body" >
						<div class="row">
							<!--  BALANÇO MENSAL -->
							<div class="col-sm-6" style=" border-top: 1px dashed #f00; border-bottom: 1px dashed #f00;">
								<!--  SALDO ANTERIOR  BALANÇO MENSAL -->
								<div class="row" >
									<div class="col-sm-12" style="text-align: left; border-bottom: 1px dashed #f00;" >
									<font color="black" size="3"><strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#006400" ?>">BALANCO MENSAL</span></strong></font>
									</div>
								</div>
								<div class="row" >
									<div class="col-sm-6" style="text-align: left;" >
									<font color="black" size="3"><strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#006400" ?>">Saldo Anterior:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right;">
									<font color="black" size="3">
										<strong>
											<span style="font-size:14px; color:<?php echo "#006400" ?>">
												<?php echo formata_dinheiro($saldoanterior) ?>
											</span>
										</strong>
									</font>	
									</div>
								</div>
									<!--  ENTRADAS BALANÇO MENSAL -->
								<div class="row">
									<div class="col-sm-6" style="text-align: left;">
									<font color="black" size="3"><strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#0000FF" ?>">Entradas:</span></strong></font>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
									<font color="black" size="3">
										<strong>
											<span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#0000FF" ?>">
												<?php echo formata_dinheiro($entradas_per) ?>
											</span>
										</strong>
									</font>
									</div>
								</div>
								<!--  SAIDAS BALANÇO MENSAL -->
								<div class="row">
									<div class="col-sm-6" style=" text-align: left; ">
									<font color="black" size="3"><strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#C00" ?>">Saidas:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; ">
									<font color="black" size="3">
										<strong>
											<span style="font-size:14px; color:<?php echo "#C00" ?>">
												<?php echo formata_dinheiro($saidas_per) ?>
											</span>
										</strong>
									</font>
									</div>
								</div>
								<!--  BALANÇO BALANÇO MENSAL -->
								<div class="row">
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php if($bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ?>">Balanco no Mes:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
									<font color="black" size="3">
										<strong>
											<span style="font-size:14px; color:<?php if($bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ?>">
												<?php echo formata_dinheiro($bal) ?>
											</span>
										</strong>
									</font>
									</div>
								</div>
									<!--  SALDO ATUAL BALANÇO MENSAL  -->
								<div class="row">
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left;">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php echo "#006400" ?>">Saldo Atual:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right;">
									<font color="black" size="3">
										<strong>
											<span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#006400" ?>">
												<?php echo formata_dinheiro($saldo_atual) ?>
											</span>
										</strong>
									</font>
									</div>
								</div>		
							</div>
							<!-- BALANÇO ANUAL  -->	
							<div class="col-sm-6" style=" border-top: 1px dashed #f00; border-bottom: 1px dashed #f00;">
								
								<div class="row" >
									<div class="col-sm-12" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;" >
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php echo "#006400" ?>">BALANCO ANUAL</span></strong></font>
									</div>
								</div>
								<!--  SALDO ANTERIOR  BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left;">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php echo "#006400" ?>">Saldo Anterior:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right;">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($saldo_aa) ?></span></strong></font>
									</div>
								</div>
								<!--  ENTRADAS  BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left;">
									<font color="black" size="3"><strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#0000FF" ?>">Entradas:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right;">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php echo "#0000FF" ?>"><?php print formata_dinheiro($ent_acab) ?></span></strong></font>
									</div>
								</div>
								<!--  SAIDAS BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; ">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php echo "#C00" ?>">Saidas:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; ">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php echo "#C00" ?>"><?php print formata_dinheiro($sai_acab) ?></span></strong></font>
									</div>
								</div>
								<!--  BALANÇO BALANÇO MENSAL -->
								<div class="row">
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;">
									<font color="black" size="3"><strong><span style="font-size:14px; color:<?php if($bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ?>">Balanco:</span></strong></font>
									</div>
									<div class="col-sm-6" style="font-family:courier,arial,helvetica;text-align: right; border-bottom: 1px dashed #f00;">
									<font color="black" size="3">
										<strong>
											<span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php if($bal_bal > 0) { echo "#0000FF"; }else{ echo "#C00";} ?>">
												<?php echo formata_dinheiro($bal_bal) ?>
											</span>
										</strong>
									</font>
									</div>
								</div>
								<!--  SALDO ATUAL BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style=" text-align: left;">
									<font color="black" size="3"><strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#006400" ?>">Saldo Atual:</span></strong></font>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
									<font color="black" size="3"><strong><span style="font-family:courier,arial,helvetica;font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($saldo_acab) ?></span></strong></font>
									</div>
								</div>	
							</div>
							<!--  FIM DO BALANCO ANUAL -->
						</div>
						<br>
						<div class="row" >
							<div class="col-sm-12" style="font-family:courier,arial,helvetica;text-align: left; border-bottom: 1px dashed #f00;" >
								<form class="form-inline" style="text-align: center;">
									<label for="ano">
									Ano:
									</label>
									<select class="form-control" id="sel1" onchange="location.replace('?mes=<?php echo $mes_hoje?>&ano='+this.value)">
										<?php
											for ($i=2004;$i<=2050;$i++){
											?>
											<option value="<?php echo $i?>" <?php if ($i==$ano_hoje) echo "selected=selected"?> ><?php echo $i?></option>
										<?php }?>
									</select>
									<label for="mes">
										Mes:
									</label>
									<div class="btn-group">
										<?php
											for ($i=1;$i<=12;$i++){
										?>
										<?php if($mes_hoje==$i){?>
										<!-- MES SELECIONADO -->
										<a href="?mes=<?php echo $i?>&ano=<?php echo $ano_hoje?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-calendar"></span>
										<?php }else{?>
										<!-- OUTROS MESES -->
										<a href="?mes=<?php echo $i?>&ano=<?php echo $ano_hoje?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-calendar"></span>
										<?php } ?>	
										<?php echo mostraMes($i);?>
										</a>
										<?php } ?>
									</div>	
									<a href="#modal_pdf" class="btn btn-sm btn-success " name="modal_periodo" data-toggle="modal">
										<i class="glyphicon glyphicon-plus"></i> pdf	
									</a>
									<a href="#addmov" class="btn btn-sm btn-success " name="add_mov" data-toggle="modal" style="border: solid 1px; border-radius: 1px; box-shadow: 1px 1px 1px 1px black; border-radius:3px 3px 3px 3px;">
										<i class="glyphicon glyphicon-plus"></i> Novo
									</a>
									<a href="list_cat.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span>Categorias</a>
								</form>	
							</div>
						</div>
						
					</div>
					<div class="panel-footer" style="text-align: center;">
						<form class="form-inline" name="form_filtro_cat" method="get" action="" onsubmit="return valida_form(this)">
							<span><strong> filtrar por cod/desc: </strong></span><input type="text" name="busca" class="form-control input-sm" style="width:180px;" value="">
							<span><strong> Data inicial: </strong></span><input type="text" name="data_i" id="data_i" class="form-control input-sm"  style="width:110px;" value="" >
							<span><strong> Data final: </strong></span><input type="text" name="data_f" id="data_f" class="form-control input-sm"  style="width:110px;" value="">
							<span><strong><label> Categoria: </label></strong></span>
							<input type="hidden" name="data_hoje" value="<?php echo $dia_hoje . "-" . mostraMes($mes_hoje) ."-" . $ano_hoje ?>">
							<a href="#modal_date" class="btn btn-sm btn-success " name="modal_date" data-toggle="modal">
								<i class="glyphicon glyphicon-plus"></i> pdf
							</a>
							<button type="submit" name="filtrar_data" class="btn btn-default btn-sm"  value="Filtrar" >Filtrar</button>	
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- FIM DA DIV BALANÇO DE MOVIMENTO FILTRAR POR DATA -->
	
	<!-- INICIO DA TABELA DE DADOS -->
	<?php
	if (isset($_GET['filtrar_data'])){
		// VERIFICA SE O CAMPO BUSCA FOI PREENCHIDO
		if(isset($_GET['busca']) && $_GET['busca'] != ''){
			$busca = $_GET['busca'];
		} else{
			$busca = '';
		}
		// VERIFICA SE A DATA INICIAL FOI PREENCHIDA
		if(isset($_GET['data_i']) && $_GET['data_i'] != ''){
			$data_i = $_GET['data_i'];
		} else{
			$data_i = '';
		}
		// VERIFICA SE A DATA FINAL FOI PREENCHIDA
		if(isset($_GET['data_f']) && $_GET['data_f'] != ''){
			$data_f = $_GET['data_f'];
		} else{
			$data_f = '';
		}
			/*
			$stmt = $db->prepare($dados_data_a_data);
			$stmt->bindparam(':contapd', $contapd );
			$stmt->bindvalue(':busca', '%'.$busca.'%' );
			$stmt->bindparam(':data_i', invertData($data_i) );
			$stmt->bindparam(':data_f', invertData($data_f) );
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			*/
			$mostrar = new MOVS;
			$stmt = $mostrar->dados_pordata($contapd, $busca, invertData($data_i), invertData($data_f) );
		} else {
			/*
			$stmt = $db->prepare($dados_pormes);
			$stmt->bindparam(':contapd', $contapd );
			$stmt->bindparam(':mes_hoje', $mes_hoje );
			$stmt->bindparam(':ano_hoje', $ano_hoje );
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			*/
			$mostrar = new MOVS;
			$stmt = $mostrar->dados_pormes($contapd, $mes_hoje, $ano_hoje );
		}
	?>
	<div class="container-fluid" style="margin-top:0px; margin-left:90px; margin-right: 90px; padding: 0; ">
		<div class="panel panel-danger" >
		<!--  CABEÇALHO DA TABELA DE DADOS -->
			<div class="panel-heading"  >
				<div class="row">
					<div class="col-sm-6" style="text-align: left;">
						<font color="black" style = "font-family:courier,arial,helvetica;"><strong>RELATORIO DE CAIXA: <?php if (isset($_GET['filtrar_data'])){ ?>De <?php echo $data_i ?> a <?php echo $data_f ?><?php }else{ ?> :  <?php echo $mes_hoje ?>/<?php echo $ano_hoje ?><?php }?></strong></font>
					</div>
					<div class="col-sm-6" style="text-align: right;">
						<font color="black" style = "font-family:courier,arial,helvetica;"><strong>
								SALDO ANTERIOR:  <?php echo formata_dinheiro($saldoanterior) ?>
						</strong></font>
					</div>
				</div>
			</div>
			<!--  CORPO DA TABELA DE DADOS -->
			<div class="panel-body" >
				<div class="container-fluid" >
					<table id="tb_home_filtrar" class="table table-responsive table-bordered table-striped table-condensed table-hover" >
						<thead>
							<th style=" text-align: center;">Seq.</th>
							<th style=" text-align: center;">Id.</th>
							<th style=" text-align: center;">Data</th>
							<th style=" text-align: left;">Descricao</th>
							<th style=" text-align: center;">Categoria</th>
							<th style=" text-align: center;">Livro/Folha</th>
							<th style=" text-align: center;">Entradas</th>
							<th style=" text-align: center;">Saidas</th>
							<th style=" text-align: center;">Saldo</th>
						</thead>					
						<tbody>
							<?php
								// $pdo = new Database();
								// $db = $pdo->dbConnection();
							
								
								// COMANDOS SQL PARA VERIFICAR CAMPO BUSCA, DATA INICIAL E DATA FINAL
								
								$cont=0;
								$seq=0;
								$saldo=0;
								
								foreach ($stmt as $row) {
								$cont++;
								$seq++;
								
								$cat = $row['cat'];
								$stmt = $db->prepare("SELECT * FROM lc_cat WHERE id='$cat'");
								$stmt->execute();
								$qr2=$stmt->fetch(PDO::FETCH_ASSOC);
								$categoria = $qr2['nome'];	
							?>
							
							<tr >
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $seq; ?></strong></td>
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>">
									<a style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>" href="#edit<?php echo $row['id']; ?>" data-toggle="modal" name="btn_edit_mov" ><strong style="font-size:12px;"><i><u><?php echo $row['id']; ?></u></i></strong></a>									
								<?php include('operations/edit_mov.php'); ?>
								</td>
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo InvertData($row['datamov']); ?></strong></td>
								<td style=" text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['descricao']; ?></strong></td>
								<td style=" text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['cat']; ?> - <a style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>" href="?mes=<?php echo $mes_hoje?>&ano=<?php echo $ano_hoje?>&filtro_cat=<?php echo $cat?>"><strong style="font-size:12px;"><?php echo $categoria?></a></td>
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['idlivro']; ?>-<?php echo $row['folha']; ?></strong></td>
								<td style=" text-align: center;">
									<p style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><?php if ($row['tipo']==1) echo "+" ; else echo ""?><strong style="font-size:12px;"><?php if ($row['tipo']==1) echo formata_dinheiro($row['valor']); else echo "";?></strong></p>
								</td>
								<td style=" text-align: center;">
									<p style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#030"?>"><?php if ($row['tipo']==0) echo "-"; else echo ""?><strong style="font-size:12px;"><?php if ($row['tipo']==0) echo formata_dinheiro($row['valor']); else echo ""?></strong></p>
								</td>
								<?php if ($row['tipo']==1) formata_dinheiro($saldo=$saldo+$row['valor']); else formata_dinheiro($saldo=$saldo-$row['valor']); ?>
								<?php if(isset($_POST["filtrar_data"])) $acumulado = $saldoanterior + $saldo; else $acumulado = $saldoanterior+$saldo;?>
								<td style=" text-align: center;">
									<p style="color:<?php if ($acumulado>0) echo "#00f"; else echo"#C00" ?>"><strong style="font-size:12px;"><?php echo formata_dinheiro($acumulado);?></strong></p>
								</td>							
							</tr>					
							<?php  } ?>						
						</tbody>
					</table>
				</div>
			</div>
			<!--  RODAPÉ DA TABELA DE DADOS -->
			<div class="panel-footer" >
				<div class="container-fluid" >
					<table id="tb_home_f" class="table table-responsive table-bordered table-striped table-condensed table-hover" >
						<tr>
							<td style="width:220px; text-align: right; " COLSPAN="4"><strong> A TRANSPORTAR TOTAIS DO DIA </strong></td>
							<td style="width:100px; text-align: center; ">
								<strong>
									<span style="font-size:12px; color:<?php echo "#0000FF" ?>">
										
										
										<?php echo formata_dinheiro($entradas_per) ?>
										
									</span>
								</strong>
							</td>
							<td style="width:100px; text-align: center; ">
								<strong>
									<span style="font-size:12px; color:<?php echo "#C00" ?>">
										
										<?php echo formata_dinheiro($saidas_per) ?>
										
									</span>
								</strong>
							</td>
							<td style="width:100px; text-align: center; "><?php echo "" ?></td>
						</tr>
						<tr>
							<td style="width:180px; text-align: right; " COLSPAN="4"><strong> SALDO ANTERIOR </strong></td>
							<td style="width:100px; text-align: center; ">
								<strong>
									<span style="font-size:12px; color:<?php echo "#006400" ?>">
										
										<?php echo formata_dinheiro($saldoanterior) ?>
										
									</span>
								</strong>
							</td>
							<td style="width:100px; text-align: center; "></td>
							<td style="width:100px; text-align: center; "></td>
						</tr>
						<tr>
							<td style="width:180px; text-align: right; " COLSPAN="4"><strong> SALDO ATUAL </strong></td>
							<td style="width:100px; text-align: center; "></td>
							<td style="width:100px; text-align: center; "></td>
							<td style="width:100px; text-align: center; ">
								<strong>
									<span style="font-size:14px; color:<?php echo "#006400" ?>">
										
										<?php echo formata_dinheiro($saldo_atual) ?>
										
									</span>
								</strong>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<!-- INICIO DO FORMULARIO MODAL FILTRAR POR DATA -->
    <div class="modal fade" id="modal_date" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <center><h4 class="modal-title" id="myModalLabel">Exportar p/ PDF Ref.: <?php echo $mes_hoje ?>/<?php echo $ano_hoje ?> - Conta: <?php echo $contapd ?></h4></center>
                </div>
                <div class="modal-body" >
					<div class="panel panel-primary">
						<div class="panel-body">
							<form class="form-inline" name="form_filtro_cat" method="POST" action="rel_cx_periodo.php">
								
								<div class="controls">
									<label class="control-label" for="inputName">Data Inicial:</label>
									<input type="text" name="data_ini" id="data_ini" value="<?php echo invertData($dti) ?>" class="form-control input-sm" size="8">
									<label class="control-label" for="inputName">Data Final:</label>
									<input type="text" name="data_fim" id="data_fim" value="<?php echo invertData($dti) ?>" class="form-control input-sm" size="8">
								</div>
								<br>
								<div class="controls">
									<label class="control-label" for="inputName">Pesquisar: </label>
									<input type="text" name="busca" placeholder="busca" class="form-control input-sm">
									<label class="control-label" for="inputName">Conta:</label>
									<input type="text" name="conta" value="<?php echo $contapd?>" class="form-control input-sm" size="1">
									<button type="submit" class="btn btn-warning btn-sm" name="btn_date_pdf" ><span class="glyphicon glyphicon-check"></span> Imprimir em PDF </button>	
								</div>
							</form>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<!-- /.modal -->	
<?php include "operations/add_mov.php" ?>
<?php include "modal_pdf.php" ?>

	<div class="container-fluid" style="margin-top:0px; margin-left: 120px; margin-right: 120px; padding: 0; ">	
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<!-- necessário para abrir os DataTables -->
		<script src="assets/js/dataTables/js/jquery.dataTables.js"></script>
		<script src="assets/js/dataTables/js/dataTables.bootstrap.js"></script>
		
		<!-- necessário para abrir o calendario datepicker -->
		<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
		
		<script type="text/javascript">
		  $(function () {
			// toolip
			$('[data-toggle="tooltip"]').tooltip();
			// datatables
			$('#tb_home_filtrar').dataTable( {
				"lengthMenu": [[2, 5, 10, 12, 15, -1], [2, 5, 10, 12, 15, "All"]],
				"pageLength": 5,
				"pagingType": "full_numbers",
				"paging": true,
				"ordering": true,
				"info":     true
			} );
		  })
		</script>	
	</div>	
</body>
</html>