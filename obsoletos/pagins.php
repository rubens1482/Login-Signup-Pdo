<?php
$pdo = new Database();
$db = $pdo->dbConnection();
class Paginacao_PDO 
{ 
    public $paginador = 'pag'; 
    private $pagina; 
    public $sql; 
    public $numporpagina = 12; 
    public $i = 5; 
    
    // Construtor carrega a string usada para como paginador 
    public function __construct() 
    { 
        $this->pagina = ( isset ($_REQUEST["{$this->paginador}"]) ) 
                                   ? $_REQUEST["{$this->paginador}"] 
                                   : 0 ; 
    } 
    // Conexão privada 
    //private function conexao() 
    //{ 
    //    $conexao = new Database(); 
    //    $con = $conexao->dbConnection; 
     //   return $con; 
    //} 
    // Retorna o número de resultados encontrados 
    public function resultado() 
    { 
        $this->resultado = $this->conexao()->query(str_replace('*', 'COUNT(*)', $this->sqldadosaexibir)); 
        $this->numeroResultados = $this->resultado->fetchColumn(); 
        return $this->numeroResultados; 
    } 
    // Imprime um texto amigável mostrando o status das paginas em relação ao resultado atual 
    public function imprimeBarraResultados() 
    { 
        if($this->resultado() > 0) { 
            echo '<p class="info_resultado_busca">'; 
            echo 'Página <b style="font-size:20px">' . $this->paginaAtual() . '</b> / <b style="font-size:20px">' . $this->numPaginas() . '</b> de <b style="font-size:20px">'.$this->resultado().'</b> resultados encontrados.</p>'; 
        } else { 
            echo '<p class="info_resultado_busca">Não foram encontrados resultados para sua busca.</p>'; 
        } 
    } 
    // Calcula o número total de páginas 
    public function numPaginas() 
    { 
        $numPaginas = ceil($this->resultado() / $this->numporpagina); 
        return $numPaginas; 
    } 
    // Procura o número da página Atual 
    public function paginaAtual() 
    { 
        if (isset($this->pagina) && is_numeric($this->pagina)) { 
            $this->paginaAtual = (int) $this->pagina; 
        } else { 
            $this->paginaAtual = 1; 
        } 

        if ($this->paginaAtual > $this->numPaginas()) { 
            $this->paginaAtual = $this->numPaginas(); 
        } 

        if ($this->paginaAtual < 1) { 
            $this->paginaAtual = 1; 
        } 

        return $this->paginaAtual; 
        
    } 
    // Calcula o offset da consulta 
    private function numporpagina() 
    { 
        $numporpagina = ($this->paginaAtual() - 1) * $this->numporpagina; 
        return $numporpagina; 
    } 
    // Retorna o SQL para trabalhar posteriormente 
    public function sqldadosaexibir() 
    { 
        $sqldadosaexibir = $this->sqldadosaexibir . " LIMIT {$this->numporpagina} OFFSET {$this->numporpagina()} "; 
        return $sqldadosaexibir; 
    } 
    // Imprime a barra de navegação da paginaçaõ 
    public function imprimeBarraNavegacao() 
    { 
        if($this->resultado() > 0) { 
            echo '<div id="navegacao_busca">'; 
            if ($this->paginaAtual() > 1) { 
                echo " <a href='?" . $this->paginador . "=1" . $this->reconstruiQueryString($this->paginador) . "'><input type='button' class='btn btn-danger' name='primeiro' value='primeiro' ></a> "; 
                $anterior = $this->paginaAtual() - 1; 
                echo " <a href='?" . $this->paginador . "=" . $anterior . $this->reconstruiQueryString($this->paginador) . "'><input type='button' class='btn btn-danger' name='primeiro' value='Anterior' ></a> "; 
            } 
            
            for ($x = ($this->paginaAtual() - $this->i); $x < (($this->paginaAtual() + $this->i) + 1); $x++) { 
                if (($x > 0) && ($x <= $this->numPaginas())) { 
                    if ($x == $this->paginaAtual()) { 
                        echo " <b><input type='button' class='btn btn-danger' name='primeiro' value='$x' disabled></b> "; 
                    } else { 
                        echo " <a href='?" . $this->paginador . "=" . $x . $this->reconstruiQueryString($this->paginador) . "'><input type='button' class='btn btn-danger' name='primeiro' value='$x' ></a> "; 
                    } 
                } 
            } 
            
            if ($this->paginaAtual() != $this->numPaginas()) { 
                $paginaProxima = $this->paginaAtual() + 1; 
                echo " <a href='?" . $this->paginador . "=" . $paginaProxima . $this->reconstruiQueryString($this->paginador) . "'><input type='button' class='btn btn-danger' name='primeiro' value='Proximo' ></a> "; 
                echo " <a href='?" . $this->paginador . "=" . $this->numPaginas() . $this->reconstruiQueryString($this->paginador) . "'><input type='button' class='btn btn-danger' name='primeiro' value='ultimo' ></a> "; 
            } 
            
            echo '</div>'; 
        } 
    } 
    // Monta os valores da Query String novamente 
    public function reconstruiQueryString($valoresQueryString) { 
        if (!empty($_SERVER['QUERY_STRING'])) { 
            $partes = explode("&", $_SERVER['QUERY_STRING']); 
            $novasPartes = array(); 
            foreach ($partes as $val) { 
                if (stristr($val, $valoresQueryString) == false) { 
                    array_push($novasPartes, $val); 
                } 
            } 
            if (count($novasPartes) != 0) { 
                $queryString = "&".implode("&", $novasPartes); 
            } else { 
                return false; 
            } 
            return $queryString; // nova string criada 
        } else { 
            return false; 
        } 
    } 
    
} 
?>