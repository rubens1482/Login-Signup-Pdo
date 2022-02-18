<?php
    include('config.php');
    include('Email.php');

    if(isset($_POST['esqueciasenha'])){
        $token = uniqid();
        $_SESSION['email_aluno'] = $_POST['email_aluno'];
        $_SESSION['token'] = $token;

        $sql = $pdo->prepare("SELECT * FROM tb_site_alunos WHERE email_aluno = ?");
        $sql->execute([$_SESSION['email_aluno']]);
        
        if($sql->rowCount() == 1){
            $info = $sql->fetch();

           $mail = new Email('hostdasuahospedagem','contato@doseusite','senhadoemail','Nome do seu site');
           $mail->enviarPara($_POST['email_aluno'], $info['nome_aluno']);
           $url = 'http://localhost/Projetos/Testes/redefinir.php';
           $corpo = 'Olá '.$info['nome_aluno'].', <br>
           Foi solicitada uma redefinição da sua senha na "Nome do site". Acesse o link abaixo para redefinir sua senha.<br>
           <h3><a href="'.$url.'?token='.$_SESSION['token'].'">Redefinir a sua senha</a></h3> 
           <br>            
           Caso você não tenha solicitado essa redefinição, ignore esta mensagem.<br>
           Qualquer problema ou dúvida entre em contato pelo email contato@contato.com';

           $informacoes = ['Assunto'=>'Redefinição de senha', 'Corpo'=>$corpo];           
           $mail->formatarEmail($informacoes);
           
           if($mail->enviarEmail()){
               $data['sucesso'] = true;
           }else{
                $data['erro'] = true;
           }

           die('As orientações para criar uma nova senha no site tal foram enviadas ao seu e-mail.');
      }else{
           die('Não encontramos esse <b>email</b> em nossa base de dados.');
      }
  }
?>