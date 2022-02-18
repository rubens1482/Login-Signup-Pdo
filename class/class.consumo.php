<?php

require_once('config/dbconfig.php');
//
class MOVS
{	
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
//	FUNÇÃO DE CONSULTA		
	public function Lquery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	/*
	/ FUNÇÃO: inserir na base de dados o movimento de caixa	
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php através do modal "operations/add_mov.php" direcionando para a pagina "movements.php" e redirecionando para a  
	/ pagina anterior.	
	*/
	public function register_lc($tipo,$dia,$mes,$ano,$livro,$folha,$cat,$descricao,$valor,$datamov,$idconta) 
	{
		try
		{
			
			$stmt = $this->conn->prepare("INSERT INTO lc_movimento(tipo,dia,mes,ano,livro,folha,cat,descricao,valor,datamov,idconta) VALUES(:tipo, :dia, :mes, :ano, :livro, :folha, :cat, :descricao, :valor, :datamov, :idconta)");
												  
			$stmt->bindparam(":tipo", $tipo);
			$stmt->bindparam(":dia", $dia);
			$stmt->bindparam(":mes", $mes);
			$stmt->bindparam(":ano", $ano);			
			$stmt->bindparam(":livro", $livro);
			$stmt->bindparam(":folha", $folha);
			$stmt->bindparam(":cat", $cat);
			$stmt->bindparam(":descricao", $descricao);
			$stmt->bindparam(":valor", str_replace(",",".",$valor ));
			$stmt->bindparam(":datamov", $datamov);
			$stmt->bindparam(":idconta", $idconta);
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	/*
	/ FUNÇÃO: adicinar movimento de caixa	
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php através do modal "operations/add_mov.php" direcionando para a pagina "movements.php" e redirecionando para a  
	/ pagina anterior.	
	*/
	public function new_mov($tipo,$dia,$mes,$ano,$idlivro,$folha,$cat,$descricao,$valor,$datamov,$idconta) // INSERIR REGISTROS
	{
		try
		{			
			$stmt = $this->conn->prepare("INSERT INTO lc_movimento(tipo,dia,mes,ano,idlivro,folha,cat,descricao,valor,datamov,idconta)VALUES(:tipo,:dia,:mes,:ano,:idlivro,:folha,:cat,:descricao,:valor,:datamov,:idconta)");
			
			$stmt->bindparam(":tipo", $tipo);
			$stmt->bindparam(":dia", $dia);
			$stmt->bindparam(":mes", $mes);	
			$stmt->bindparam(":ano", $ano);
			$stmt->bindparam(":idlivro", $idlivro);
			$stmt->bindparam(":folha", $folha);	
			$stmt->bindparam(":cat", $cat);	
			$stmt->bindparam(":descricao", $descricao);	
			$stmt->bindparam(":valor", $valor);	
			$stmt->bindparam(":datamov", $datamov);	
			$stmt->bindparam(":idconta", $idconta);	
			
			if($stmt->execute())
			{
				$erro = "inserido";
				echo $erro;
				return $stmt;
			}else
			{
				echo "nao inserido!";
			}
			
			/*
			if($stmt){
				echo $stmt->lastInsertId();
				
			}
			*/	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}	
	/*
	/ FUNÇÃO: alterar na base de dados o movimento de caixa	
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php através do modal "operations/edit_mov.php" direcionando para a pagina "movements.php" e redirecionando para a  
	/ pagina anterior.	
	*/
	public function update_mov($id,$tipo,$dia,$mes,$ano,$idlivro,$folha,$cat,$descricao, $valor, $datamov,$idconta) // ATUALIZAR REGISTROS
	{
		try
		{			
			$stmt = $this->conn->prepare("UPDATE lc_movimento SET tipo=:tipo, dia=:dia, mes=:mes, ano=:ano, idlivro=:idlivro, folha=:folha, cat=:cat, descricao=:descricao, valor=:valor, datamov=:datamov, idconta=:idconta WHERE id=:id");
			$stmt->bindparam(":id", $id);
			$stmt->bindparam(":tipo", $tipo);
			$stmt->bindparam(":dia", $dia);
			$stmt->bindparam(":mes", $mes);
			$stmt->bindparam(":ano", $ano);
			$stmt->bindparam(":idlivro", $idlivro);
			$stmt->bindparam(":folha", $folha);	
			$stmt->bindparam(":cat", $cat);	
			$stmt->bindparam(":descricao", $descricao);	
			$stmt->bindparam(":valor", $valor);	
			$stmt->bindparam(":datamov", $datamov);	
			$stmt->bindparam(":idconta", $idconta);	
				
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	/*
	/ FUNÇÃO: deletar movimento de caixa	
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php através do modal "operations/add_mov.php" no botão "Apagar" ("btn_del_mov") direcionando para a pagina 
	/ "movements.php" e redirecionando para a pagina anterior.	
	*/
	public function delete_mov($id) // DELETAR REGISTRO
	{
		try
		{
			$stmt = $this->conn->prepare("DELETE FROM lc_movimento WHERE id = :id");
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			
			$stmt->execute();
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
	/*
	/ FUNÇÃO: deletar movimento de caixa	
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php através do modal "operations/add_mov.php" no botão "Apagar" ("btn_del_mov") direcionando para a pagina 
	/ "movements.php" e redirecionando para a pagina anterior.	
	*/
	public function select_lc($tipo, $dia, $mes, $ano, $idlivro, $folha, $cat, $descricao, $valor, $datamov, $idconta)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM lc_movimento WHERE datamov>=:datamov and datamov<=:datamov and idconta=:idconta");
			$stmt->execute(array(
			':id'=>$id, 
			':datamov'=>$datamov,
			':descricao'=>$descricao,
			':tipo'=>$tipo,
			':valor'=>$valor,
			':cat'=>$cat,
			':idconta'=>$idconta,
			':idlivro'=>$idconta));
			$lc_Row=$stmt->fetch(PDO::FETCH_ASSOC);
			
			foreach($lc_Row as $value)
			{
				return($lc_Row);
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
//	FUNÇÃO LIVRO ATUAL DE LANÇAMENTO	
	public function lc_livro($account)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT MAX(idlivro) FROM lc_movimento WHERE idconta=:account");
			$stmt->execute(array(':account'=>$idconta));
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			
			$_SESSION['livro'] = $result['idlivro'];
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	//	FUNÇÃO LIVRO ATUAL DE LANÇAMENTO	
	public function lc_livromes($account, $mes_hoje, $ano_hoje)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT idlivro FROM lc_movimento WHERE idconta=:account and month(datamov)=:mes_hoje and year(datamov)=:ano_hoje ORDER BY datamov ASC");
			$stmt->execute(array(
			':account'=>$idconta,
			':mes_hoje'=>$mes_hoje,
			':ano_hoje'=>$ano_hoje));
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			
			$_SESSION['livromes'] = $result['idlivro'];
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	public function sel_mov_id($id)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM lc_movimento WHERE id=:id");
			$stmt->execute();
			$mov_Row=$stmt->fetchObject();
			
			$id = $mov_Row['id'];
			$tipo = $mov_Row['tipo'];
			$folha = $mov_Row['folha'];
			$cat = $mov_Row['cat'];
			$descricao = $mov_Row['descricao'];
			$valor = $mov_Row['valor'];
			$datamov = $mov_Row['datamov'];
			$idconta = $mov_Row['idconta'];
			
				
			{
				return($result);
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	/*
	/ FUNÇÃO: redirecionar para a pagina especifica	
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php, login.php, login_c.php, signup.php, signup_c.php.  
	/ pagina anterior.	
	*/
	public function redirect($url)
	{
		header("Location: $url");
	}	
	/*
	/ FUNÇÃO: Utilizado no "BALANÇO DE MOVIMENTO - BALANÇO MENSAL" e "BALANÇO DE MOVIMENTO - BALANÇO ANUAL" quando a opção selecionada for ano/mes 
	/ PAGINAS: index.php filtrarpordata.php e filtrarporfolha.php
	/ dados retornados: idconta, mes, ano, saldo_ano_ant, saldo_anterior_mes, credito_mes, debito_mes, saldo_atual_mes, bal_mes, credito_acum_ano, debito_acum_ano
	/ CORRIGIDO
	*/
	public function bal_pormes($idconta, $mes_hoje, $ano_hoje )
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT idconta, DATE_FORMAT(datamov,'%m') AS mes, DATE_FORMAT(datamov,'%Y') AS ano,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y') > DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS saldo_ano_ant,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') > DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS saldo_anterior_mes, SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_mes, SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_mes,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') >=DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS saldo_atual_mes,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE DATE_FORMAT(lc_movimento.datamov,'%Y%m') = DATE_FORMAT(L2.datamov,'%Y%m') and idconta = lc_movimento.idconta) AS bal_mes,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE tipo=1 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=month(L3.datamov) and idconta = lc_movimento.idconta) AS credito_acum_ano,(SELECT SUM(valor)*-1 FROM lc_movimento AS L3 WHERE tipo=0 and year(lc_movimento.datamov) = year(L3.datamov) and month(lc_movimento.datamov) >=month(L3.datamov) and idconta = lc_movimento.idconta) AS debito_acum_ano,(SELECT credito_acum_ano) + (SELECT debito_acum_ano) as bal_acum,(SELECT saldo_ano_ant) + (SELECT credito_acum_ano) + (SELECT debito_acum_ano) as saldo_acum_ano FROM lc_movimento WHERE idconta=:contapd and mes=:mes_hoje and ano=:ano_hoje GROUP BY idconta, mes, ano ORDER BY ano, mes;");
			$stmt->bindParam(":contapd", $idconta, PDO::PARAM_INT);
			$stmt->bindParam(":mes_hoje", $mes_hoje, PDO::PARAM_INT);
			$stmt->bindParam(":ano_hoje", $ano_hoje, PDO::PARAM_INT);
			$stmt->execute();
			
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
	/*
	/ FUNÇÃO: Utilizado no "BALANÇO DE MOVIMENTO - BALANÇO MENSAL" e "BALANÇO DE MOVIMENTO - BALANÇO ANUAL" quando a opção selecionada for ano/mes 
	/ PAGINAS: index.php filtrarpordata.php e filtrarporfolha.php
	/ dados retornados: idconta, mes, ano, saldo_ano_ant, saldo_anterior_mes, credito_mes, debito_mes, saldo_atual_mes, bal_mes, credito_acum_ano, debito_acum_ano
	/ CORRIGIDO
	*/
	public function bal_pordata($idconta, $data_i, $data_f )
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT idconta, (SELECT IF(SUM(IF(tipo = 1, valor, -1*valor)) IS NULL, 0, SUM(IF(tipo = 1, valor, -1*valor))) FROM lc_movimento AS L2 WHERE L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta) as saldo_ant_per, (SELECT SUM(IF(lc_movimento.tipo = 1, valor, 0))) AS credito_per, (SELECT SUM(IF(lc_movimento.tipo = 0, -1*valor, 0))) AS debito_per, (SELECT SUM(IF(lc_movimento.tipo = 1, valor, 0))) + (SELECT (SELECT SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)))) as bal_per, (SELECT saldo_ant_per) + SUM(IF(lc_movimento.tipo = 1, valor, 0)) + SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) as saldo_atual_per,(SELECT SUM(IF(tipo = 1, valor, 0)) FROM lc_movimento AS L3 WHERE L3.idconta=lc_movimento.idconta and L3.datamov <= lc_movimento.datamov) + SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_acum_per,(SELECT SUM(IF(tipo = 0, -1*valor, 0)) FROM lc_movimento AS L3 WHERE  L3.idconta=lc_movimento.idconta and L3.datamov <= lc_movimento.datamov) + SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_acum_per FROM lc_movimento WHERE idconta=:contapd and datamov>=:data_i and datamov<=:data_f GROUP BY idconta;");
			$stmt->bindParam(":contapd", $idconta, PDO::PARAM_INT);
			$stmt->bindParam(":data_i", $data_i, PDO::PARAM_STR);
			$stmt->bindParam(":data_f", $data_f, PDO::PARAM_STR);
			$stmt->execute();
			
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	/*
	/ FUNÇÃO: select dos dados mostrados do mês selecionado quando da escolha de mes/ano
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php. 
	/ CORRIGIDO PARA UTLIZAR NAS TELAS.
	*/
	public function dados_pormes($idconta, $mes_hoje, $ano_hoje )
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT *,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and L2.idconta=lc_movimento.idconta and L2.datamov = lc_movimento.datamov) as credito, (SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and L2.idconta=lc_movimento.idconta and L2.id = lc_movimento.id) as debito, (IF((SELECT Saldo_anterior) IS NULL, 0, (SELECT Saldo_anterior) ) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento WHERE idconta=:contapd and month(datamov)=:mes_hoje and year(datamov)=:ano_hoje ORDER BY datamov ASC");
			/*
			$stmt = $this->conn->prepare("SELECT *, lc_cat.nome,
(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,
(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and L2.idconta=lc_movimento.idconta and L2.datamov = lc_movimento.datamov) as credito, (SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and L2.idconta=lc_movimento.idconta and L2.id = lc_movimento.id) as debito, (IF((SELECT Saldo_anterior) IS NULL, 0, (SELECT Saldo_anterior) ) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento INNER JOIN lc_cat ON lc_movimento.cat=lc_cat.id WHERE idconta=:contapd and month(datamov)=:mes_hoje and year(datamov)=:ano_hoje ORDER BY datamov ASC");	
			*/
			$stmt->bindParam(":contapd", $idconta, PDO::PARAM_INT);
			$stmt->bindParam(":mes_hoje", $mes_hoje, PDO::PARAM_INT);
			$stmt->bindParam(":ano_hoje", $ano_hoje, PDO::PARAM_INT);
			$stmt->execute();
			
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	/*
	/ FUNÇÃO: select dos dados conforme o parametro de busca para o campo "descrição" e por intervalo de datas selecionadas
	/ PAGINAS: filtrarpordata.php. ]
	/ CORRIGIDO A FUNÇÃO.
	*/
	public function dados_pordata($idconta, $busca, $data_i, $data_f)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT *, (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,(SELECT IF(lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and idconta=1 and L2.datamov = lc_movimento.datamov) as credito, (SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and idconta=1 and L2.id = lc_movimento.id) as debito, (SELECT IF((Saldo_anterior) > 0, Saldo_anterior , 0 ) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento WHERE idconta=:contapd and descricao LIKE :busca and datamov>=:data_i and datamov<=:data_f ORDER BY datamov ASC;");
			//$stmt->execute(array(':contapd'=>$idconta, ':busca'=> '%' . $busca . '%', ':data_i'=>$data_i, ':data_f'=>$data_f));
			
			$stmt->bindParam(":contapd", $idconta, PDO::PARAM_INT);
			$stmt->bindvalue(':busca', '%'.$busca.'%', PDO::PARAM_STR);
			$stmt->bindParam(":data_i", $data_i, PDO::PARAM_STR);
			$stmt->bindParam(":data_f", $data_f, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
			
			return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	/*
	/ FUNÇÃO: select dos dados mostrados do mês selecionado quando da escolha de mes/ano
	/ PAGINAS: index.php, filtrarpordata.php e filtrarporfolha.php. 
	/ CORRIGIDO PARA INSERIR NAS PAGINAS DEVIDAS
	*/
	public function bal_porfolha($idconta, $livro, $folha_i, $folha_f )
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT idconta, idlivro, folha, CASE WHEN lc_movimento.idlivro=1 and lc_movimento.folha=1 THEN 0 WHEN lc_movimento.idlivro=1 and lc_movimento.folha>=2 THEN (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L1 WHERE L1.idlivro = lc_movimento.idlivro and L1.folha < lc_movimento.folha and L1.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro >=2 and lc_movimento.folha=1 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro >= 2 and lc_movimento.folha>=2 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L1 WHERE L1.idlivro = lc_movimento.idlivro and L1.folha < lc_movimento.folha and L1.idconta = lc_movimento.idconta) END AS saldo_folha_ant, SUM(IF(lc_movimento.tipo = 1, valor, 0)) AS credito_f, SUM(IF(lc_movimento.tipo = 0, -1*valor, 0)) AS debito_f, CASE WHEN lc_movimento.idlivro=1 and lc_movimento.folha>=1 THEN (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro <= lc_movimento.idlivro and L2.folha <= lc_movimento.folha and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro>=2 and lc_movimento.folha=1 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha = lc_movimento.folha and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro>=2 and lc_movimento.folha>=2 THEN (SELECT SUM(IF(tipo = 1 AND idlivro = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) + (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha <= lc_movimento.folha and L2.idconta = lc_movimento.idconta) WHEN lc_movimento.idlivro>1 and lc_movimento.folha>1 THEN '2-3 ou acima' END AS saldo_atual_f,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro = lc_movimento.idlivro and L2.folha = lc_movimento.folha and L2.idconta = lc_movimento.idconta) AS bal_folha, CASE WHEN lc_movimento.idlivro=1 THEN 0.00 WHEN lc_movimento.idlivro>1 THEN (SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE L2.idlivro < lc_movimento.idlivro and L2.idconta = lc_movimento.idconta) END AS saldo_livro_ant,(SELECT SUM(valor) FROM lc_movimento AS L3 WHERE L3.tipo=1 and L3.idlivro = lc_movimento.idlivro and L3.folha <=lc_movimento.folha and L3.idconta = lc_movimento.idconta)  AS credito_acum,(SELECT IF(SUM(valor) IS NULL , 0, SUM(valor)) FROM lc_movimento AS L3 WHERE L3.tipo=1 and L3.idlivro < lc_movimento.idlivro and L3.idconta = lc_movimento.idconta)  AS cred_la,(SELECT SUM(valor)*-1 FROM lc_movimento AS L3 WHERE L3.tipo=0 and L3.idlivro = lc_movimento.idlivro and L3.folha <=lc_movimento.folha and L3.idconta = lc_movimento.idconta) AS debito_acum,(SELECT IF(SUM(valor) IS NULL , 0, SUM(valor)*-1) FROM lc_movimento AS L3 WHERE L3.tipo=0 and L3.idlivro < lc_movimento.idlivro and L3.idconta = lc_movimento.idconta)  AS deb_la,(SELECT credito_acum) + (SELECT cred_la) + (SELECT debito_acum) + (SELECT deb_la) as bal_cre, (SELECT credito_acum) + (SELECT debito_acum) as bal_acum FROM lc_movimento WHERE idconta=:contapd and idlivro=:idlivro and folha>=:folha_i and folha<=:folha_f GROUP BY idconta, idlivro, folha;");
			
			$stmt->bindParam(":contapd", $idconta, PDO::PARAM_INT);
			$stmt->bindParam(":idlivro", $livro, PDO::PARAM_INT);
			$stmt->bindParam(":folha_i", $folha_i, PDO::PARAM_INT);
			$stmt->bindParam(":folha_f", $folha_f, PDO::PARAM_INT);
			$stmt->execute();
			
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
	/*
	/ FUNÇÃO: RETORNAR O BALANÇO DE MOVIMENTO POR FOLHA E LIVRO
	/ PAGINAS: http://localhost/filtrarporfolhateste.php
	/ DADOS RETORNADOS: idconta, idlivro, folha, saldo_folha_ant, credito, debito, saldo_folha_atual, bal_mes, credito_acum, debito_acum
	/ CORRIGIDO. PODE SER UTILIZADO PARA BUSCAR OS DADOS NAS TELAS 
	*/
	public function dados_porfolha($idconta, $busca, $livro, $folha_i, $folha_f )
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT *,(SELECT SUM(IF(tipo = 1, valor, -1*valor)) FROM lc_movimento AS L2 WHERE IF(L2.datamov < lc_movimento.datamov, L2.datamov < lc_movimento.datamov and L2.idconta = lc_movimento.idconta, L2.datamov = lc_movimento.datamov and L2.idconta = lc_movimento.idconta and L2.id < lc_movimento.id)) AS saldo_anterior,(SELECT IF( lc_movimento.tipo=1, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.id = lc_movimento.id and L2.idconta=lc_movimento.idconta and L2.datamov = lc_movimento.datamov) as credito, (SELECT IF( lc_movimento.tipo=0, SUM(valor), 0 ) FROM lc_movimento as L2 WHERE L2.datamov = lc_movimento.datamov and L2.idconta=lc_movimento.idconta and L2.id = lc_movimento.id) as debito, ((SELECT Saldo_anterior) + (SELECT credito) - (SELECT debito)) as saldo_atual FROM lc_movimento WHERE idconta=:idconta and descricao LIKE :busca and idlivro=:idlivro and folha>=:folha_i and folha<=:folha_f ORDER by datamov;");
			
			$stmt->bindParam(":idconta", $idconta, PDO::PARAM_INT);
			$stmt->bindvalue(':busca', '%'.$busca.'%', PDO::PARAM_STR);
			$stmt->bindParam(":idlivro", $livro, PDO::PARAM_INT);
			$stmt->bindParam(":folha_i", $folha_i, PDO::PARAM_INT);
			$stmt->bindParam(":folha_f", $folha_f, PDO::PARAM_INT);
			$stmt->execute();
			
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
}
?>