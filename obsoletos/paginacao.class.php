<?php
class Paginacao{
    private $pagina;
    private $registros_por_pagina;
    private $total_de_paginas;//Setado no método paginasTotal()
        
    /*
     * Função para pegar o número de registro no banco
     * deverá ter no parâmetro a querry
     * EX: SELECT * FROM NOME_DA_TABELA ORDER BY id DESC
     */
    private function paginasTotal($sql_result){
        if(isset($_GET['pag'])){
            $this->pagina = $_GET['pag'];
        }else{
            $this->pagina = 1;
        }
        //Pega o número de registros da tabela
        $total_de_registros = mysql_num_rows($sql_result);
        
        if ($total_de_registros <= $this->registros_por_pagina) {
            $this->total_de_paginas = 1;
        }elseif (($total_de_registros % $this->registros_por_pagina) == 0) {
            $this->total_de_paginas = ($total_de_registros / $this->registros_por_pagina);
        }else{
            $this->total_de_paginas = ($total_de_registros / $this->registros_por_pagina) + 1;
        }
        
        $this->total_de_paginas = (int) $this->total_de_paginas;
        
        if (($this->pagina > $this->total_de_paginas) || ($this->pagina < 0)){
            echo 'Número de página inexistente!';
            exit;
        }        
    }
    
    public function linkNavegacao($sql_result,$registros_por_pagina){  
        $this->registros_por_pagina = $registros_por_pagina;//Setando Registros por pagina
        $this->paginasTotal($sql_result);//Iniciando o método paginasTotal
        /*
         * $sql = $sql . " LIMIT $registro_inicio, $registros_por_pagina";
         */
        $link_de_navegacao = '';            
        $pagina_anterior = $this->pagina - 1;
        $pagina_posterior = $this->pagina + 1;
        
        /* link "anterior" */
        if($pagina_anterior)
        {
            $link_de_navegacao .= " <a href='index.php?pag=$pagina_anterior'>Anterior</a> ";
        }
        for($i = 1; $i <= $this->total_de_paginas; $i++)
        {
            if($i != $this->pagina)
            {
                /* link individual para as outras páginas */
                $link_de_navegacao .= " <a href='index.php?pag=$i'>$i</a> ";
            }else{
                $link_de_navegacao .= " [$i]";
            }
        }
        /* link "proximo" */
        if($this->pagina != $this->total_de_paginas)
        {
            $link_de_navegacao .= "<a href='index.php?pag=$pagina_posterior'>PrÃ³ximo</a>";
        }
             echo "<p>$link_de_navegacao</p>";        
    }
}
?>