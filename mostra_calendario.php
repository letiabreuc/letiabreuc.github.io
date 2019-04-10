<?php

require_once "classes.php";
require_once "funcoes.php";

$data = explode("-", date("Y-m-d"));
$proxima_data = $data;
$data_anterior = $data;

if($data[1] == 12){
	$proxima_data[1] = 1;
	$proxima_data[0] += 1;
}else{		
	$proxima_data[1] += 1;
}

if($data[1] == 1){
	$data_anterior[1] = 12;
	$data_anterior[0] -= 1;
}else{
	$data_anterior[1] -= 1;		
}	

$proximo_mes = implode("-", $proxima_data);
$mes_anterior = implode("-", $data_anterior);

$dia = 1;
$primeiro_dia = date("w", mktime(0,0,0,$data[1], 1, $data[0]));
$mes_passado = date("t", mktime(0, 0, 0, ($data[1] - 1), 1, $data[0]));
$mes_atual = date("t", mktime(0,0,0,$data[1], 1, $data[0]));
$dia_semana = $primeiro_dia;

if((($primeiro_dia >= 5) and ($mes_atual == 31)) or(($primeiro_dia == 6) and ($mes_atual == 30))){
	$altura = "14.3%";
}else{
	$altura = "17.2%";
}

?>
<div id="agenda">
	<div class="row calendario">
		<div class="col-md-12">
			<table class="table-bordered table-striped agenda">
				<tr class="semana-agenda">
					<td colspan="7" class="text-center">
						<b><?php echo mostraMes(date("m")); ?></b>
					</td>
				</tr>
				<tr class="semana-agenda">
					<th class="bg-success semana-agenda">Dom</th>
					<th class="bg-success semana-agenda">Seg</th>
					<th class="bg-success semana-agenda">Ter</th>
					<th class="bg-success semana-agenda">Qua</th>
					<th class="bg-success semana-agenda">Qui</th>
					<th class="bg-success semana-agenda">Sex</th>
					<th class="bg-success semana-agenda">Sáb</th>
				</tr>
				<tr style="height: <?php echo $altura; ?>">
				<?php				
				
				for($i=($primeiro_dia-1); $i>=0; $i--){
					?>
					<td>
						<button href="#" class="dia-agenda"> 
							<span class="dia-agenda text-muted"> 
								<?php echo $mes_passado-$i; ?>
							</span> 
						</button>  
					</td>
					<?php
				}
				
				while($dia <= $mes_atual){
					if($dia == date("d") and ($data[1] == date("m"))){
						$classe_dia = "dia-hoje";
					}else{
						$classe_dia = "";
					}

					if($dia_semana == 7){
						?>
						</tr>
						<tr style="height: <?php echo $altura; ?>">
						<?php
						$dia_semana = 0;
					}

					?>

					<td>
						<button href="#" class="dia-agenda" onclick="mostraAgenda('dia', '<?php echo $data[0] . '-' . $data[1] . '-' . $dia; ?>')"> 
							<span class="dia-agenda <?php echo $classe_dia; ?>"> 
								<?php echo $dia; ?> 
							</span> 
							<?php
							$lista_eventos = $eventoDAO->retornaEventosData(($data[0] . "-" . $data[1] . "-" . $dia));
							if($lista_eventos){
								foreach($lista_eventos as $evento){
									?>
									<div class="evento">&nbsp;</div>
									<?php
								}
							}
							?>						
						</button> 
					</td>

					<?php
					$dia += 1;
					$dia_semana += 1;
				}
				
				$dia_extra = 1;
				
				while($dia_semana < 7){
					?>
					<td class="text-muted dia-agenda"> 
						<button href="#" class="dia-agenda"> 
							<span class="dia-agenda text-muted"> 
								<?php echo $dia_extra; ?>
							</span> 
						</button> 
					</td>
					<?php
					$dia_extra += 1;
					$dia_semana += 1;
				}
				?>
				</tr>
			</table>
		</div>
	</div>
</div>