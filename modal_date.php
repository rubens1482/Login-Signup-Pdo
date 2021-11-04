<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
<script src="jquery-ui-1.12.1/external/jquery/jquery.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="jQuery-Mask-Plugin-v1.7.7-0/jquery.mask.min.js"></script>
<script>
  $( function valida_pdf() {
	  $( "#data_ini, #data_fim" ).datepicker({
		altField: "#actualDate",
		dateFormat: "dd-mm-yy",
		altFormat: "dd-mm-YY",
		showWeek: true,	
		changeMonth: true,
		changeYear: true,
		changeDay: true
	});
	$( ".selector" ).datepicker({
	altFormat: "dd-mm-yy",
	altField: "#actualDate"
	});
  } );
  $ (function() {
	  $("#valor").mask('#,##0.00', {reverse: true});
  }
</script>

	<!-- INICIO DO FORMULARIO MODAL FILTRAR POR DATA -->
    <div class="modal fade" id="modal_date" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <center><h4 class="modal-title" id="myModalLabel">Exportar p/ PDF Ref.: <?php echo $mes_hoje ?>/<?php echo $ano_hoje ?> - Conta: <?php echo $contapd ?></h4></center>
                </div>
                <div class="modal-body" >
					
						<div class="panel panel-primary">
							<div class="panel-body">
								<form class="form-inline" name="form_filtro_cat" method="POST" action="rel_cx_periodo.php" onsubmit="return valida_pdf(this)">
									<div class="controls">
										<label class="control-label" for="inputName">Data Inicial:</label>
										<input type="text" name="data_ini" id="data_ini" value="<?php echo invertData($dti) ?>" class="form-control input-sm" size="8">
										<label class="control-label" for="inputName">Data Final:</label>
										<input type="text" name="data_fim" id="data_fim" value="<?php echo invertData($dti) ?>" class="form-control input-sm" size="8">
									</div>
									<br>
									<div class="controls">
										<label class="control-label" for="inputName">Pesquisar: </label>
										<input type="text" name="busca" placeholder="busca" class="form-control input-sm">
										<label class="control-label" for="inputName">Conta:</label>
										<input type="text" name="conta" value="<?php echo $contapd?>" class="form-control input-sm" size="1">
										<button type="submit" class="btn btn-warning btn-sm" name="btn_date_pdf" ><span class="glyphicon glyphicon-check"></span> Imprimir em PDF </button>	
									</div>
								</form>	
							</div>
						</div>
						
					
				</div>
			</div>
		</div>
	</div>	
<!-- /.modal -->