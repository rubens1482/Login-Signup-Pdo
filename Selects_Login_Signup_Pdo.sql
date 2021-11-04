===================================================================================================================================================================================
/* 
/ FUNÇÃO: BALANÇO POR FOLHA E LIVRO.
/ Dados: idconta, idlivro, folha,saldo_folha_ant, credito, debito, saldo_atual, bal_mes, saldo_livro_ant, credito_acum, debito_acum
*/
SELECT idconta, idlivro, folha,
CASE
WHEN lc_movimento.idlivro=1 and lc_movimento.folha=1 THEN 0
WHEN lc_movimento.idlivro=1 and lc_movimento.folha>=2 THEN
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L1 WHERE L1.idlivro = lc_movimento.idlivro and L1.folha < lc_movimento.folha and L1.idconta = lc_movimento.idconta)
WHEN lc_movimento.idlivro >=2 and lc_movimento.folha=1 THEN 
(SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta)
WHEN lc_movimento.idlivro >= 2 and lc_movimento.folha>=2 THEN 
(SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L1 WHERE L1.idlivro = lc_movimento.idlivro and
L1.folha < lc_movimento.folha and
L1.idconta = lc_movimento.idconta)
END
AS saldo_folha_ant,
SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito,
SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito,
CASE
WHEN lc_movimento.idlivro=1 and lc_movimento.folha>=1 THEN 
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro <= lc_movimento.idlivro and L2.folha <= lc_movimento.folha and L2.idconta = lc_movimento.idconta)
WHEN lc_movimento.idlivro>=2 and lc_movimento.folha=1 THEN 
(SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha = lc_movimento.folha and L2.idconta = lc_movimento.idconta)
WHEN lc_movimento.idlivro>=2 and lc_movimento.folha>=2 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha <= lc_movimento.folha and L2.idconta = lc_movimento.idconta)
WHEN lc_movimento.idlivro>1 and lc_movimento.folha>1 THEN '2-3 ou acima'
END AS atual,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro <= lc_movimento.idlivro and L2.folha <= lc_movimento.folha and L2.idconta = lc_movimento.idconta) AS saldo_atual,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha = lc_movimento.folha and L2.idconta = lc_movimento.idconta) AS bal_mes,
CASE WHEN lc_movimento.idlivro=1 THEN 0.00
WHEN lc_movimento.idlivro>1 THEN
(SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta)
END AS saldo_livro_ant,
(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE L3.tipo=1 and L3.idlivro = lc_movimento.idlivro and L3.folha <=lc_movimento.folha and L3.idconta = lc_movimento.idconta) AS credito_acum,
(SELECT SUM(valor)*-1 FROM lc_movimento AS L3 WHERE L3.tipo=0 and L3.idlivro = lc_movimento.idlivro and L3.folha <=lc_movimento.folha and L3.idconta = lc_movimento.idconta) AS debito_acum
FROM lc_movimento
WHERE idconta=1 and idlivro=2 and folha>=1 and folha<=2 GROUP BY idconta, idlivro, folha;

=====================================================================================================================================================================================
/* 
/ FUNÇÃO: DADOS POR FOLHA E LIVRO COM SALDO LINHA A LINHA
/ Dados: idconta, idlivro, folha,saldo_folha_ant, credito, debito, saldo_atual, bal_mes, saldo_livro_ant, credito_acum, debito_acum
*/
SELECT *, (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = 
lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,
(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and idconta=1 and L2.datamov = lc_movimento.datamov) as credito, 
(SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and idconta=1 and L2.id = lc_movimento.id) as debito, 
((SELECT Saldo_anterior) + (SELECT credito) - (SELECT debito)) as saldo_atual

FROM lc_movimento
WHERE idconta=1 and idlivro=2 and folha=2 ORDER by datamov;/*
/ FUNÇÃO: RETORNAR OS DADOS DO BALANÇO POR MES E ANO COM ACUMULADO ANUAL ATÉ O MES SELECIONADO
/ DADOS RETORNADOS: idconta, mes, ano, saldo_ano_ant, saldo_anterior_mes, credito_mes, debito_mes, saldo_atual_mes, bal_mes, credito_acum_mes, debito_acum_ano
*/
SELECT idconta, DATE_FORMAT(datamov,'%m') AS mes, DATE_FORMAT(datamov,'%Y') AS ano,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y') >
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_ano_ant,

(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_anterior_mes,
SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_mes,
SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_mes,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >=
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_atual_mes,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') =
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS bal_mes,
(SELECT SUM(valor) FROM lc_movimento AS L3
WHERE tipo=1 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=
month(L3.datamov) and 
idconta = lc_movimento.idconta) AS credito_acum_ano,
(SELECT SUM(valor)*-1 FROM lc_movimento AS L3
WHERE tipo=0 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=
month(L3.datamov) and 
idconta = lc_movimento.idconta) AS debito_acum_ano
FROM lc_movimento WHERE idconta = 1 and mes<=12 and ano=2016
GROUP BY idconta, mes, ano ORDER BY ano, mes;

=====================================================================================================================================================================================
/*
/ FUNÇÃO: BALANÇO DOS MESES DO ANO - DEMOSTRATIVO ANUAL MES A MES
/ DADOS RETORNADOS: idconta, mes, ano, saldo_ano_ant, saldo_anterior_mes, credito_mes, debito_mes, saldo_atual_mes, bal_mes, credito_acum_mes, debito_acum_ano
*/
SELECT idconta, DATE_FORMAT(datamov,'%m') AS mes, DATE_FORMAT(datamov,'%Y') AS ano,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y') >
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_ano_ant,

(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_anterior_mes,
SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_mes,
SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_mes,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >=
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_atual_mes,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') =
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS bal_mes,
(SELECT SUM(valor) FROM lc_movimento AS L3
WHERE tipo=1 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=
month(L3.datamov) and 
idconta = lc_movimento.idconta) AS credito_acum_ano,
(SELECT SUM(valor)*-1 FROM lc_movimento AS L3
WHERE tipo=0 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=
month(L3.datamov) and 
idconta = lc_movimento.idconta) AS debito_acum_ano
FROM lc_movimento WHERE idconta = 1 and mes<=12 and ano=2016
GROUP BY idconta, mes, ano ORDER BY ano, mes;
===================================================================================================================================================
/*
/ FUNÇÃO: BALANÇO DO MES / ANO - DEMOSTRATIVO MENSAL
/ DADOS RETORNADOS: idconta, mes, ano, saldo_ano_ant, saldo_anterior_mes, credito_mes, debito_mes, saldo_atual_mes, bal_mes, credito_acum_mes, debito_acum_ano
*/
SELECT idconta, DATE_FORMAT(datamov,'%m') AS mes, DATE_FORMAT(datamov,'%Y') AS ano,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y') >
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_ano_ant,

(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_anterior_mes,
SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_mes,
SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_mes,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >=
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS saldo_atual_mes,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2
WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') =
DATE_FORMAT(L2.datamov,'%Y%m') and
idconta = lc_movimento.idconta) AS bal_mes,
(SELECT SUM(valor) FROM lc_movimento AS L3
WHERE tipo=1 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=
month(L3.datamov) and 
idconta = lc_movimento.idconta) AS credito_acum_ano,
(SELECT SUM(valor)*-1 FROM lc_movimento AS L3
WHERE tipo=0 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=
month(L3.datamov) and 
idconta = lc_movimento.idconta) AS debito_acum_ano
FROM lc_movimento WHERE idconta = 1 and mes<=12 and ano=2016
GROUP BY idconta, mes, ano ORDER BY ano, mes;
=============================================================================================================================================================
/* 
/ FUNÇÃO: DADOS DO MES/ANO SALDO LINHA A LINHA
/ Dados: idconta, id, datamov, descricao, idlivro, folha, cat, mes, ano, saldo_anterior, credito, debito, saldo_atual
*/
SELECT *, 
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = 
lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,
(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and idconta=1 and L2.datamov = lc_movimento.datamov) as credito, 
(SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and idconta=1 and L2.id = lc_movimento.id) as debito, 
((SELECT Saldo_anterior) + (SELECT credito) - (SELECT debito)) as saldo_atual 
 FROM lc_movimento WHERE idconta=1 and month(datamov)=3 and year(datamov)=2015 ORDER BY datamov
 ASC;


select abastecimentos.idveiculo, abastecimentos.data_abast ,abastecimentos.hora_abast ,abastecimentos.quantidade, abastecimentos.valor_unit ,abastecimentos.valorcompra ,(select max(l1.km_abast) from abastecimentos l1 where ((l1.idveiculo = abastecimentos.idveiculo) and (l1.km_abast < abastecimentos.km_abast))) AS km_anterior, abastecimentos.km_abast, (select (abastecimentos.km_abast - km_anterior)) AS km_perc,(select (km_perc / abastecimentos.quantidade)) AS kmporlitro,(select (abastecimentos.valorcompra / km_perc)) AS reaisporkm,(select (abastecimentos.quantidade / km_perc)) AS litrosporkm, fornecedores.razao_social from abastecimentos INNER JOIN fornecedores on abastecimentos.idfornecedor=fornecedores.idfornecedor where abastecimentos.idveiculo=1 ORDER BY  abastecimentos.data_abast ASC;