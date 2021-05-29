<div class="container-fluid" >
<!-- FORMULARIO DE BUSCA, DATA INICIAL, DATA FINAL E FILTRO DE CATEGORIAS  -->
	<form class="form-inline" name="form_filtro_cat" method="POST" action="rel_pdf.php">
		<div class="panel panel-primary">
			<div class="panel-body">					
				Męs: <input type="text" name="mes" value="<?php echo $mes_hoje?>" class="form-control input-sm" size="1">
				Ano: <input type="text" name="ano" value="<?php echo $ano_hoje?>" class="form-control input-sm" size="1">
				Conta: <input type="text" name="conta" value="<?php echo $contapd?>" class="form-control input-sm" size="1">
				<button type="submit" class="btn btn-warning btn-sm" name="btn_pdf" ><span class="glyphicon glyphicon-check"></span> Imprimir em PDF </button>
				<button type="submit" name="gerar_pdf" class="btn btn-default btn-sm"  value="Filtrar" style="border: solid 1px; border-radius:1px; box-shadow: 1px 1px 1px 1px black; width:80px;" >Filtrar</button>	
				<a href="list_cat.php" class="btn btn-success btn-md">
					<span class="glyphicon glyphicon-pencil"></span> + Categoria
				</a>
				<a href="javascript:;" onclick="abreFecha('add_conta')" class="btn btn-success btn-md" >
					<span class="glyphicon glyphicon-pencil"></span> + Conta 
				</a>
				
			</div>
		</div>
	</form>
</div>