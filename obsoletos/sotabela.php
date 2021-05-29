<?php

include "config/config_session.php";
include "functions.php";





	$contapd = 1;
	$mes_hoje = 5;
	$ano_hoje = 2020;
	$mostrar = new MOVS;
	$dados = $mostrar->bal_pormes($contapd,$mes_hoje, $ano_hoje);
	
	foreach($dados as $linha){
	$saldo_aa = $linha['saldo_ano_ant'];
	$saldo_ant = $linha['saldo_anterior_mes'];
	$entradas_m = $linha['credito_mes'];
	$saidas_m = $linha['debito_mes'];
	$resultado_mes = $linha['saldo_atual_mes'];
	$ent_acab = $linha['credito_acum_ano'];
	$sai_acab = $linha['debito_acum_ano'];
	$bal = $linha['bal_mes'];
	$bal_bal = $linha['bal_acum'];
	$saldo_acab = $linha['saldo_acum_ano'];
	//$saldo_acab = $saldo_aa + $ent_acab - $sai_acab;
	} 
	
	
?>
<div class="container-fluid" style="margin-top:0px; margin-left:90px; margin-right: 90px; padding: 0; ">
		<div class="panel panel-primary" >
			<!--  CABEÇALHO DA TABELA DE DADOS -->
			<div class="panel-heading"  >
				<div class="row">
					<div class="col-sm-6" >
						<strong>RELATORIO DE CAIXA <?php echo $mes_hoje ?>/<?php echo $ano_hoje ?></strong>
					</div>
					<div class="col-sm-6" style="text-align: right;">
						<strong>
								SALDO ANTERIOR:  <?php echo formata_dinheiro($saldo_ant) ?>
						</strong>
					</div>
				</div>
			</div>
			<!--  CORPO DA TABELA DE DADOS -->
			<div class="panel-body" >
				<?php 
					
					$dados = $mostrar->dados_pormes($contapd,$mes_hoje, $ano_hoje);	
				?>
				<table id="tb_index" class="table table-responsive table-bordered table-striped table-condensed table-hover" style="">
					<thead>
						<tr >
							<th >Seq.</th>
							<th >Id.</th>
							<th >Data</th>
							<th >Descricao</th>
							<th >Categoria</th>
							<th >Livro/Folha</th>
							<th >Entradas</th>
							<th >Saidas</th>
							<th >Saldo</th>
						</tr>
					</thead>					
					<tbody>
					<?php
						foreach ($dados as $row) {	
						$cont++;
						$seq++;
						
						$cat = $row['cat'];
						$stmt = $db->prepare("SELECT * FROM lc_cat WHERE id='$cat'");
						$stmt->execute();
						$qr2=$stmt->fetch(PDO::FETCH_ASSOC);
						$categoria = $qr2['nome'];	
							
					?>
					<tr >	
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $seq; ?></strong></td>
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>">
							<a style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>;" href="#edit<?php echo $row['id']; ?>" data-toggle="modal" name="btn_edit_mov" ><strong style="font-size:12px;"><b><i><u><?php echo $row['id']; ?></u></i></b></a>									
						<?php include('operations/edit_mov.php'); ?>
						</td>
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo InvertData($row['datamov']); ?></strong></td>
						<td style="text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['descricao']; ?></strong></td>
						<td style="text-align: left; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><a style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>;" href="?mes=<?php echo $mes_hoje?>&ano=<?php echo $ano_hoje?>&filtro_cat=<?php echo $categoria?>"><?php echo $categoria?></strong></a></td>
						<td style="text-align: center; color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><strong style="font-size:12px;"><?php echo $row['idlivro']; ?>-<?php echo $row['folha']; ?></strong></td>
						<td style="text-align: center;">
							<p style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#0000FF"?>"><?php if ($row['tipo']==1) echo "+"; else echo ""?><strong style="font-size:12px;"><?php if ($row['tipo']==1) echo formata_dinheiro($row['credito']); else echo ""?></strong></p>
						</td>
						<td style="text-align: center;">
							<p style="color:<?php if ($row['tipo']==0) echo "#C00"; else echo "#030"?>"><?php if ($row['tipo']==0) echo "-"; else echo ""?><strong style="font-size:12px;"><?php if ($row['tipo']==0) echo formata_dinheiro($row['debito']); else echo ""?></strong></p>
						</td>
						<?php //if ($row['tipo']==1) formata_dinheiro($saldo=$saldo+$row['valor']); else formata_dinheiro($saldo=$saldo-$row['valor']); ?>
						<?php //$acumulado = $saldo_ant+$saldo;?>
						<td style="text-align: center;">
							<p style="color:<?php if ($acumulado>0) echo "#00f"; else echo"#C00" ?>"><strong style="font-size:12px;"><?php echo formata_dinheiro($row['saldo_atual']);?></strong></p>
						</td>							
					</tr>					
					<?php  } ?>						
					</tbody>
					<tfoot>
						<tr>
							<th >Seq.</th>
							<th >Id.</th>
							<th >Data</th>
							<th >Descricao</th>
							<th >Categoria</th>
							<th >Folha</th>
							<th >Entradas</th>
							<th >Saidas</th>
							<th >Saldo</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="panel-footer" >
				<table id="tb_home" class="table table-responsive table-bordered table-striped table-condensed table-hover" >
					<tr>
						<td style="width:220px; text-align: right; " COLSPAN="4"><strong> A TRANSPORTAR TOTAIS DO DIA </strong></td>
						<td style="width:100px; text-align: center; ">
							<strong>
								<span style="font-size:12px; color:<?php echo "#0000FF" ?>">
									<?php echo formata_dinheiro($entradas_m) ?>
								</span>
							</strong>
						</td>
						<td style="width:100px; text-align: center; ">
							<strong>
								<span style="font-size:12px; color:<?php echo "#C00" ?>">
									<?php echo formata_dinheiro($saidas_m) ?>
								</span>
							</strong>
						</td>
						<td style="width:100px; text-align: center; "><?php echo "" ?></td>
					</tr>
					<tr>
						<td style="width:180px; text-align: right; " COLSPAN="4"><strong> SALDO ANTERIOR </strong></td>
						<td style="width:100px; text-align: center; ">
							<strong>
								<span style="font-size:12px; color:<?php echo "#006400" ?>">
									<?php echo formata_dinheiro($saldo_ant) ?>
								</span>
							</strong>
						</td>
						<td style="width:100px; text-align: center; "></td>
						<td style="width:100px; text-align: center; "></td>
					</tr>
					<tr>
						<td style="width:180px; text-align: right; " COLSPAN="4"><strong> SALDO ATUAL </strong></td>
						<td style="width:100px; text-align: center; "></td>
						<td style="width:100px; text-align: center; "></td>
						<td style="width:100px; text-align: center; ">
							<strong>
								<span style="font-size:14px; color:<?php echo "#006400" ?>">
									<?php echo formata_dinheiro($resultado_mes) ?>
								</span>
							</strong>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>