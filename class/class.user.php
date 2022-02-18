<?php

require_once('config/dbconfig.php');

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	// FUN��O PARA EXECUTAR CONSULTAS NO BANCO
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	// FUN��O PARA REGISTRAR USU�RIO NO SISTEMA
	public function register($uname,$umail,$upass,$ufoto)
	{
		try
		{
			$new_password = password_hash($upass, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO users(user_name,user_email,user_pass,users_foto) 
		                                               VALUES(:uname, :umail, :upass, :ufoto)");
												  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":upass", $new_password);										  
			$stmt->bindparam(":ufoto", $ufoto);
			
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	// FUN��O PARA EFETUAR LOGIN NO SISTEMA DO USU�RIO
	public function doLogin($uname,$umail,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_pass FROM users WHERE user_name=:uname OR user_email=:umail ");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($upass, $userRow['user_pass']))
				{
					$_SESSION['user_session_id'] = $userRow['user_id'];
					$_SESSION['user_mail'] = $userRow['user_email'];
					$_SESSION['user_pass'] = $userRow['user_pass'];
					$_SESSION['user_name'] = $userRow['user_name'];
					
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	// FUN��O PARA VERIFICAR LOGIN CORRETO DO USU�RIO
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}

	// FUNCAO PARA BUSCAR OS DADOS DO USUARIO LOGADO substituindo o config_session.php
	public function dadoslogin()
	{
		if(isset($_SESSION['user_session']))
		{
			
			return true;
		}
	}
	// FUN��O PARA REDIRECIONAMENTO DE P�GINA
	public function redirect($url)
	{
		header("Location: $url");
	}
	// FUN��O PARA DESLOGAR DO SISTEMA TANTO DA CONTA QUANTO DO USU�RIO
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
	// FUN��O PARA GERAR CAHVE DE ACESSO PARA RECUPERAR SENHA
	public function checaCadastroEmail($umail)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE user_email=:umail ");
			$stmt->execute(array(':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				return true;
			} else {
				return false;
			}
			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	// FUN��O PARA CHECAR CHAVE DE ACESSO GERADA
	public function ChecaCodigo($codigo)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM lc_recover WHERE codigo=:codigo");
			$stmt->execute(array(':codigo'=> $codigo));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				return true;
			} else {
				return false;
			}
			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
	
	// FUN��O PARA CHECAR VALIDADE DA CHAVE DE RECUPERA��O
	public function ChecaValidadeCodigo($codigo,$data_fim)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM lc_recover WHERE codigo=:codigo && data_fim>=:data_fim");
			$stmt->execute(array(':codigo'=>$codigo, ':data_fim'=>$data_fim));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() >= 1)
			{
				return true;
			} else {
				return false;
			}
			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
	// FUN��O PARA ATUALIZAR SENHA DO USU�RIO
	public function setNovaSenha($novasenha,$id)
	{
		try
		{
			$stmt = $this->conn->prepare("UPDATE users SET user_pass = :novasenha WHERE user_email= :email ");
			$stmt->execute(array(':novasenha'=>$novasenha, ':user_email'=>$umail));
			$usernewrow=$stmt->fetch(PDO::FETCH_ASSOC);	
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	// FUN��O PARA VALIDA��O DE EMAIL
	public function validaEmail($umail)
	{
		try
		{
			if(filter_var($umail, FILTER_VALIDATE_EMAIL)) {
				echo "valido email";
			} else {
				echo "invalido email";
			}
		}
		catch (PDOException$e)
		{
			echo $e->getMessage();
		}
	}

	// FUN��O PARA INSERIR CODIGO DE RECUPERA��O
	public function inserecodigo($codigo,$dt_expirar)
	{
		try
		{	
			$stmt = $this->conn->prepare("INSERT INTO lc_recover(codigo, data_fim) 
		                                               VALUES(:codigo, :dt_expirar)");
			$stmt->bindparam(":codigo", $codigo);
			$stmt->bindparam(":dt_expirar", $dt_expirar);
			
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	// FUN��O PARA INSERIR CODIGO DE RECUPERA��O
	public function deletacodigo($codigo, $dt_fim)
	{
		try
		{	
			$stmt = $this->conn->prepare("DELETE * FROM lc_recover WHERE codigo=:codigo && data_fim>=data_fim");
			$stmt->bindParam(array(':codigo'=> $codigo, ':data_fim'=> $dt_fim));
			
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
}
	
?>