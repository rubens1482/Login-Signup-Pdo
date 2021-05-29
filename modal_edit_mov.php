<div class="container-fluid">
	<form role="form" method="POST" action="add_cat.php" class="form-horizontal">
			<div class="row" style="border: solid 1px;">
				<div class="col-xs-6">
					Data: <input type="text" class="form-control" id="inputEmail" >
				</div>
				<div class="col-xs-6">
					Tipo: <input type="text" class="form-control" id="inputEmail" >
				</div>
			</div>
			<div class="row" style="border: solid 1px;">
				<div class="col-xs-6">
					Categoria: <input type="text" class="form-control" id="inputEmail" >
				</div>
				<div class="col-xs-4">
					Descricao: <input type="text" class="form-control" id="inputEmail" >
				</div>
			</div >
			<div class="row">
				<div class="col-xs-6">
					Valor: <input type="text" class="form-control" id="inputEmail" >
				</div>
				<div class="col-xs-4">
					Conta: <input type="text" class="form-control" id="inputEmail" >
				</div>
			</div>	
			<div class="row">
				<div class="col-lg-6">
					<label class="control-label" style="position:relative; top:4px;"> Data: </label>
					<input type="text" class="form-control" name="datamov" style="width:100px;">
				</div>
				<div class="col-lg-6">
					<label class="control-label" style="position:relative; top:1px;"> Tipo: </label>
					<label for="operacao<?php echo $row['id']?>" style="color:#030"><input <?php if($row['operacao']=="credito") echo "checked=checked"?> type="radio" name="operacao" value="credito" id="operacao<?php echo $row['id']?>" /> Entrada </label>&nbsp; 
					<label for="operacao<?php echo $row['id']?>" style="color:#C00"><input <?php if($row['operacao']=="debito") echo "checked=checked"?> type="radio" name="operacao" value="debito" id="operacao<?php echo $row['id']?>" /> Saida </label>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<label class="control-label" style="position:relative; top:4px;"> Categoria:</label>
					<input type="text" class="form-control" name="nome" style="width:100px;">
				</div>
				<div class="row">
		
					<div class="col-lg-6">
						<label class="control-label" style="position:relative; top:4px;"> Categoria:</label>
						<input type="text" class="form-control" name="nome">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2">
					<label class="control-label" style="position:relative; top:7px;">Tipo:</label>
				</div>
				<div class="col-lg-10">
					<select name="operacao" id="operacao" class="form-control input-sm">
					  <option value="debito">debito</option>
					  <option value="credito">credito</option>
					</select>
				</div>
			</div>
	</form>
	</div>