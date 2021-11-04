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
	$contaname = $accountRow['conta'];
	$name = $accountRow['proprietario'];

if (isset($_POST['btn_pdf'])){
	$mm = $_POST['mes'];
	$aa = $_POST['ano'];
	$cc = $_POST['conta'];
} 

$pdo = new Database();
$db = $pdo->dbConnection();


/*	
$dia = 01;
*/

$datainicial = date("$dia-$mm-$aa");
$datafinal = date("t-$mm-$aa");

// ENTRADAS ANO ANTERIOR
	$stmt = $db->prepare("SELECT sum(valor) as entradas_aa FROM lc_movimento WHERE idconta='$cc' && tipo=1 && year(datamov)<'$aa'");
	$stmt->execute();
	$qreaa=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_aa = $qreaa['entradas_aa'];
	//echo "Entradas Ano Anterior: " . formata_dinheiro($entradas_aa);
	// echo "<br>----------------------------------------------------";
// SAIDAS ANO ANTERIOR
	$stmt = $db->prepare("SELECT sum(valor) as saidas_aa FROM lc_movimento WHERE idconta='$cc' && tipo=0 && year(datamov)<'$aa'");
	$stmt->execute();
	$qrsaa=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_aa = $qrsaa['saidas_aa'];
	//echo "Saidas Ano Anterior: " . formata_dinheiro($saidas_aa);
	// echo "<br>";	
// SALDO ANO ANTERIOR	
	$saldo_aa = $entradas_aa-$saidas_aa;
	
// ENTRADAS DO MÊS SELECIONADO
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_m FROM lc_movimento WHERE idconta='$cc' && tipo=1 && month(datamov)='$mm' && year(datamov)='$aa'");
	$stmt->execute();
	$qrems=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_m = $qrems['entradas_m'];
	// echo "<br>Entradas ". $mes_hoje ."/". $ano_hoje . ": "  . formata_dinheiro($entradas_m);
// SAIDAS DO MÊS SELECIONADO
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_m FROM lc_movimento WHERE idconta='$cc' && tipo=0 && month(datamov)='$mm' && year(datamov)='$aa'");
	$stmt->execute();
	$qrsms=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_m = $qrsms['saidas_m'];
	// echo "<br>Saidas " . $mes_hoje ."/". $ano_hoje . ": " .  formata_dinheiro($saidas_m);
// SALDO MÊS SELECIONADO
	$saldo_m = $entradas_m-$saidas_m;
	
// ENTRADAS ACUMULADO DO ANO
	if ($mm == 1){
		$stmt = $db->prepare("SELECT SUM(valor) as ent_aca FROM lc_movimento WHERE idconta='$cc' && tipo=1 && year(datamov)<'$aa'");
	} else {
		$stmt = $db->prepare("SELECT SUM(valor) as ent_aca FROM lc_movimento WHERE idconta='$cc' && tipo=1 && month(datamov)<'$mm' && year(datamov)='$aa'");
	}
	$stmt->execute();
	$qreaca=$stmt->fetch(PDO::FETCH_ASSOC);
	$ent_aca = $qreaca['ent_aca'];
// SAIDAS ACUMULADO DO ANO	
	if ($mm == 1){
		$stmt = $db->prepare("SELECT SUM(valor) as sai_aca FROM lc_movimento WHERE idconta='$cc' && tipo=0 && year(datamov)<'$aa'");
	} else {
		$stmt = $db->prepare("SELECT SUM(valor) as sai_aca FROM lc_movimento WHERE idconta='$cc' && tipo=0 && month(datamov)<'$mm' && year(datamov)='$aa'");
	}
	$stmt->execute();
	$qrsaca=$stmt->fetch(PDO::FETCH_ASSOC);
	$sai_aca = $qrsaca['sai_aca'];
	// echo "<br>Saidas Acumuladas antes de " . $mes_hoje ."/". $ano_hoje . ": " .  formata_dinheiro($sai_aca);	
// SALDO ACUMULADO DO ANO $sai_aca
	$saldo_aca = $ent_aca-$sai_aca;
	
// ENTRADAS GERAL CONTA
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_g FROM lc_movimento WHERE idconta='$cc' && tipo=1");
	$stmt->execute();
	$qregc=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_g = $qregc['entradas_g'];
// SAIDAS GERAL CONTA
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_g FROM lc_movimento WHERE idconta='$cc' && tipo=0");
	$stmt->execute();
	$qrsgc=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_g = $qrsgc['saidas_g'];
// SALDO GERAL CONTA

// ENTRADAS BALANCO ANUAL
	$stmt = $db->prepare("SELECT SUM(valor) as ent_acab FROM lc_movimento WHERE idconta='$cc' and tipo=1 and month(datamov)<='$mm' and year(datamov)='$aa'");
	$stmt->execute();
	$qreacab=$stmt->fetch(PDO::FETCH_ASSOC);
	$ent_acab = $qreacab['ent_acab'];
// SAIDAS BALANÇO ANUAL
	$stmt = $db->prepare("SELECT SUM(valor) as sai_acab FROM lc_movimento WHERE idconta='$cc' and tipo=0 and month(datamov)<='$mm' and year(datamov)='$aa'");
	$stmt->execute();
	$qrsacab=$stmt->fetch(PDO::FETCH_ASSOC);
	$sai_acab = $qrsacab['sai_acab'];
// SALDO BALANÇO ANUAL
	$saldo_acab = $saldo_aa+$ent_acab-$sai_acab;
// SALDO ANTES DA DATA INICIAL
		
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_ep FROM lc_movimento WHERE idconta='$cc' && tipo=1 && datamov>='$datainicial' && datamov<='$datafinal'");
	$stmt->execute();
	$qreep=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_ep=$qreep['entradas_ep'];
	
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_ep FROM lc_movimento WHERE idconta='$cc' && tipo=1 && datamov>='$datainicial' && datamov<='$datafinal'");
	$stmt->execute();
	$qrsep=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_ep=$qrsep['saidas_ep'];
	
// SALDO ANTERIOR CASO O MÊS SEJA JANEIRO OU OUTROS MESES...
	

	if ($mm == 1){
		$saldo_ant=$saldo_aa;
	} else {
		$saldo_ant=$saldo_aca+$saldo_aa;
	}
	
	if ($mm == 1){
		$resultado_mes=$saldo_aa+$saldo_m;
	} else {
		$resultado_mes=$saldo_aa+$saldo_aca+$saldo_m;
	}	
	
?>

<?php
require('fpdf181/fpdf.php');
ob_start (); 
class PDF extends FPDF
{
function Header()
{
    // Select Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Framed title
	$this->SetDrawColor(0,80,180);
    $this->SetFillColor(230,230,0);
    $this->SetTextColor(220,50,50);
    // Thickness of frame (1 mm)
    $this->SetLineWidth(1);
	$this->Cell($w,9,$title,1,1,'C',true);
    $this->Cell(30,10,'Title',1,0,'C');
    // Line break
    $this->Ln(40);
}

function Footer()
{
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
$pdo = new Database();
	$db = $pdo->dbConnection();
	//$stmt = $db->prepare("SELECT * FROM lc_movimento WHERE idconta='$contapd' and mes='$mes_hoje' and ano='$ano_hoje'");
	//$stmt = $db->prepare("SELECT * FROM lc_movimento WHERE month(datamov)='$mes_hoje' and year(datamov)='$ano_hoje' and idconta='$contapd'");
	/*
	$stmt = $db->prepare("SELECT * FROM lc_movimento WHERE idconta=:cc and month(datamov)=:mm and year(datamov)=:aa ORDER BY datamov ASC");
	$stmt->bindparam(':cc', $cc );
	$stmt->bindparam(':mm', $mm );
	$stmt->bindparam(':aa', $aa );
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
	*/
	$mostrar = new MOVS;
	$dados = $mostrar->dados_pormes($cc,$mm, $aa);	
	$cont=0;
	$seq=0;
	$saldo=0;
	
$rel = new FPDF();
$title = "RELATÓRIO DE CAIXA  - MOVIMENTO MENSAL";
$t = 13;

$rel->AliasNbPages();
$rel->addPage('L','A4');
$rel->SetTitle($title);

$rel->SetFont('Arial','B',11);
$rel->SetFillColor(160,160,160);
$rel->SetTextColor(61,33,33);
$rel->Cell(280,10,utf8_decode("RELATÓRIO DE CAIXA - DEMONSTRATIVO MENSAL"),1,0,"C",2);
$rel->Ln(10);

$rel->SetFont('Arial','B',11);
$rel->Cell(280,10,utf8_decode("Proprietário: " . $name),0,0,"L");
$rel->Ln(5);

$rel->SetFont('Arial','B',11);
$rel->Cell(280,10,utf8_decode("Referência: " . $mm . "/" . $aa),0,0,"L");
$rel->Line(50,20,20,20);
$rel->Ln(5);

$rel->SetFont('Arial','B',11);
$rel->SetFillColor(200,220,255);
if ($mm == 1){
$rel->Cell(280,10,utf8_decode("Saldo Anterior: " . formata_dinheiro($saldo_aa)),0,0,"R");
} else {
$rel->Cell(280,10,utf8_decode("Saldo Anterior: " . formata_dinheiro($saldo_aca+$saldo_aa)),0,0,"R");
}
$rel->Ln(10);

$rel->SetFont('Arial','B',8);
$rel->SetFillColor(160,160,160);
$rel->SetTextColor(63,63,63);
$rel->Cell(10,7,utf8_decode("Seq."),1,0,"C",2);
$rel->Cell(12,7,utf8_decode("Id."),1,0,"C",2);
$rel->Cell(20,7,utf8_decode("Data"),1,0,"C",2);
$rel->Cell(83,7,utf8_decode("Descrição"),1,0,"C",2);
$rel->Cell(55,7,utf8_decode("Categoria"),1,0,"C",2);
$rel->Cell(10,7,utf8_decode("fl"),1,0,"C",2);
$rel->Cell(30,7,utf8_decode("Entradas"),1,0,"C",2);
$rel->Cell(30,7,utf8_decode("Saídas"),1,0,"C",2);
$rel->Cell(30,7,utf8_decode("Saldo"),1,0,"C",2);
$rel->Ln(7);
	
	foreach ($dados as $row) {
		$cont++;
		$seq++;
	
		$cat = $row['cat'];
		$stmt = $db->prepare("SELECT * FROM lc_cat WHERE id='$cat'");
		$stmt->execute();
		$qr2=$stmt->fetch(PDO::FETCH_ASSOC);
		$categoria = $qr2['nome'];

if ($row['tipo']==1){
	$rel->SetTextColor(0,0,240);
} else {
	$rel->SetTextColor(255,0,43);
}
	$rel->Cell(10,7,$seq,1,0,"C");	
	$rel->Cell(12,7,$row["id"],1,0,"C");
	$rel->Cell(20,7,InvertData($row["datamov"]),1,0,"C");
	$rel->Cell(83,7,utf8_decode($row["descricao"]),1,0,"L");
	$rel->Cell(55,7,utf8_decode($categoria),1,0,"L");
	$rel->Cell(10,7,$row["folha"],1,0,"C");
	if ($row['tipo']==1){
		$rel->Cell(30,7,"+" . formata_dinheiro($row["valor"]),1,0,"C");
	} else {
		$rel->Cell(30,7,"",1,0,"C");
	}
	if ($row['tipo']==0){
		$rel->Cell(30,7,"-" . formata_dinheiro($row["valor"]),1,0,"C");
	} else {
		$rel->Cell(30,7,"",1,0,"C");
	}
	if ($row['tipo']==1){
		formata_dinheiro($saldo=$saldo+$row['valor']); 
	} else {
		formata_dinheiro($saldo=$saldo-$row['valor']);
	}
	$acumulado = $saldo_ant+$saldo;
	$rel->Cell(30,7, formata_dinheiro($acumulado),1,0,"C");
$rel->Ln(7);
	}
$rel->SetFont('Arial','B',8);
$rel->SetFillColor(160,160,160);
$rel->SetTextColor(63,63,63);	
$rel->Cell(190,7,"Totais:",1,0,"R",2);
$rel->Cell(30,7,"+" . formata_dinheiro($entradas_m),1,0,"C",2);
$rel->Cell(30,7,"-" . formata_dinheiro($saidas_m),1,0,"C",2);
$rel->Cell(30,7,"-" . formata_dinheiro($resultado_mes),1,0,"C",2);

$rel->Output(); 


?>
