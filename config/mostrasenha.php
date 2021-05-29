<?php 
include "gerasenha.php";
// Gera uma senha com 10 carecteres: letras (min e mai), números
$senha = geraSenha(10);
echo $senha;
// gfUgF3e5m7

// Gera uma senha com 9 carecteres: letras (min e mai)
$senha = geraSenha(9, true, false);
// BJnCYupsN

// Gera uma senha com 6 carecteres: letras minúsculas e números
$senha = geraSenha(6, false, true);
// sowz0g

// Gera uma senha com 15 carecteres de números, letras e símbolos
$senha = geraSenha(15, true, true, true);
// fnwX@dGO7P0!iWM
?>