<?php

require_once "classes.php";

session_start();

$usuario = $_SESSION['usuario'];

if(isset($_POST['id'])){
	$prontuario = $prontuarioDAO->retornaProntuario($_POST['id']);
	$protocolo = $protocoloDAO->retornaProtocolo($prontuario->getProtocoloId());
	$lista_moduloProtocolo = $moduloProtocoloDAO->retornaModuloProtocolo($protocolo->getId());
	$lista_moduloProntuario = explode(";", $prontuario->getValores());
	?>
	<h2 class="principal">
		<div class="row">
			<div class="col-md-9">			
				<?php echo $prontuario->retornaNomePaciente(); ?>
			</div>
			<div class="col-md-3">
				<button class="btn btn-success pull-right" id="salva-prontuario" onclick="salvaProntuario(<?php echo $prontuario->getId(); ?> , <?php echo $usuario->getId(); ?>)">
					<span class="glyphicon glyphicon-floppy-saved"></span>
				</button>
			</div>
		</div>		
	</h2>

	<div id="prontuario">
		<div id="erro-prontuario"></div>
		<?php
		foreach($lista_moduloProtocolo as $moduloProtocolo){
			$modulo = $moduloDAO->retornaModulo($moduloProtocolo->getModuloId());
			if($lista_moduloProntuario[0] == ""){
				$modulo->mostraModulo();
			}else{
				foreach($lista_moduloProntuario as $moduloProntuario){
					$valores = explode(":", $moduloProntuario);
					if($valores[0] == $modulo->getId()){
						$modulo->mostraModuloProntuario($valores);
						break;
					}
				}
			}	
		}
		?>
	</div>
	<?php
} else {
	$lista_prontuario = $prontuarioDAO->leProntuario($usuario->getId());
	?>

<h2 class="principal">
	Meus Prontuarios 
	<button class="btn btn-success" data-toggle="modal" data-target="#modal-adiciona-prontuario">
		<span class="fa fa-plus" id="adiciona-prontuario"></span>
	</button>
</h2> 

<div class="panel" id="lista-prontuario">
	<div class="panel-body">
		<div class="list-group">
		<?php 
		foreach($lista_prontuario as $prontuario){
			$nome_paciente = $prontuario->retornaNomePaciente();
			?>			
			<a href="#" class="list-group-item" onclick="mostraProntuario(<?php echo $prontuario->getId(); ?>)"><h4><span class="glyphicon glyphicon-user"></span> &emsp; <?php echo $nome_paciente; ?></h4></a>
			<?php
		}
		?>
		</div>
	</div>
</div>

<div class="modal" id="modal-adiciona-prontuario">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3>Selecionar Protocolo</h3>
			</div>
			<div class="modal-body">
			<?php 
			$lista_protocolo = $protocoloDAO->leProtocolo($usuario->getId());
			foreach($lista_protocolo as $numero => $protocolo){
				if($numero%2){
					$icone = "glyphicon-list-alt";
				}else{
					$icone = "glyphicon-list";
				}
				?>				
				<button class="btn btn-default btn-protocolo" data-dismiss="modal" onclick="adicionaProntuario('<?php echo $protocolo->getId(); ?>','<?php echo $usuario->getId(); ?>','<?php echo $protocolo->getNome(); ?>')">
					<span class="glyphicon <?php echo $icone; ?> icone-protocolo"></span>
					<h4> <?php echo $protocolo->getNome(); ?> </h4>
				</button>
				<?php
			}
			?>
			</div>
		</div>
	</div>
</div>
<?php

}