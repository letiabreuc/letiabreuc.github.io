<?php

require_once "classes.php";
require_once "funcoes.php";
session_start();

$usuario = $_SESSION['usuario'];
$data = date("Y-m-d");

$lista_registro = $registroDAO->leRegistro($data, $usuario->getId());
$lista_dataRegistro = array();

foreach($lista_registro as $registro){
	$lista_dataRegistro[$registro->getData()] = "";
}

foreach($lista_registro as $registro){
	$lista_dataRegistro[$registro->getData()] .= $registro->getTipo() . ";";
}
?>

<h2 class="principal">Meus Registros</h2>

<div class="registros">
	<div class="panel" id="lista-registro">
		<div class="panel-body">
			<div class="panel-group" id="accordion">
			<?php
			
			$num_registro = 1;
			foreach($lista_dataRegistro as $indice => $dataRegistro){
				$data = explode("-", $indice);
				$registros = explode(";", substr($dataRegistro, 0, -1));
				$icones = "";
				$tipos_registro = array();
				
				foreach($registros as $registro){
					$tipos_registro[$registro] = 1;
				}
				
				foreach($tipos_registro as $tipo => $valor){
					if($tipo == "refeicao"){
						$classe = "cutlery";
					} else if($tipo == "atividade"){
						$classe = "bicycle";
					} else if($tipo == "sintoma"){
						$classe = "heartbeat";
					} else if($tipo == "medicacao"){
						$classe = "medkit";
					}
					
					$icones .= "&nbsp; <i class='fa fa-" . $classe . "'> </i>";
				}
				?>			
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#registro-<?php echo $num_registro; ?>"> 
								<?php echo $data[2] . " de " . mostraMes($data[1]); ?>
							</a>
							<span class="pull-right">
								<?php echo $icones; ?>
							</span>
						</h4>
					</div>
					
					<div id="registro-<?php echo $num_registro; ?>" class="panel-collapse collapse">
						<?php
						foreach($tipos_registro as $tipo => $valor){
							$lista_registroTipo = $registroDAO->retornaRegistrosTipo($tipo, $indice, $usuario->getId());
							?>
							<h4> &nbsp; <?php echo mostraTipoRegistro($tipo); ?> </h4>
							<ul>
							<?php
							foreach($lista_registroTipo as $registroTipo){
								?>
								<li> 
									<span class="pull-right glyphicon glyphicon-time"> 
										<?php echo $registroTipo->getHorario(); ?>
									</span>  
									<?php echo $registroTipo->getNome(); ?>
									<br/>
									<?php 
									if($tipo != "refeicao"){
										echo $registroTipo->getDescricao();
									} else{
										$itens = explode(";", $registroTipo->getDescricao());
										?>
										<ul>
										<?php
										foreach($itens as $item){
											$dados = explode(":", $item);
											?>
											<li> <?php echo $dados[0]; ?> <small class="text-muted"><?php echo $dados[1] . " " . $dados[2]; ?> </small> </li>
											<?php
										}
										?>
										</ul>
										<?php
									}
									?>
								</li>
								<?php
							}
							?>
							</ul>
							<?php
						}
						?>
					</div>
				</div>
				<?php
				
				$num_registro += 1;
			}
			?>
			</div>
		</div>
	</div>
</div>