<?php
    include('config.php');   
?>

<html>
    <body>
        <?php
            if(isset($_GET['token'])){
                $token = $_GET['token'];
                if($token != $_SESSION['token']){
                    die('O token não corresponde');
                }else{
        ?>

        <div class="bg_login">
            <div class="box_esqueci_a_senha">
            <?php
               $sql = $pdo->prepare("SELECT * FROM tb_site_alunos WHERE email_aluno = ?");
               $sql->execute([$_SESSION['email_aluno']]);
               $info = $sql->fetch();
              
               if($sql->rowCount() == 1){    
                   if(isset($_POST['redefinirsenha'])){
                        $senha = $_POST['senha_aluno'];
                        $criptografada = password_hash($senha, PASSWORD_DEFAULT);
                        $sql = $pdo->prepare("UPDATE tb_site_alunos SET senha_aluno = ? WHERE email_aluno = ?");
                        $sql->execute([$criptografada, $_SESSION['email_aluno']]);
                        echo 'A sua senha foi redefinida com sucesso.';                                                
                   }
               }else{
                   echo 'Não encontramos esse email';
               }  
            ?>

                <div class="head_login">
                    <h2><i class="fas fa-lock"></i> Redefinir a minha senha</h2>
                </div>

                <form method="POST">
                    <div class="input_group">
                        <label for="senha">Digite a sua nova senha</label>
                        <input type="password" id="senha_aluno" name="senha_aluno">
                    </div>

                    <div class="input_group">
                        <input type="submit" name="redefinirsenha" value="Redefinir">
                    </div>
                </form>

                <div class="direitos">
                    <p>Todos os direitos reservados</p>
                </div>
            </div>
        </div>

        <?php
            }   
        ?>

        <?php
            }else{
                echo 'Precisa de um token';
            }   
        ?>
    </body>
</html>