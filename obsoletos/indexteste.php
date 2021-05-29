<?php
error_reporting(E_ERROR | E_WARNING);
	
	include "config/config_session.php";
	include "parameters.php";
	include "consultas.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link href="assets/css/bootstrap.css" rel="stylesheet">
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script >
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
	$('#tb_home_f').dataTable( {
		"lengthMenu": [[2, 7, 10, 12, 15, 25, -1], [2, 7, 10, 12, 15, 25, "All"]],
		"pageLength": 7,
		"pagingType": "full_numbers",
		"order": [[ 3, "asc" ]],
		"order": [[ 2, "asc" ]],
		"info":     true
	} );
	  })
	</script>
	<!--
function mascara_cpf(cpf)
{
    var mycpf = '';
    mycpf = mycpf + cpf;
    if (mycpf.length == 3) {
        mycpf = mycpf + '.';
        document.forms[0].cpf.value = mycpf;
    }
    if (mycpf.length == 7) {
        mycpf = mycpf + '.';
        document.forms[0].cpf.value = mycpf;
    }
    if (mycpf.length == 11) {
        mycpf = mycpf + '-';
        document.forms[0].cpf.value = mycpf;
    }
    if (mycpf.length == 14) {
    }
}

</script>
//-->	
	<!-- 	SCRIPT JAVASCRIPT PARA DATATABLES CSS -->
	<title> welcome - <?php print($userRow['user_email']); ?></title>
</head>
<body> 
	<!-- 	INICIO DA NAVBAR PRINCIPAL CONTENDO NOME DO SISTEMA, DATA ATUAL, LOGIN USUARIO, LOGIN CONTA, MENU DE CONTEXTO -->
	<nav class="navbar navbar-expand-lg navbar-light bg-primary navbar-fixed-top " style="background-color: #084B8A">
		<div class="container">
			<div class="navbar-header" >
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"> ok</span>
				<span class="icon-bar"> vamos </span>
				<span class="icon-bar"> terá </span>
			  </button>
			  <a class="navbar-brand" href="http://www.localhost/Login-Signup-Pdo/index.php">Livro Caixa <?php print $accountRow['conta'] ?></a>
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
					  <span class="glyphicon glyphicon-user"></span>&nbsp;Conta: <?php echo $accountRow['idconta'] . "-" . $accountRow['conta']; ?>&nbsp;<span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="profile_user.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Dados da Conta</a></li>
						<li><a href="logout_c.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Alterar Conta</a></li>
					  </ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span>&nbsp;Usu&aacute;rio: <?php echo $userRow['user_name']; ?>&nbsp;<span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="profile_user.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Dados do Usuario</a></li>
							<li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Alterar Usuario</a></li>
						  </ul>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>
	<!-- 	FIM DA NAV BAR PRINCIPAL -->
	<!-- 	INICIO DO BALANÇO DE MOVIMENTO -->
	<div class="container-fluid" style="margin-top:40px; margin-left:90px; margin-right: 90px; padding: 0; ">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xd-12">	
				<div class="panel panel-primary" >
					<!--  CABEÇALHO DOS BALANCOS -->
					<div class="panel-heading"  >
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xd-6" style="text-align: left;">
								<strong> BALANCOS DE MOVIMENTO - LIVRO Nº <?php echo $livro ?></strong>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xd-6" style="text-align: right;">
								<strong> DEMOSTRATIVO MENSAL </strong>
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
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>">BALANCO MENSAL </span></strong>
									</div>
								</div>
								<div class="row" >
									<div class="col-sm-6" style="text-align: left;" >
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>">Saldo Anterior:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($saldo_ant) ?></span></strong>
									</div>
								</div>
								<!--  ENTRADAS BALANÇO MENSAL -->
								<div class="row">
									<div class="col-sm-6" style="text-align: left;">
										<strong><span style="font-size:14px; color:<?php echo "#0000FF" ?>">Entradas:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
										<strong><span style="font-size:14px; color:<?php echo "#0000FF" ?>"><?php print formata_dinheiro($entradas_m) ?></span></strong>
									</div>
								</div>
								<!--  SAIDAS BALANÇO MENSAL -->
								<div class="row">
									<div class="col-sm-6" style=" text-align: left; border-bottom: 1px dashed #f00;">
										<strong><span style="font-size:14px; color:<?php echo "#C00" ?>">Saidas:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right; border-bottom: 1px dashed #f00;">
										<strong><span style="font-size:14px; color:<?php echo "#C00" ?>"><?php print formata_dinheiro($saidas_m) ?></span></strong>
									</div>
								</div>
								<!--  SALDO ATUAL BALANÇO MENSAL  -->
								
								<div class="row">
									<div class="col-sm-6" style=" text-align: left;">
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>">Saldo Atual:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($resultado_mes) ?></span></strong>
									</div>
								</div>		
							</div>
							<!--  FIM DO BALANCO MENSAL -->
							<!-- INICIO DO BALANÇO ANUAL  -->	
							<div class="col-sm-6" style=" border-top: 1px dashed #f00; border-bottom: 1px dashed #f00;">
								
								<div class="row" >
									<div class="col-sm-12" style="text-align: left; border-bottom: 1px dashed #f00;" >
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>">BALANCO ANUAL</span></strong>
									</div>
								</div>
								<!--  SALDO ANTERIOR  BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style=" text-align: left;">
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>">Saldo Anterior:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($saldo_aa) ?></span></strong>
									</div>
								</div>
								<!--  ENTRADAS  BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style=" text-align: left;">
										<strong><span style="font-size:14px; color:<?php echo "#0000FF" ?>">Entradas:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
										<strong><span style="font-size:14px; color:<?php echo "#0000FF" ?>"><?php print formata_dinheiro($ent_acab) ?></span></strong>
									</div>
								</div>
								<!--  SAIDAS BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style=" text-align: left; border-bottom: 1px dashed #f00;">
										<strong><span style="font-size:14px; color:<?php echo "#C00" ?>">Saidas:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right; border-bottom: 1px dashed #f00;">
										<strong><span style="font-size:14px; color:<?php echo "#C00" ?>"><?php print formata_dinheiro($sai_acab) ?></span></strong>	
									</div>
								</div>
								<!--  SALDO ATUAL BALANÇO ANUAL -->
								<div class="row">
									<div class="col-sm-6" style=" text-align: left;">
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>">Saldo Atual:</span></strong>
									</div>
									<div class="col-sm-6" style=" text-align: right;">
										<strong><span style="font-size:14px; color:<?php echo "#006400" ?>"><?php print formata_dinheiro($saldo_acab) ?></span></strong>
									</div>
								</div>
							</div>
							<!--  FIM DO BALANCO ANUAL -->
						</div>
						<br>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xd-12" style="text-align: left; border-bottom: 1px dashed #f00;">
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
									<a href="#modal_pdf" class="btn btn-sm btn-success " name="add_mov" data-toggle="modal">
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
					<div class="panel-footer">
					BALANÇO 
					</div>
				</div>	
			</div>
		</div>
	</div>
	<!-- 	INICIO DO BALANÇO DE MOVIMENTO TESTE DE FUNÇÃO dados_saldo -->
	<?php 
	$mostrar = new MOVS;
	$dados = $mostrar->dados_balanco_mes_ano($contapd,$mes_hoje, $ano_hoje);
	
	foreach($dados as $linha){
	$saldo_aa = $linha['saldo_ano_ant'];
	$saldo_ant = $linha['saldo_anterior_mes'];
	$entradas_m = $linha['credito_mes'];
	$saidas_m = $linha['debito_mes'];
	$resultado_mes = $linha['saldo_atual_mes'];
	$ent_acab = $linha['credito_acum_ano'];
	$sai_acab = $linha['debito_acum_ano'];
	$saldo_acab = $linha['saldo_atual_mes'];
	$saldo_acab = $saldo_aa + $ent_acab - $sai_acab;
	?>
	
	<!--  -->
	<?php } ?>
	<!-- 	FIM DA BALANÇO DE MOVIMENTO -->
	
	<!-- 	INICIO DO TABELA DE DADOS FOLHA DE MOVIMENTOS -->
	<div class="container-fluid" style="margin-top:0px; margin-left:90px; margin-right: 90px; padding: 0; ">
		<div class="panel panel-primary" >
			<!--  CABEÇALHO DA TABELA DE DADOS -->
			<div class="panel-heading"  >
				<div class="row">
					<div class="col-sm-6" >
						<strong>RELATORIO DE CAIXA <?php echo $mes_hoje ?>/<?php echo $ano_hoje ?></strong>
					</div>
					<div class="col-sm-6" style="text-align: right;">
						<strong>
								SALDO ANTERIOR:  <?php echo formata_dinheiro($saldo_ant) ?>
						</strong>
					</div>
				</div>
			</div>
			<!--  CORPO DA TABELA DE DADOS -->
			<div class="panel-body" >
				<?php 
					/**/
					$pdo = new Database();
					$db = $pdo->dbConnection();
					// a variavel "$dados_pormes" está na pagina consultas.php, linha 18
					$stmt = $db->prepare($dados_pormes);
					$stmt->bindparam(':contapd', $contapd );
					$stmt->bindparam(':mes_hoje', $mes_hoje );
					$stmt->bindparam(':ano_hoje', $ano_hoje );
					$stmt->execute();
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
					$cont=0;
					$seq=0;
					$saldo=0;
				?>
				<table id="tb_home_f" class="table table-responsive table-bordered table-striped table-condensed table-hover" style="">
					<thead>
						<tr >
							<th >Seq.</th>
							<th >Id.</th>
							<th >Data</th>
							<th >Descricao</th>
							<th >Categoria</th>
							<th >Livro/Folha</th>
							<th >Entradas</th>
							<th >Saidas</th>
							<th >Saldo</th>
						</tr>
					</thead>					
					<tbody>
					<?php
							foreach ($result as $row) {	
							$cont++;
							$seq++;
							
							$cat = $row['cat'];
							$stmt = $db->prepare("SELECT * FROM lc_cat WHERE id='$cat'");
							$stmt->execute();
							$qr2=$stmt->fetch(PDO::FETCH_ASSOC);
							$categoria = $qr2['nome'];	
							
					?>
					<tr >	
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $seq; ?></strong></td>
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>">
							<a style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>;" href="#edit<?php echo $row['id']; ?>" data-toggle="modal" name="btn_edit_mov" ><strong style="font-size:12px;"><b><i><u><?php echo $row['id']; ?></u></i></b></a>									
						<?php include('operations/edit_mov.php'); ?>
						</td>
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo InvertData($row['datamov']); ?></strong></td>
						<td style="text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['descricao']; ?></strong></td>
						<td style="text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><a style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>;" href="?mes=<?php echo $mes_hoje?>&ano=<?php echo $ano_hoje?>&filtro_cat=<?php echo $categoria?>"><?php echo $categoria?></strong></a></td>
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['idlivro']; ?>-<?php echo $row['folha']; ?></strong></td>
						<td style="text-align: center;">
							<p style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><?php if ($row['tipo']==1) echo "+"; else echo ""?><strong style="font-size:12px;"><?php if ($row['tipo']==1) echo formata_dinheiro($row['credito']); else echo ""?></strong></p>
						</td>
						<td style="text-align: center;">
							<p style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#030"?>"><?php if ($row['tipo']==0) echo "-"; else echo ""?><strong style="font-size:12px;"><?php if ($row['tipo']==0) echo formata_dinheiro($row['debito']); else echo ""?></strong></p>
						</td>
						<?php //if ($row['tipo']==1) formata_dinheiro($saldo=$saldo+$row['valor']); else formata_dinheiro($saldo=$saldo-$row['valor']); ?>
						<?php //$acumulado = $saldo_ant+$saldo;?>
						<td style="text-align: center;">
							<p style="color:<?php if ($acumulado>0) echo "#00f"; else echo"#C00" ?>"><strong style="font-size:12px;"><?php echo formata_dinheiro($row['saldo_atual']);?></strong></p>
						</td>							
					</tr>					
					<?php  } ?>						
					</tbody>
					<tfoot>
						<tr>
							<th >Seq.</th>
							<th >Id.</th>
							<th >Data</th>
							<th >Descricao</th>
							<th >Categoria</th>
							<th >Folha</th>
							<th >Entradas</th>
							<th >Saidas</th>
							<th >Saldo</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="panel-footer" >
				<table id="tb_home" class="table table-responsive table-bordered table-striped table-condensed table-hover" >
					<tr>
						<td style="width:220px; text-align: right; " COLSPAN="4"><strong> A TRANSPORTAR TOTAIS DO DIA </strong></td>
						<td style="width:100px; text-align: center; ">
							<strong>
								<span style="font-size:12px; color:<?php echo "#0000FF" ?>">
									<?php echo formata_dinheiro($entradas_m) ?>
								</span>
							</strong>
						</td>
						<td style="width:100px; text-align: center; ">
							<strong>
								<span style="font-size:12px; color:<?php echo "#C00" ?>">
									<?php echo formata_dinheiro($saidas_m) ?>
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
									<?php echo formata_dinheiro($saldo_ant) ?>
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
									<?php echo formata_dinheiro($resultado_mes) ?>
								</span>
							</strong>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<!-- 	FIM DA TABELA DE MOVIMENTOS -->
	
	<div class="container-fluid" style="margin-top:0px; margin-left: 120px; margin-right: 120px; padding: 0; ">	
		
		<script src="vendor/jquery-1.11.3.min.js"></script>
		<script src="vendor/main.js"></script>
		<script src="vendor/plugins.js"></script>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<!-- necessário para abrir os DataTables 
		
		
		-->
		<script src="assets/js/dataTables/js/jquery.dataTables.js"></script>
		<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
		<script src="assets/js/dataTables/js/dataTables.bootstrap.js"></script>
		<!-- necessário para abrir o calendario datepicker 
		
		-->
		<script type="text/javascript">
		
		  $(function () {
			// toolip
			$('[data-toggle="tooltip"]').tooltip();
			// datatables
			$('#tb_home_f').dataTable( {
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
	<!-- FORMULÁRIOS MODAIS - RELATORIO EM PDF -->
	<div class="modal fade" id="modal_pdf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <center><h4 class="modal-title" id="myModalLabel">Exportar p/ PDF Ref.: <?php echo $mes_hoje ?>/<?php echo $ano_hoje ?> - Conta: <?php echo $contapd ?></h4></center>
                </div>
                <div class="modal-body" >
					<div class="container-fluid" >
						<form class="form-inline" name="form_filtro_cat" method="POST" action="rel_pdf.php">
							<div class="panel panel-primary">
								<div class="panel-body">					
									Męs: <input type="text" name="mes" value="<?php echo $mes_hoje?>" class="form-control input-sm" size="1">
									Ano: <input type="text" name="ano" value="<?php echo $ano_hoje?>" class="form-control input-sm" size="1">
									Conta: <input type="text" name="conta" value="<?php echo $contapd?>" class="form-control input-sm" size="1">
									<button type="submit" class="btn btn-warning btn-sm" name="btn_pdf" ><span class="glyphicon glyphicon-check"></span> Imprimir em PDF </button>
											
								</div>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php include "operations/add_mov.php"; ?>
</body>
</html>