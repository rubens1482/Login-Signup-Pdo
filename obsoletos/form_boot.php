<?php
	require_once("config/session.php");
	require_once("class/class.user.php");
	include "config/config_session.php";
	include "parameters.php";
?>
<?php
$pdo = new Database();
$db = $pdo->dbConnection();

$primeiro_dia = date("01", mktime(0,00,0,$mes_hoje,'01',$ano_hoje));
$ultimo_dia = date("t", mktime(0,00,0,$mes_hoje,'01',$ano_hoje));

$dia = 01;
$mes = $mes_hoje;
$ano = $ano_hoje;
//$datainicial = date("$dia-$mes-$ano");
//$datafinal = date("t/$mes/$ano");

$tipo1 = 1;
$tipo0 = 0;

// ENTRADAS ENTRE A DATA INICIAL E A DATA FINAL  "CORRIGIDO"
if (isset($_GET['filtrar_data'])){
	$data_i = invertData($_GET['data_i']);
	$data_f = invertData($_GET['data_f']);
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_ep FROM lc_movimento WHERE idconta=:contapd and tipo=:tipo1 and datamov>=:data_i and datamov<=:data_f");
	$stmt->bindparam(':contapd', $contapd );
	$stmt->bindparam(':tipo1', $tipo1 );
	$stmt->bindparam(':data_i', $data_i );
	$stmt->bindparam(':data_f', $data_f );
	$stmt->execute();
	$qreep=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_ep=$qreep['entradas_ep'];
// SAIDAS ENTRE A DATA INICIAL E A DATA FINAL	 "CORRIGIDO"
	$data_i = invertData($_GET['data_i']);
	$data_f = invertData($_GET['data_f']);
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_ep FROM lc_movimento WHERE idconta=:contapd and tipo=:tipo0 and datamov>=:data_i and datamov<=:data_f");
	$stmt->bindparam(':contapd', $contapd );
	$stmt->bindparam(':tipo0', $tipo0 );
	$stmt->bindparam(':data_i', $data_i );
	$stmt->bindparam(':data_f', $data_f );
	$stmt->execute();
	$qrsep=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_ep=$qrsep['saidas_ep'];
// DEFINIÇÃO DO FILTRO PARA ENTRADA ANTERIOR POR PERIODO
}else{
	
}

// ENTRADAS ANTES DA DATA INICIAL  "CORRIGIDO"
if (isset($_GET['filtrar_data'])){
	$data_i = invertData($_GET['data_i']);
	
	//$data_f = invertData($_POST['data_f']);
	// ENTRADAS ANTES DA DATA INICIAL
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_di FROM lc_movimento WHERE idconta=:contapd and tipo=:tipo1 and datamov<:data_i");
	$stmt->bindparam(':contapd', $contapd );
	$stmt->bindparam(':tipo1', $tipo1 );
	$stmt->bindparam(':data_i', $data_i );
	$stmt->execute();
	$qredi=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_di=$qredi['entradas_di'];	
	echo $entradas_di;
// SAIDA ANTES DA DATA INICIAL  "CORRIGIDO"
	$data_i = invertData($_GET['data_i']);
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_di FROM lc_movimento WHERE idconta=:contapd and tipo=:tipo0 and datamov<:data_i");
	$stmt->bindparam(':contapd', $contapd );
	$stmt->bindparam(':tipo0', $tipo0 );
	$stmt->bindparam(':data_i', $data_i );
	$stmt->execute();
	$qrsdi=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_di=$qrsdi['saidas_di'];
// SALDO ANTERIOR A DATA INICIAL
	$saldo_dti = $entradas_di-$saidas_di;		
}else{	
}
// SALDO ANTERIOR CASO O MÊS SEJA JANEIRO OU OUTROS MESES...
	if ($mes_hoje == 1){
		$saldo_ant=$saldo_aa;
	} else {
		$saldo_ant=$saldo_aca+$saldo_aa;
	}
	if ($mes_hoje == 1){
		$resultado_mes=$saldo_aa+$saldo_m;
	} else {
		$resultado_mes=$saldo_aa+$saldo_aca+$saldo_m;
	}
	
	
	
	if (isset($_GET["filtrar_data"])){
		$dti = $data_i;
		$dtf = $data_f;
	}else{
		$dti = invertData(date('d-m-Y'));
		$dtf = invertData(date('t-m-Y'));
	}
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
		  $( "#datamov, #datamov, #datapicker, #data_ini, #data_fim" ).datepicker({
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
							<li><a href="home.php"> Home </a></li>
							<li class="divider"></li>
							<li><a href="list_cat.php"> Adicionar Categorias </a></li>
							<li class="divider"></li>
							<li><a href="filtrarpordata.php"> Filtrar por Data </a></li>
							<li class="divider"></li>
							<li><a href="filtrarporfolha.php"> Filtrar por Folha </a></li>
							<li class="divider"></li>
							<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> profile</a></h5></li>
							<li class="divider"></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span>&nbsp;Conta: <?php echo $accountRow['idconta'] . "-" . $accountRow['conta']; ?>&nbsp;<span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li>
						<li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
					  </ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span>&nbsp;Usu&aacute;rio: <?php echo $userRow['user_name']; ?>&nbsp;<span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li>
							<li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
						  </ul>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>	
	<!-- FIM DA NAVBAR DEFAULT -->
	
	<!-- INICIO DA DIV BALANÇO DE MOVIMENTO FILTRAR POR DATA -->
	<div class="container-fluid" style="margin-top:40px; margin-left:90px; margin-right: 90px; padding: 0; ">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xd-12">
				<div class="panel panel-danger" >
					<!--  CABEÇALHO DOS BALANCOS -->
					<div class="panel-heading"  > 
						LANCAMENTO DE COMPRAS 
					</div>
					<!--  INICIO DO CORPO DO BALANCO -->
					<div class="panel-body" >
					<form class="form-horizontal" role="form">
						<div class="row">
							<!--  BALANÇO MENSAL -->
							<div class="col-sm-6" >

									<div class="form-group">
									<label for="Codigo" class="col-sm-3 control-label">Codigo</label>
										<div class="col-sm-9">
										  <input type="text" class="form-control"  placeholder="Codigo" />
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label for="n_form" class="col-sm-3 control-label">Forma Pagto</label>
										<div class="col-sm-9">
										  <select class="form-control">
											<option value="1">à vista</option>
											<option value="2">Parcelado</option>
											
										  </select>
										</div>
									</div>
									
									
									<div class="form-group">
									<label for="Produto" class="col-sm-3 control-label">produto</label>
										<div class="col-sm-9">
										  <input type="text" class="form-control"  placeholder="produto" />
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Dates range</label>
										<div class="col-sm-9">
										  <div class="input-daterange" id="datepicker">
											<div class="input-group">
											  <input type="text" class="input-small form-control"
												name="start" /> <span class="input-group-addon">to</span> <input
												type="text" class="input-small form-control" name="end" />
											</div>
										  </div>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
										  <button type="submit" class="btn btn-default">Sign in</button>
										</div>
									</div>
								
							</div>
							
							<!-- BALANÇO ANUAL  -->	
							<div class="col-sm-6" >
								<div class="form-group">
								<label for="n_card" class="col-sm-3 control-label">Num. Cartão</label>
									<div class="col-sm-9">
									  <select class="form-control">
										<option value="1">5790</option>
										<option>2044</option>
										<option>6818</option>
										<option>4</option>
										<option>5</option>
									  </select>
									</div>
								</div>	
								<div class="form-group">
									<label for="Fornecedor" class="col-sm-3 control-label">Fornecedor</label>
									<div class="col-sm-9">
									  <input type="text" class="form-control" id="fornecedor" placeholder="Fornecedor" />
									</div>
								</div>
								
								
							</div>
							
							<!--  FIM DO BALANCO ANUAL -->
						</div>
						<br>
						<div class="row" >
							
	
						</div>
						
					</form>
					</div>
					<div class="panel-footer" style="text-align: center;">
						<form class="form-inline" name="form_filtro_cat" method="get" action="" onsubmit="return valida_form(this)">
							<span><strong> filtrar por cod/desc: </strong></span><input type="text" name="busca" class="form-control input-sm" style="width:180px;" value="">
							<span><strong> Data inicial: </strong></span><input type="text" name="data_i" id="data_i" class="form-control input-sm"  style="width:110px;" value="<?php echo invertData($dti) ?>" >
							<span><strong> Data final: </strong></span><input type="text" name="data_f" id="data_f" class="form-control input-sm"  style="width:110px;" value="<?php echo invertData($dtf) ?>">
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
	<div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Panel title</h3>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" role="form">
				<div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">Email</label>
					<div class="col-sm-9">
					  <input type="email" class="form-control" id="inputEmail3"
						placeholder="Email" />
					</div>
				</div>
				<div class="form-group">
					<label for="inputPassword3" class="col-sm-3 control-label">Password</label>
					<div class="col-sm-9">
					  <input type="password" class="form-control" id="inputPassword3"
						placeholder="Password" />
					</div>
				</div>
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-3 control-label">Skill</label>
                <div class="col-sm-9">
                  <select class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Dates range</label>
                <div class="col-sm-9">
                  <div class="input-daterange" id="datepicker">
                    <div class="input-group">
                      <input type="text" class="input-small form-control"
                        name="start" /> <span class="input-group-addon">to</span> <input
                        type="text" class="input-small form-control" name="end" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label> <input type="checkbox" />Remember me
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-default">Sign in</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-7">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Chart panel</h3>
            <div class="btn-group btn-group-xs pull-right">
              <a href="#" class="btn btn-success"><span
                class="glyphicon glyphicon-plus"></span></a> <a href="#"
                class="btn btn-danger"><span
                class="glyphicon glyphicon-minus"></span></a>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="panel-body">Panel content</div>
        </div>
      </div>
    </div>
  </div>
	<!-- INICIO DA TABELA DE DADOS -->
	<div class="container-fluid" style="margin-top:0px; margin-left:90px; margin-right: 90px; padding: 0; ">
		<div class="panel panel-danger" >
		<!--  CABEÇALHO DA TABELA DE DADOS -->
			<div class="panel-heading"  >
				<div class="row">
					<div class="col-sm-6" >
						<strong>RELATORIO DE CAIXA <?php if (isset($_GET['data_i'])){ ?>Data Inicial:  <?php echo invertData($data_i) ?><?php }else{ ?> :  <?php echo $mes_hoje ?>/<?php echo $ano_hoje ?><?php }?></strong>
					</div>
					<div class="col-sm-6" style="text-align: right;">
						<strong>
							<?php if (isset($_GET['filtrar_data'])){ $saldoanterior = $saldo_dti?>
								SALDO ANTERIOR:  <?php echo formata_dinheiro($saldoanterior) ?>
							<?php }else{ $saldoanterior = $saldo_ant?>
								SALDO ANTERIOR:  <?php echo formata_dinheiro($saldoanterior) ?>
							<?php }?>
						</strong>
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
							<th style=" text-align: center;">Folha</th>
							<th style=" text-align: center;">Entradas</th>
							<th style=" text-align: center;">Saidas</th>
							<th style=" text-align: center;">Saldo</th>
						</thead>					
						<tbody>
							<?php
								$pdo = new Database();
								$db = $pdo->dbConnection();
							
								// VERIFICAR SE O CAMPO BUSCA FOI PREENCHIDO
								if(isset($_GET['busca']) && $_GET['busca'] != ''){
									$busca = $_GET['busca'];
								} else{
									$busca = '';
								}
								// VERIFICAR SE A DATA INICIAL FOI PREENCHIDA
								if(isset($_GET['data_i']) && $_GET['data_i'] != ''){
									$data_i = invertData($_GET['data_i']);
								} else{
									$data_i = '';
								}
								// VERIFICAR SE A DATA FINAL FOI PREENCHIDA
								if(isset($_GET['data_f']) && $_GET['data_f'] != ''){
									$data_f = invertData($_GET['data_f']);
								} else{
									$data_f = '';
								}
								// COMANDOS SQL PARA VERIFICAR CAMPO BUSCA, DATA INICIAL E DATA FINAL
								if(isset($_GET['data_i']) && isset($_GET['data_f'])){
									$stmt = $db->prepare("SELECT * FROM lc_movimento WHERE idconta=:contapd and descricao LIKE :busca and datamov>=:data_i and datamov<=:data_f ORDER BY datamov ASC");
									$stmt->bindparam(':contapd', $contapd );
									$stmt->bindvalue(':busca', '%'.$busca.'%' );
									$stmt->bindparam(':data_i', $data_i );
									$stmt->bindparam(':data_f', $data_f );							
									$stmt->execute();
									$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
								}else{
									
									$stmt = $db->prepare("SELECT * FROM lc_movimento WHERE idconta=:contapd and month(datamov)=:mes_hoje and year(datamov)=:ano_hoje ORDER BY datamov ASC");
									$stmt->bindparam(':contapd', $contapd );
									$stmt->bindparam(':mes_hoje', $mes_hoje );
									$stmt->bindparam(':ano_hoje', $ano_hoje );
									
									$stmt->execute();
									$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
								}
								 
								$cont=0;
								$seq=0;
								$saldo=0;
								
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
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $seq; ?></strong></td>
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>">
									<a style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>" href="#edit<?php echo $row['id']; ?>" data-toggle="modal" name="btn_edit_mov" ><strong style="font-size:12px;"><i><u><?php echo $row['id']; ?></u></i></strong></a>									
								<?php include('operations/edit_mov.php'); ?>
								</td>
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo InvertData($row['datamov']); ?></strong></td>
								<td style=" text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['descricao']; ?></strong></td>
								<td style=" text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['cat']; ?> - <a style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>" href="?mes=<?php echo $mes_hoje?>&ano=<?php echo $ano_hoje?>&filtro_cat=<?php echo $cat?>"><strong style="font-size:12px;"><?php echo $categoria?></a></td>
								<td style=" text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['folha']; ?></strong></td>
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
										<?php if (isset($_GET['filtrar_data'])){ $entradas_per = $entradas_ep?>
										<?php echo formata_dinheiro($entradas_ep) ?>
										<?php }else{ $entradas_per = $entradas_m?>
										<?php echo formata_dinheiro($entradas_per) ?>
										<?php }?>
									</span>
								</strong>
							</td>
							<td style="width:100px; text-align: center; ">
								<strong>
									<span style="font-size:12px; color:<?php echo "#C00" ?>">
										<?php if (isset($_GET['filtrar_data'])){ $saidas_per = $saidas_ep?>
										<?php echo formata_dinheiro($saidas_ep) ?>
										<?php }else{ $saidas_per = $saidas_m?>
										<?php echo formata_dinheiro($saidas_per) ?>
										<?php }?>
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
										<?php if (isset($_GET['filtrar_data'])){ $saldoanterior = $saldo_dti?>
										<?php echo formata_dinheiro($saldoanterior) ?>
										<?php }else{ $saldoanterior = $saldo_ant?>
										<?php echo formata_dinheiro($saldoanterior) ?>
										<?php }?>
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
										<?php if (isset($_GET['filtrar_data'])){ $saldo_atual = $saldo_dti+$entradas_ep-$saidas_ep?>
										<?php echo formata_dinheiro($saldo_atual) ?>
										<?php }else{ $saldo_atual = $resultado_mes?>
										<?php echo formata_dinheiro($saldo_atual) ?>
										<?php }?>
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