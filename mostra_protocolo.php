<?php

require_once "classes.php";

session_start();
$usuario = $_SESSION['usuario'];

if (isset($_POST['id'])){
	$protocolo = $protocoloDAO->retornaProtocolo($_POST['id']);
	$listaModuloProtocolo = $moduloProtocoloDAO->retornaModuloProtocolo($protocolo->getId());
	?>
	<h2 class="principal">
		<div class="row">
			<div class="col-md-7">			
				<div class="form-group"> 
					<input class="form-control input-lg" name="nome-protocolo" type="text" value="<?php echo $protocolo->getNome(); ?>">
					<input name="modulos-protocolo" type="hidden" value="<?php 
					foreach($listaModuloProtocolo as $moduloProtocolo){
						if(($moduloProtocolo->getModuloId()) > 1){
							echo ",";
						} 
						echo $moduloProtocolo->getModuloId();
					} ?>">
				</div>
			</div>
			
			<div class="col-md-5">
				<div class="btn-group pull-right">
					<button class="btn btn-default" data-toggle="modal" title="Adicionar Módulos" data-target="#modal-protocolo">
						<span class="glyphicon glyphicon-plus"></span>
					</button>
					<?php if(($protocolo->getId()) > 1){
						?>
					<button class="btn btn-danger" title="Excluir Protocolo" onclick="removeProtocolo(<?php echo $protocolo->getId(); ?>)">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
						<?php				
					}
					?>
					<button class="btn btn-success" id="salva-protocolo" onclick="salvaProtocolo(<?php echo $protocolo->getId(); ?> , <?php echo $usuario->getId(); ?>)">
						<span class="glyphicon glyphicon-floppy-saved"></span>
					</button>
				</div>
			</div>
		</div>		
	</h2>
	<div id="modulos">
			<?php
			foreach($listaModuloProtocolo as $moduloProtocolo){
				$modulo = $moduloDAO->retornaModulo($moduloProtocolo->getModuloId());			
				$modulo->mostraModulo("protocolo");
			}
			?>			
	</div>	
	
	<div id="modal-protocolo" class="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3>Adicionar Módulo</h3>
				</div>
				<div class="modal-body">
					<?php
					$listaModuloModal = $moduloDAO->leModulo();
					foreach($listaModuloModal as $indice => $moduloModal){
						$campos = explode(";", $moduloModal->getCampos());
						if(($indice == 0) or !($indice%2)){
							?>
							<div class="row">
							<?php
						}
						?>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-10">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4><?php echo $moduloModal->getNome(); ?></h4>
										</div>
										<div class="panel-body" id="panel-modulo-modal">
											<ul>
											<?php
											foreach($campos as $campo){
												$valores = explode(":", $campo);
												?>
												<li><?php echo $valores[0]; ?></li>
												<?php
											}
											?>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="checkbox">
									  <label><input type="checkbox" name="modulos[]" value="<?php echo $moduloModal->getId(); ?>"></label>
									</div>
								</div>
							</div>
						</div>
						<?php
						if($indice%2){
							?>
							</div>
							<?php
						}
					}
					?>
					<div id="btn-modal">
						<button class="btn btn-success" data-dismiss="modal" onclick="adicionaModulos(<?php echo $protocolo->getId(); ?>)">Adicionar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
} else {

$lista_protocolo = $protocoloDAO->leProtocolo($usuario->getId());

?>

<h2 class="principal">
	Meus Protocolos 
	<button class="btn btn-success">
		<i class="fa fa-plus" aria-hidden="true" id="adiciona-protocolo" onclick="mostraProtocolo(1)"></i>
	</button>
</h2> 

<div class="panel" id="lista-protocolo">
	<div class="panel-body">
		<?php 
		foreach($lista_protocolo as $numero => $protocolo){
			if($numero%2){
				$icone = "glyphicon-list-alt";
			}else{
				$icone = "glyphicon-list";
			}
			?>
			
			<button class="btn btn-default btn-protocolo" onclick="mostraProtocolo(<?php echo $protocolo->getId(); ?>)">
				<span class="glyphicon <?php echo $icone; ?> icone-protocolo"></span>
				<h4> <?php echo $protocolo->getNome(); ?> </h4>
			</button>
			<?php
		}
		?>		
	</div>
</div>
<?php

}