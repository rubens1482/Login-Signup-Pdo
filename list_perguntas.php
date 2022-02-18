<?php
	require_once("config/session.php");
	require_once("class/class.user.php");
	
	include 'functions.php';
	$auth_user = new USER();
	
	$user_id = $_SESSION['user_session'];
	
	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	
	require_once("config/session.php");
	require_once("class/class.account.php");
	
	$auth_account = new ACCOUNT();
	
	$account_id = $_SESSION['account_session'];
	
	$stmt = $auth_account->SqlQuery("SELECT * FROM lc_contass WHERE idconta=:idconta");
	$stmt->execute(array(":idconta"=>$account_id));
	
	$accountRow=$stmt->fetch(PDO::FETCH_ASSOC);
	$contapd = $accountRow['idconta'];
	//
	if (isset($_GET['dia'])) 
    $dia_hoje = $_GET['dia'];
	else
		$dia_hoje = date('d');

	if (isset($_GET['mes']))
		$mes_hoje = $_GET['mes'];
	else
		$mes_hoje = date('m');

	if (isset($_GET['ano']))
		$ano_hoje = $_GET['ano'];
	else
		$ano_hoje = date('Y');
	$hoje = $dia_hoje ."-" . $mes_hoje . "-" . $ano_hoje;

?>
<?php // codigos para saldos 
$pdo = new Database();
$db = $pdo->dbConnection();	

// ARQUIVO DE PAGINAÇÃO

if (isset($_GET['pagina']) && $_GET['pagina']!=""){
	$pagina = $_GET['pagina'];
} else{
	$pagina = 1;
}
if (isset($_GET['itenspag']) && $_GET['itenspag']!= ''){
		$numporpagina = $_GET['itenspag'];
	} else {
		$numporpagina = 5 ;
	}
//$search = isset($_GET['search']) ? $_GET['search'] : '';
if (isset($_GET['search']) && $_GET['search'] != '') {
	$search = $_GET['search'];						
	$sql = "SELECT * FROM lc_cat WHERE nome LIKE :search OR operacao LIKE :search ORDER BY nome ";	
	$resultado = $db->prepare($sql);
	$resultado->bindValue(':search','%'. $search . '%');
	$resultado->execute();
	$numtotalregistros = $resultado->fetchColumn();
	$numporpagina = $numtotalregistros;
}
else{
	$search = '';
	$sqlcount = "SELECT COUNT(*) FROM lc_cat";
	$sql = "SELECT COUNT(*) FROM lc_cat ORDER BY nome ";
	$resultado = $db->prepare($sql);
	$resultado->execute();
	$numtotalregistros = $resultado->fetchColumn();
}	


	// Quantidade de páginas a exibir
	$numPaginas = ceil($numtotalregistros/$numporpagina);
	//define o limite inicial
	$inicio = ($numporpagina*$pagina)- $numporpagina;
	$limit=" limit " . $inicio . "," . $numporpagina;
	// consulta para gerar os dados da paginação
if (isset($_GET['search']) && $_GET['search'] != '') {
	$search = $_GET['search'];	
	$querypaginada = $db->prepare("SELECT * FROM lc_cat WHERE nome LIKE :search OR operacao LIKE :search" . $limit);
	$querypaginada->bindValue(':search','%'. $search . '%');
	$querypaginada->execute();
	$numtotalregistros = $resultado->fetchColumn();
	$numporpagina = $numtotalregistros;
}
else{
	$search = '';
	$sqllist = "";
	$querypaginada = $db->prepare("SELECT * FROM lc_cat" . $limit);
	$querypaginada->execute();
}
	//$itenstotais = $querypaginada->fetch(PDO::FETCH_ASSOC);	
	//$itenstotais = $querypaginada->fetchAll(PDO::FETCH_OBJ);
	
	$pripagina = ($numPaginas - $numPaginas) + 1;
	$ultpagina = $numPaginas;
	$proxima = $pagina + 1;
	$anterior = $pagina - 1;

	$limiteDeLinks = 7;

	$lim_inicial = (($pagina > 1) ? $pagina - $limiteDeLinks : 1);
	$lim_final = ((($pagina + $limiteDeLinks) < $numPaginas) ? $pagina + $limiteDeLinks : $numPaginas);	
	
	$inicial = $inicio + 1;
	//if ($pagina = 1) {
		$final = $inicio + $numporpagina ;
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
	<script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
	<script language="javascript" src="bootstrap/js/scripts.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="style.css" type="text/css"  />
<title>welcome - <?php print($userRow['user_name']); ?></title>


</head>
<body>
<!-- NAVBAR PRINCIPAL COM NOME DA CONTA, DATA ATUAL, MENU DA CONTA E MENU DO USUARIO -->
	<nav class="navbar navbar-default navbar-fixed-top" style="margin: 1px auto;">
		<div class="container" >
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"> ok</span>
				<span class="icon-bar"> vamos </span>
				<span class="icon-bar"> terá </span>
			  </button>
			  <a class="navbar-brand" href="http://www.localhost/Login-Signup-Pdo/home.php">Livro Caixa <?php print $accountRow['conta'] ?></a>
			</div>
			<div id="navbar" class="navbar-collapse collapse" >
				<!-- DIV DO CALENTADIO TIPO MES/ANO -->
				<ul class="nav navbar-nav">
					<li><a href="?mes=<?php echo date('m')?>&ano=<?php echo date('Y')?>"><?php print "Hoje &eacute:"?><strong> <?php echo date('d')?> de <?php echo mostraMes(date('m'))?> de <?php echo date('Y')?></strong></a></li>	
				</ul>
				<!-- DIV DO LOGOUT DA CONTA -->
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span> Ir Para <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home </a></li>
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
				<!-- DIV DO LOGOUT DO USUARIO -->
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span>&nbsp;Hi' <?php echo $userRow['user_email']; ?>&nbsp;<span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li>
							<li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
						  </ul>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
    </nav>   
	
	<div class="container-fluid" style="margin-top: 80px; margin-left:120px; margin-right: 120px; padding: 0;">
		<div class="container-fluid" >
			
		
		<div class="panel panel-default" >
			<!-- BOTÕES SUPERIORES DA PAGINAÇÃO -->
			<div class="panel-heading" style="background-color: #C5F5DC; ">	
				<?php $busca = "&npag=" . $numPaginas . "&itenspag=" . $numporpagina?>
				<?php include "botoes_paginacao.php"; ?>
			</div><!-- FIM DA TAG DOS BOTÕES SUPERIORES -->
			<!-- CORPO DO PAINEL, FORMULARIO DE BUSCA, BOTÔES "FILTRAR" E "NOVO" E LISTA DAS CATEGORIAS -->
			<div class="page-header" >
				<!-- FORMULARIO DE BUSCA, PAGINAS, ITENS, ETC. -->
				<div class="container container-fluid" style="margin-top: 0px; margin-left: 5px; margin-right: 10px; padding:auto; width:100%; ">
					<form class="form-inline" name="form_filtro_cat" method="get" action="?mes=<?php echo $mes_hoje?>&ano=<?php echo $ano_hoje?>">
						<div class="panel panel-default">
							<div class="panel-body">
								<span><strong><label> Ir Para Pagina: </label></strong></span>
								<select class="form-control input-sm" name="pagina" >
									<?php $p=1;
										for ($p=1;$p<=$numPaginas;$p++){
										?>
										<option value="<?php echo $p?>" <?php if ($p==$pagina) echo "selected=selected"?> ><?php echo $p?></option>
									<?php }?>
								</select>
								<span><strong>Pagina: </strong></span><input type="text" name="pagina3" class="form-control input-sm" placeholder="Ir para pagina" style="width:50px;" value="<?php echo $pagina ?>">
								<span><strong> De </strong></span><input type="text" name="npag"  class="form-control input-sm" placeholder="nº paginas" style="width:50px;" value="<?php echo $numPaginas ?>" >
								<span><strong> Itens/paginas: </strong></span><input type="text" name="itenspag" class="form-control input-sm" placeholder="Itens por pagina" style="width:50px;" value="<?php echo $numporpagina ?>">
								<span><strong> Itens: </strong></span><input type="text" name="itensn" class="form-control input-sm" placeholder="Itens " style="width:100px;" value="<?php echo $inicial . " a " . $final ?>">
								<span><strong> Busca: </strong></span><input type="text" name="search" class="form-control input-sm"  style="width:180px;" value="<?php echo $search ?>">
								<button type="submit" name="busca_filtro" class="btn btn-default btn-sm" style="border: solid 1px; border-radius: 1px; box-shadow: 1px 1px 1px 1px black; border-radius:3px 3px 3px 3px;" value="Filtrar" style="border: solid 1px; border-radius:1px; box-shadow: 1px 1px 1px 1px black; width:80px;" >Filtrar</button> ||
								<span ><a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm" style="border: solid 1px; border-radius: 1px; box-shadow: 1px 1px 1px 1px black; border-radius:3px 3px 3px 3px;"><span class="glyphicon glyphicon-plus" ></span> Novo </a></span>
							</div>
						</div>
					</form>
				</div>
				<!-- TABELA DE DADOS DAS CATEGORIAS -->
				<div class="container-fluid" >
					<table class="table table-bordered table-striped table-condensed table-hover" >
						<thead>
							<th>ID</th>
							<th>Descrição</th>
							<th>Tipo Operação</th>
							<th>Botões</th>
						</thead>
						<tbody>
						<?php
						// aqui começa o loop dos dados
						while ($row = $querypaginada->fetch(PDO::FETCH_ASSOC)) { ?>
							<tr>
								<td><a href="#edit<?php echo $row['id']; ?>" data-toggle="modal" name="btn_edit_cat" ><b><i><u><?php echo $row['id']; ?></u></i></b></a></td>
								<td><?php echo $row['nome']; ?></td>
								<td><?php echo $row['operacao']; ?></td>
								<td>
									<a href="#edit<?php echo $row['id']; ?>" data-toggle="modal" name="btn_edit_cat" class="btn btn-warning" style="border: solid 1px; border-radius: 1px; box-shadow: 1px 1px 1px 1px black; border-radius:3px 3px 3px 3px;"><span class="glyphicon glyphicon-edit"></span> Alterar </a> || 
									
									<?php include('operations/edit_del_cat.php'); ?>
								</td>
							</tr>
						<?php } ?>

						</tbody>
					</table>
				</div>
			</div> 
			<!-- BOTÔES INFERIORES DA PAGINAÇÃO -->
			<div class="panel-footer">
			<?php $busca = "&npag=" . $numPaginas . "&itenspag=" . $numporpagina?>
				<?php include "botoes_paginacao.php"; ?>
				<?php include('operations/add_cat.php'); ?>
			</div><!-- FIM DA TAG DOS BOTÔES INFERIORES -->
		</div>
	</div>	 	
</div>
</body>
</html>