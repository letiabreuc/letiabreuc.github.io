<?php

require_once "classes.php";
require_once "funcoes.php";

session_start();

$usuario = $_SESSION['usuario'];
$opcao = $_POST['opcao'];
$data = explode("-", $_POST['data']);
$proxima_data = $data;
$data_anterior = $data;

if ($opcao == 'dia'){
	$proxima_data[2] += 1;
	$proximo_dia = implode("-", $proxima_data);
	$data_anterior[2] -= 1;
	$dia_anterior = implode("-", $data_anterior);

	$lista_id_eventos = $eventoDAO->retornaEventosData($_POST['data']);

	?>
	<h2 class="principal"> Agenda </h2>
	<div id="agenda">
		<div class="row">
			<div class="col-md-12">
				<div class="btn-group pull-right">
					<button class="btn btn-default" data-toggle="modal" data-target="#modal-evento">&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;</button>
					<button class="btn btn-success" onclick="mostraAgenda('mes', '<?php echo $_POST['data']; ?>')">Mês</button>
				</div>				
				<h3 class="text-center agenda"><?php echo $data[2] . " de " . mostraMes($data[1]); ?></h3>
			</div>
		</div>

		<div class="modal" id="modal-evento">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h3>Adicionar Evento</h3>
					</div>
					<div class="modal-body">
						<div id="erro-modal-evento"></div>
						<div class="row">
							<div class="col-md-6">							
								<div class="form-group">
									<label>Tipo</label>
									<select class="form-control" name="tipo">
										<option value="0">Selecionar</option>
										<option value="1">Consulta</option>
										<option value="2">Exame</option>
										<option value="3">Cirurgia</option>
										<option value="4">Outro</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Horário</label>
									<input class="form-control" type="time" name="horario">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Descrição</label>
							<input class="form-control" type="text" name="descricao">
						</div>					

						<div class="form-group">
							<label>Paciente</label>
							<input class="form-control" type="text" name="paciente">
						</div>

						<div id="btn-modal">
							<button class="btn btn-success" onclick="adicionaEvento('<?php echo $_POST['data']; ?>', '<?php echo $usuario->getId(); ?>')">Adicionar</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row agenda">
			<div class="col-md-12" >
				<table class="table">
					<?php
					for($i=0;$i<24;$i++){
						$horario = $i . ":00";
						?>
						<tr>
							<td class="horario"><b><?php echo $horario; ?></b></td>
							<td>
								<?php
								if($lista_id_eventos){
									foreach($lista_id_eventos as $evento_id){
										$evento = $eventoDAO->retornaEvento($evento_id);
										if($evento->getHorario() == $horario){
											?>
											<button class="close" title="Remover" onclick="removeEvento(<?php echo $evento->getId(); ?>, '<?php echo $_POST['data']; ?>')"> &times; </button>
											<span>
												<?php echo $evento->getDescricao()?>
											</span><br/>
											<?php
										}
									}
								}
								?>
							</td>
						</tr>
						<?php
						foreach($lista_id_eventos as $evento_id){
							$evento = $eventoDAO->retornaEvento($evento_id);
							if($evento->getHorario() != $horario and substr($evento->getHorario(), 0, 2) == $i){
								?>
								<tr>
									<td class="horario">&nbsp;&nbsp;<?php echo $evento->getHorario(); ?></td>
									<td>
										<button class="close" title="Remover" onclick="removeEvento(<?php echo $evento->getId(); ?>, '<?php echo $_POST['data']; ?>')"> &times; </button>
										<span>
											<?php echo $evento->getDescricao()?>
										</span><br/>
									</td>
								</tr>
								<?php
							}
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
	<?php
}

else if ($opcao == "mes"){
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
		$altura = "15%";
	}else{
		$altura = "18.6%";
	}
	
	?>
	<h2 class="principal"> Agenda </h2>
	<div id="agenda">
		<div class="row">
			<div class="col-md-12">
				<button class="btn btn-success pull-right" onclick="mostraAgenda('dia', '<?php echo $_POST['data']; ?>')">Dia</button>
				<h3 class="text-center agenda">
					<button class="btn mes-agenda" onclick="mostraAgenda('mes', '<?php echo $mes_anterior; ?>')">
						<span class="glyphicon glyphicon-triangle-left"></span>
					</button>
					
					<?php echo mostraMes($data[1]); ?>
					<button class="btn mes-agenda" onclick="mostraAgenda('mes', '<?php echo $proximo_mes; ?>')">
						<span class="glyphicon glyphicon-triangle-right"></span>
					</button>
					
				</h3>
			</div>
		</div>
		<div class="row agenda">
			<div class="col-md-12">
				<table class="table-bordered table-striped agenda">
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
	<?php
}
?>