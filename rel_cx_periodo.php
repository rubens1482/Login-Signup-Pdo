<?php

	require_once("config/session.php");
	require_once("class/class.user.php");
	require_once("class/class.account.php");
	include "config/config_session.php";
	include "parameters.php";
	
if (isset($_POST['btn_date_pdf'])){
	$datainicial = $_POST['data_ini'];
	$datafinal = $_POST['data_fim'];
	$busca = $_POST['busca'];
	$cc = $_POST['conta'];	
} 
	$mostrar = new MOVS;
	$dados = $mostrar->bal_pordata($cc, $datainicial, $datafinal );

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
?>

<?php
require('fpdf181/fpdf.php');

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
	$stmt = $db->prepare("SELECT * FROM lc_movimento WHERE idconta=:cc and datamov>=:datainicial and datamov<=:datafinal ORDER BY datamov ASC");
	$stmt->bindparam(':cc', $cc );
	$stmt->bindparam(':datainicial', $datainicial );
	$stmt->bindparam(':datafinal', $datafinal );
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
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
$rel->Cell(280,10,utf8_decode("Referência: " . $datainicial . " a " . $datafinal),0,0,"L");
$rel->Line(50,20,20,20);
$rel->Ln(5);

$rel->SetFont('Arial','B',11);
$rel->SetFillColor(200,220,255);



$rel->Cell(280,10,utf8_decode("Saldo Anterior: " . formata_dinheiro($saldo_ant)),0,0,"R");
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
	
	foreach ($result as $row) {
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
$rel->Cell(30,7,"+" . formata_dinheiro($entradas_ep),1,0,"C",2);
$rel->Cell(30,7,"-" . formata_dinheiro($saidas_ep),1,0,"C",2);
$rel->Cell(30,7,"-" . formata_dinheiro($saldo_ep),1,0,"C",2);

$rel->Output(); 


?>
