<?php
$pdo = new Database();
$db = $pdo->dbConnection();

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

include "functions.php";

// $dia = 01;
// $conta = $contapd;
// $mes = $mes_hoje;
// $ano = $ano_hoje;
// $datainicial = date("$dia-$mes-$ano");
// $datafinal = date("t-$mes-$ano");
// CONSULTAS PARA RECEBER OS BALANÇOS, ENTRADAS, SAIDAS E SALDO POR MES/ANO, LIVRO/FOLHA E BALANÇO GERAL ANUAL OU LIVRO/FOLHA
/*
/ FUNÇÃO: RETORNAR OS DADOS DO BALANÇO POR MES E ANO COM ACUMULADO ANUAL ATÉ O MES SELECIONADO
/ DADOS RETORNADOS: idconta, mes, ano, saldo_ano_ant, saldo_anterior_mes, credito_mes, debito_mes, saldo_atual_mes, bal_mes, credito_acum_ano, debito_acum_ano
*/
// $bal_pormes = "SELECT idconta, DATE_FORMAT(datamov,'%m') AS mes, DATE_FORMAT(datamov,'%Y') AS ano,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y') > DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS saldo_ano_ant,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') > DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS saldo_anterior_mes, SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_mes, SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_mes,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >=DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS saldo_atual_mes,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') = DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS bal_mes,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=1 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=month(L3.datamov) and idconta = lc_movimento.idconta) AS credito_acum_ano,(SELECT SUM(valor)*-1 FROM lc_movimento AS L3 WHERE tipo=0 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=month(L3.datamov) and idconta = lc_movimento.idconta) AS debito_acum_ano,(SELECT credito_acum_ano) + (SELECT debito_acum_ano) as bal_acum,(SELECT saldo_ano_ant) + (SELECT credito_acum_ano) + (SELECT debito_acum_ano) as saldo_acum_ano FROM lc_movimento WHERE idconta=:contapd and mes=:mes_hoje and ano=:ano_hoje GROUP BY idconta, mes, ano ORDER BY ano, mes;";
/*
/ FUNÇÃO: BALANÇO POR FOLHA E LIVRO COM ACUMULADO POR ATÉ A FOLHA E LIVRO SELECIONADO
/ DADOS RETORNADOS: idconta, idlivro, folha, saldo_folha_ant, credito, debito, atual, saldo_atual, bal_folha, saldo_livro_ant, credito_acum, debito_acum
*/
// $bal_porfolha = "SELECT idconta, idlivro, folha, CASE WHEN lc_movimento.idlivro=1 and lc_movimento.folha=1 THEN 0 WHEN lc_movimento.idlivro=1 and lc_movimento.folha>=2 THEN (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L1 WHERE L1.idlivro = lc_movimento.idlivro and L1.folha < lc_movimento.folha and L1.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro >=2 and lc_movimento.folha=1 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro >= 2 and lc_movimento.folha>=2 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L1 WHERE L1.idlivro = lc_movimento.idlivro and L1.folha < lc_movimento.folha and L1.idconta = lc_movimento.idconta) END AS saldo_folha_ant, SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_f, SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_f, CASE WHEN lc_movimento.idlivro=1 and lc_movimento.folha>=1 THEN (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro <= lc_movimento.idlivro and L2.folha <= lc_movimento.folha and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro>=2 and lc_movimento.folha=1 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha = lc_movimento.folha and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro>=2 and lc_movimento.folha>=2 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha <= lc_movimento.folha and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro>1 and lc_movimento.folha>1 THEN '2-3 ou acima' END AS saldo_atual_f,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha = lc_movimento.folha and L2.idconta = lc_movimento.idconta) AS bal_folha, CASE WHEN lc_movimento.idlivro=1 THEN 0.00 WHEN lc_movimento.idlivro>1 THEN (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) END AS saldo_livro_ant,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE L3.tipo=1 and L3.idlivro = lc_movimento.idlivro and L3.folha <=lc_movimento.folha and L3.idconta = lc_movimento.idconta)  AS credito_acum,(SELECT IF(SUM(valor) IS NULL , 0, SUM(valor)) FROM lc_movimento AS L3 WHERE L3.tipo=1 and L3.idlivro < lc_movimento.idlivro and L3.idconta = lc_movimento.idconta)  AS cred_la,(SELECT SUM(valor)*-1 FROM lc_movimento AS L3 WHERE L3.tipo=0 and L3.idlivro = lc_movimento.idlivro and L3.folha <=lc_movimento.folha and L3.idconta = lc_movimento.idconta) AS debito_acum,(SELECT IF(SUM(valor) IS NULL , 0, SUM(valor)*-1) FROM lc_movimento AS L3 WHERE L3.tipo=0 and L3.idlivro < lc_movimento.idlivro and L3.idconta = lc_movimento.idconta)  AS deb_la,(SELECT credito_acum) + (SELECT cred_la) + (SELECT debito_acum) + (SELECT deb_la) as bal_cre, (SELECT credito_acum) + (SELECT debito_acum) as bal_acum FROM lc_movimento WHERE idconta=:contapd and idlivro=:livro and folha>=:folha_i and folha<=:folha_f GROUP BY idconta, idlivro, folha;";

/*
/ FUNÇÃO: BALANÇO POR INTERVALO DE DATA, COM VALORES ANTERIORES A DATA INICIAL
/ DADOS RETORNADOS: idconta, saldo_ant_per, credito_per,debito_per, bal_per, saldo_atual_per, credito_acum_par, debito_acum_per, bal_per
*/

// $bal_pordata = "SELECT idconta,((SELECT SUM(IF(tipo = 1, valor, 0)) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta)+(SELECT SUM(IF(tipo = 0, -1*valor, 0)) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta)) AS saldo_ant_per,SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_per, SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_per,(SELECT SUM(IF(lc_movimento.tipo = 1, valor, 0))) + (SELECT SUM(IF(lc_movimento.tipo = 0, -1*valor, 0))) as bal_per,((SELECT SUM(IF(tipo = 1, valor, 0)) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta)+(SELECT SUM(IF(tipo = 0, -1*valor, 0)) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta) + SUM(IF(lc_movimento.tipo = 1, valor, 0)) + SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) ) as saldo_atual_per,(SELECT SUM(IF(tipo = 1, valor, 0)) FROM lc_movimento AS L3 WHERE L3.idconta=lc_movimento.idconta and L3.datamov < lc_movimento.datamov) + SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_acum_per,(SELECT SUM(IF(tipo = 0, -1*valor, 0)) FROM lc_movimento AS L3 WHERE  L3.idconta=lc_movimento.idconta and L3.datamov < lc_movimento.datamov) + SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_acum_per FROM lc_movimento WHERE idconta=:contapd and datamov>=:data_i and datamov<=:data_f GROUP BY idconta;";

/* 
/ FUNÇÃO: DADOS POR MES / ANO SALDO LINHA A LINHA
/ Dados: idconta, id, datamov, descricao, idlivro, folha, mes, ano, saldo_anterior, credito, debito, saldo_atual
/ telas adicionadas: 
*/

//$dados_pormes = "SELECT *, (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and idconta=1 and L2.datamov = lc_movimento.datamov) as credito, (SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and idconta=1 and L2.id = lc_movimento.id) as debito, (IF((SELECT Saldo_anterior) IS NULL, 0, (SELECT Saldo_anterior) ) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento WHERE idconta=:contapd and month(datamov)=:mes_hoje and year(datamov)=:ano_hoje ORDER BY datamov ASC;";
$dados_pormes = "SELECT *, 
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,
(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and L2.idconta=lc_movimento.idconta and L2.datamov = lc_movimento.datamov) as credito, 
(SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and L2.idconta=lc_movimento.idconta and L2.id = lc_movimento.id) as debito, 
(IF((SELECT Saldo_anterior) IS NULL, 0, (SELECT Saldo_anterior) ) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento WHERE idconta=:contapd and month(datamov)=:mes_hoje and year(datamov)=:ano_hoje ORDER BY datamov ASC";
/* 
/ FUNÇÃO: DADOS POR FOLHA E LIVRO
/ Dados: idconta, id, datamov, descricao, lc_cat.nome, idlivro, folha, mes, ano, saldo_anterior, credito, debito, saldo_atual
*/
$dados_porfolha = "SELECT *, (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and idconta=1 and L2.datamov = lc_movimento.datamov) as credito, (SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and idconta=1 and L2.id = lc_movimento.id) as debito, ((SELECT Saldo_anterior) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento WHERE idconta=:contapd and descricao LIKE :busca and idlivro=:idlivro and folha>=:folha_i and folha<=:folha_f ORDER by datamov;";
/* 
/ FUNÇÃO: DADOS POR FOLHA E LIVRO
/ Dados: idconta, id, datamov, descricao, lc_cat.nome, idlivro, folha, mes, ano, saldo_anterior, credito, debito, saldo_atual
*/
$dados_mes_ano_cat = "SELECT lc_movimento.idconta, lc_movimento.tipo, month(lc_movimento.datamov) as mes, year(lc_movimento.datamov) as ano , lc_movimento.cat AS cat, if(lc_movimento.tipo=1, sum(lc_movimento.valor), sum(lc_movimento.valor)*-1) as soma, lc_cat.nome FROM lc_movimento INNER JOIN lc_cat on lc_movimento.cat=lc_cat.id WHERE idconta=:contapd GROUP BY ano, mes, tipo, cat ORDER BY ano, mes, tipo, cat ASC;";

/* 
/ FUNÇÃO: DADOS POR INTERVALO DE DATAS COM CARACTER CORINGA PARA PESQUISA DE TEXTO ESPECIFICO
/ Dados: *, saldo_anterior, credito, debito, saldo_atual
*/
$dados_data_a_data = "SELECT *, (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,(SELECT IF(lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and idconta=1 and L2.datamov = lc_movimento.datamov) as credito, (SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and idconta=1 and L2.id = lc_movimento.id) as debito, (SELECT IF((Saldo_anterior) > 0, Saldo_anterior , 0 ) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento WHERE idconta=:contapd and descricao LIKE :busca and datamov>=:data_i and datamov<=:data_f ORDER BY datamov ASC;";

/* 
/ FUNÇÃO: DADOS POR INTERVALO DE DATAS COM CARACTER CORINGA PARA PESQUISA DE TEXTO ESPECIFICO
/ Dados: idconta, id, datamov, descricao, lc_cat.nome, idlivro, folha, mes, ano, saldo_anterior, credito, debito, saldo_atual
*/

$bal_dia_folha = "SELECT idconta, datamov, folha, idlivro,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta) AS saldo_dia_ant,(SELECT IF(SUM(valor) IS NULL, 0, SUM(valor)) FROM lc_movimento as L2 WHERE L2.tipo=1 and idconta=1 and L2.datamov = lc_movimento.datamov) as credito_dia, (SELECT IF(sum(valor) IS NULL, 0 , sum(valor)) FROM lc_movimento as L2 WHERE L2.tipo=0 and idconta=1 and L2.datamov = lc_movimento.datamov) as debito_dia,((SELECT saldo_dia_ant) + (SELECT credito_dia) - (SELECT debito_dia)) as saldo_dia_atual,(SELECT IF(SUM(valor) IS NULL, 0, SUM(valor)) FROM lc_movimento AS L3 WHERE tipo=1 and L3.datamov <=lc_movimento.datamov and L3.idlivro=lc_movimento.idlivro and L3.folha>=lc_movimento.folha and L3.folha<=lc_movimento.folha and L3.idconta=lc_movimento.idconta) AS cred_acum_dia,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=0 and L3.datamov<=lc_movimento.datamov and L3.idlivro=lc_movimento.idlivro and L3.folha>=lc_movimento.folha and L3.folha<=lc_movimento.folha and L3.idconta=lc_movimento.idconta) AS deb_acum_dia FROM lc_movimento WHERE idconta=:contapd and idlivro=:idlivro and folha>=:folha_i and folha<=:folha_f GROUP by day(datamov) ORDER BY datamov ASC;";

$bal_dia_mes = "SELECT idconta, datamov, (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta) AS saldo_dia_ant,(SELECT IF(SUM(valor) IS NULL, 0, SUM(valor)) FROM lc_movimento as L2 WHERE L2.tipo=1 and idconta=1 and L2.datamov = lc_movimento.datamov) as credito_dia, (SELECT IF(sum(valor) IS NULL, 0 , sum(valor)) FROM lc_movimento as L2 WHERE L2.tipo=0 and idconta=1 and L2.datamov = lc_movimento.datamov) as debito_dia,((SELECT saldo_dia_ant) + (SELECT credito_dia) - (SELECT debito_dia)) as saldo_dia_atual,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=1 and year(L3.datamov) = year(lc_movimento.datamov) and month(L3.datamov) = month(lc_movimento.datamov) and L3.datamov <=lc_movimento.datamov and idconta = lc_movimento.idconta) AS cred_acum_dia,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=0 and year(L3.datamov) = year(lc_movimento.datamov) and month(L3.datamov) = month(lc_movimento.datamov) and L3.datamov<=lc_movimento.datamov and idconta=lc_movimento.idconta) AS deb_acum_dia FROM lc_movimento WHERE idconta=:contapd and month(datamov)=:mes_hoje and year(datamov)=:ano_hoje GROUP by day(datamov) ORDER BY datamov ASC;";

$bal_dia_data = "SELECT idconta, datamov, (SELECT IF(SUM(IF(tipo = 1, valor, -1*valor)) IS NULL, 0,SUM(IF(tipo = 1, valor, -1*valor))) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta=lc_movimento.idconta) AS saldo_dia_ant,(SELECT IF(SUM(valor) IS NULL, 0, SUM(valor)) FROM lc_movimento as L2 WHERE L2.tipo=1 and idconta=1 and L2.datamov = lc_movimento.datamov) as credito_dia, (SELECT IF(sum(valor) IS NULL, 0 , sum(valor)) FROM lc_movimento as L2 WHERE L2.tipo=0 and idconta=1 and L2.datamov = lc_movimento.datamov) as debito_dia,((SELECT saldo_dia_ant) + (SELECT credito_dia) - (SELECT debito_dia)) as saldo_dia_atual,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=1 and L3.datamov<=lc_movimento.datamov and idconta = lc_movimento.idconta) AS cred_acum_dia,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=0 and L3.datamov<=lc_movimento.datamov and idconta = lc_movimento.idconta) AS deb_acum_dia FROM lc_movimento WHERE idconta=:contapd and datamov>=:data_i and datamov<=:data_f GROUP by day(datamov) ORDER BY datamov ASC;";

$bal_geral = "SELECT idconta, DATE_FORMAT(datamov,'%m') AS mes, DATE_FORMAT(datamov,'%Y') AS ano,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2WHERE DATE_FORMAT(lc_movimento.datamov,'%Y') >DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS anoAnt,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >=DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS mesAnt,SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito,SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >=DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS saldo,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=1 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=month(L3.datamov) and idconta = lc_movimento.idconta) AS credito_acum_ano,(SELECT SUM(valor)*-1 FROM lc_movimento AS L3 WHERE tipo=0 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=month(L3.datamov) and idconta = lc_movimento.idconta) AS debito_acum_ano FROM lc_movimento WHERE idconta =:contapd GROUP BY idconta, mes, ano ORDER BY ano, mes;";

//
// ENTRADAS ANO ANTERIOR
/*
	$stmt = $db->prepare("SELECT sum(valor) as entradas_aa FROM lc_movimento WHERE idconta=$contapd && tipo=1 && year(datamov)<'$ano_hoje'");
	$stmt->execute();
	$qreaa=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_aa = $qreaa['entradas_aa'];
*/	
// SAIDAS ANO ANTERIOR
/*
	$stmt = $db->prepare("SELECT sum(valor) as saidas_aa FROM lc_movimento WHERE idconta='$contapd' && tipo=0 && year(datamov)<'$ano_hoje'");
	$stmt->execute();
	$qrsaa=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_aa = $qrsaa['saidas_aa'];
	//echo "Saidas Ano Anterior: " . formata_dinheiro($saidas_aa);
	// echo "<br>";	
// SALDO ANO ANTERIOR	
	$saldo_aa = $entradas_aa-$saidas_aa;
*/	
// ENTRADAS DO MÊS/ANO SELECIONADO
/*
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_m FROM lc_movimento WHERE idconta=$contapd && tipo=1 && month(datamov)='$mes_hoje' && year(datamov)='$ano_hoje'");
	$stmt->execute();
	$qrems=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_m = $qrems['entradas_m'];
	// echo "<br>Entradas ". $mes_hoje ."/". $ano_hoje . ": "  . formata_dinheiro($entradas_m);
*/
// SAIDAS DO MÊS/ANO SELECIONADO
/*
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_m FROM lc_movimento WHERE idconta=$contapd && tipo=0 && month(datamov)='$mes_hoje' && year(datamov)='$ano_hoje'");
	$stmt->execute();
	$qrsms=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_m = $qrsms['saidas_m'];
	// echo "<br>Saidas " . $mes_hoje ."/". $ano_hoje . ": " .  formata_dinheiro($saidas_m);
*/
// SALDO MÊS SELECIONADO
/*
	$saldo_m = $entradas_m-$saidas_m;
*/	
// ENTRADAS ACUMULADAS DO ANO
/*
	if ($mes_hoje == 1){
		$stmt = $db->prepare("SELECT SUM(valor) as ent_aca FROM lc_movimento WHERE idconta=$contapd && tipo=1 && year(datamov)<'$ano_hoje'");
	} else {
		$stmt = $db->prepare("SELECT SUM(valor) as ent_aca FROM lc_movimento WHERE idconta=$contapd && tipo=1 && month(datamov)<'$mes_hoje' && year(datamov)='$ano_hoje'");
	}
	$stmt->execute();
	$qreaca=$stmt->fetch(PDO::FETCH_ASSOC);
	$ent_aca = $qreaca['ent_aca'];
*/	
// SAIDAS ACUMULADAS DO ANO	
/*
	if ($mes_hoje == 1){
		$stmt = $db->prepare("SELECT SUM(valor) as sai_aca FROM lc_movimento WHERE idconta=$contapd && tipo=0 && year(datamov)<'$ano_hoje'");
	} else {
		$stmt = $db->prepare("SELECT SUM(valor) as sai_aca FROM lc_movimento WHERE idconta=$contapd && tipo=0 && month(datamov)<'$mes_hoje' && year(datamov)='$ano_hoje'");
	}
	$stmt->execute();
	$qrsaca=$stmt->fetch(PDO::FETCH_ASSOC);
	$sai_aca = $qrsaca['sai_aca'];
	// echo "<br>Saidas Acumuladas antes de " . $mes_hoje ."/". $ano_hoje . ": " .  formata_dinheiro($sai_aca);	
*/	
// SALDO ACUMULADO DO ANO $sai_aca
/*
	$saldo_aca = $ent_aca-$sai_aca;
*/	
// ENTRADAS GERAL CONTA
/*
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_g FROM lc_movimento WHERE idconta='$contapd' && tipo=1");
	$stmt->execute();
	$qregc=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_g = $qregc['entradas_g'];
*/	
// SAIDAS GERAL CONTA
/*
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_g FROM lc_movimento WHERE idconta='$contapd' && tipo=0");
	$stmt->execute();
	$qrsgc=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_g = $qrsgc['saidas_g'];
*/	
// SALDO GERAL CONTA
	
	
// ENTRADAS BALANCO ANUAL
/*
	$stmt = $db->prepare("SELECT SUM(valor) as ent_acab FROM lc_movimento WHERE idconta='$contapd' and tipo=1 and month(datamov)<='$mes_hoje' and year(datamov)='$ano_hoje'");
	$stmt->execute();
	$qreacab=$stmt->fetch(PDO::FETCH_ASSOC);
	$ent_acab = $qreacab['ent_acab'];
*/	
// SAIDAS BALANÇO ANUAL
/*
	$stmt = $db->prepare("SELECT SUM(valor) as sai_acab FROM lc_movimento WHERE idconta='$contapd' and tipo=0 and month(datamov)<='$mes_hoje' and year(datamov)='$ano_hoje'");
	$stmt->execute();
	$qrsacab=$stmt->fetch(PDO::FETCH_ASSOC);
	$sai_acab = $qrsacab['sai_acab'];
*/	
// SALDO BALANÇO ANUAL
/*
	$saldo_acab = $saldo_aa+$ent_acab-$sai_acab;
*/	
// ENTRADAS DO PERIODO SELECIONADO	
/*
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_ep FROM lc_movimento WHERE idconta='$contapd' && tipo=1 && datamov>='$datainicial' && datamov<='$datafinal'");
	$stmt->execute();
	$qreep=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_ep=$qreep['entradas_ep'];
*/
// SAIDAS DO PERIODO SELECIONADO
/*	
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_ep FROM lc_movimento WHERE idconta='$contapd' && tipo=1 && datamov>='$datainicial' && datamov<='$datafinal'");
	$stmt->execute();
	$qrsep=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_ep=$qrsep['saidas_ep'];
*/
// DEFINIÇÃO DO FILTRO PARA ENTRADA ANTERIOR POR PERIODO
if (isset($_GET["filtrarvarios"])){
	
	$datainicial = $_GET['data_i'];
	$datafinal = $_GET['data_f'];

// ENTRADAS ANTES DA DATA INICIAL
	$stmt = $db->prepare("SELECT SUM(valor) as entradas_di FROM lc_movimento WHERE idconta='$contapd' and tipo=1 and datamov<'$datainicial' ");
	$stmt->execute();
	$qredi=$stmt->fetch(PDO::FETCH_ASSOC);
	$entradas_di=$qreapp['entradas_di'];
	echo $entradas_di;
// SAIDA ANTES DA DATA INICIAL
	$stmt = $db->prepare("SELECT SUM(valor) as saidas_di FROM lc_movimento WHERE idconta='$contapd' && tipo=0 && datamov<'$datainicial'");
	$stmt->execute();
	$qrsdi=$stmt->fetch(PDO::FETCH_ASSOC);
	$saidas_di=$qrsdi['saidas_di'];
// SALDO ANTERIOR A DATA INICIAL
	$saldo_di = $entradas_di-$saidas_di;		
}else{
//$datainicial = date("$primeiro_dia-$mes-$ano");
$datainicial = date("Y-m-d");
//$datafinal = date("$ultimo_dia-$mes-$ano");
$datafinal = date("Y-m-d");
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
?>