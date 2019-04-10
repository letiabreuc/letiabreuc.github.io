<?php
require_once "classes.php";
session_start();

$usuario = $_SESSION['usuario'];
$tipo = $_POST['tipo'];

$ativo_refeicao = "";
$ativo_atividade = "";
$ativo_sintoma = "";
$ativo_medicacao = "";

switch($tipo){
	case "refeicao" : 
		$ativo_refeicao = "active";
		break;
	case "atividade" :
		$ativo_atividade = "active";
		break;
	case "sintoma" :
		$ativo_sintoma = "active";
		break;
	case "medicacao" :
		$ativo_medicacao = "active";
		break;
};

?>

<h2 class="principal">Registros Diários</h2>
<ul class="nav nav-tabs">
	<li class="<?php echo $ativo_refeicao; ?>"><a data-toggle="tab" href="#registros-alimentacao">Alimentação</a></li>
	<li class="<?php echo $ativo_atividade; ?>"><a data-toggle="tab" href="#registros-atividades">Atividades</a></li>
	<li class="<?php echo $ativo_sintoma; ?>"><a data-toggle="tab" href="#registros-sintomas">Sintomas</a></li>
	<li class="<?php echo $ativo_medicacao; ?>"><a data-toggle="tab" href="#registros-medicacao">Medicação</a></li>
</ul>

<div class="tab-content" id="registros-diarios">
	<div id="registros-alimentacao" class="tab-pane <?php echo $ativo_refeicao; ?>">
		<div class="panel panel-default" style="min-height: 90%">
			<div class="panel-body">
				<?php
				$lista_refeicao = $registroDAO->retornaRegistrosTipo("refeicao", date("Y-m-d"), $usuario->getId());
				
				foreach($lista_refeicao as $refeicao){
					$lista_itens = explode(";", $refeicao->getDescricao());
					
					?>
					<div class="refeicao">
						<a class="pull-right" href="#">
							<span class="glyphicon glyphicon-edit" onclick="modalAlteraRegistro(<?php echo $refeicao->getId(); ?>, 'refeicao')"></span>
						</a>
						<h4 style="margin: 0px" class="text-center"><span class="glyphicon glyphicon-list"></span><?php echo $refeicao->getNome(); ?></h3>
						<p><span class="glyphicon glyphicon-time"></span> <?php echo $refeicao->getHorario(); ?> </p>
						<ul>
							<?php
							foreach($lista_itens as $item){
								$dados = explode(":", $item);
								?>
								<li><?php echo $dados[0]; ?> <small class="text-muted"> <?php echo $dados[1] . " " . $dados[2]; ?> </small></li>
								<?php
							}
							?>
						</ul>
						<hr/>
					</div>
					<?php
				}
				?>
				
				<button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-refeicao">Adicionar</button>
			</div>
		</div>	
	</div>
	
	<div class="modal" id="modal-refeicao">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2>Adicionar Refeição</h2>
				</div>
				<div class="modal-body">
					<div id="erro-modal-refeicao"></div>
					<div class="form-group">
						<div class="row">			
							<div class="col-md-6">									
								<label>Refeição</label>
								<select class="form-control" name="nome-refeicao">
									<option value="Café da manhã">Café da manhã</option>
									<option value="Almoço">Almoço</option>
									<option value="Jantar">Jantar</option>
									<option value="Lanche">Lanche</option>
									<option value="Café da tarde">Café da tarde</option>
									<option value="Outra">Outra</option>
								</select>
							</div>												
							
							<div class="col-md-6">
								<label>Horário</label>
								<input type="time" class="form-control" name="horario-refeicao">
							</div>
						</div>
					</div>
					
					<div class="form-group" id="itens-refeicao">
						<label>Itens <button style="padding: 3px 10px;" id="adiciona-item-refeicao" class="btn btn-success" onclick="adicionaItemRefeicao(2)"><span class="grlyphicon glyphicon-plus"></span></button></label>
						<div class="row item-refeicao" id="item-1">
							<div class="col-md-4">
								<input type="text" class="form-control" name="nome-item-1" placeholder="Item">
							</div>
							<div class="col-md-2">
								<input type="number" class="form-control" name="qtd-item-1" placeholder="Qtd">
							</div>
							<div class="col-md-4">
								<select class="form-control" name="medida-item-1">
									<option value="unidades">unidade(s)</option>
									<option value="g">gramas</option>
									<option value="ml">mililitros</option>
									<option value="colheres">colher(es)</option>
								</select>
							</div>
							<div class="col-md-2">
								<button class="btn btn-danger" onclick="removeItemRefeicao('item-1')"> - </button>
							</div>
							<br/><br/>
						</div>
					</div>
					
					<div id="btn-modal">
						<button class="btn btn-success" onclick="adicionaRegistro('refeicao', '<?php echo $usuario->getId(); ?>')">Adicionar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="registros-atividades" class="tab-pane <?php echo $ativo_atividade; ?>">
		<div class="panel panel-default" style="min-height: 90%">
			<div class="panel-body">			
				<?php
				$lista_atividade = $registroDAO->retornaRegistrosTipo("atividade", date("Y-m-d"), $usuario->getId());
				
				foreach($lista_atividade as $atividade){					
					?>
					<div class="atividade">
						<span class="pull-right">
							<a href="#"><span class="glyphicon glyphicon-trash" onclick="removeRegistro(<?php echo $atividade->getId(); ?>, 'atividade')"></span></a>
							
							&nbsp;
							
							<a href="#">
								<span class="glyphicon glyphicon-edit" onclick="modalAlteraRegistro(<?php echo $atividade->getId(); ?>, 'atividade')"></span>
							</a>
						</span>
						
						<h4 style="margin: 0px" class="text-center"><span class="glyphicon glyphicon-list"></span><?php echo $atividade->getNome(); ?></h3>
						<p><span class="glyphicon glyphicon-time"></span> <?php echo $atividade->getHorario(); ?> </p>
						<ul>
							<li><?php echo $atividade->getDescricao(); ?></li>
						</ul>
						<hr/>
					</div>
					<?php
				}
				?>
				<button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-atividade">Adicionar</button>
			</div>
		</div>
	</div>
	
	<div class="modal" id="modal-atividade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2>Adicionar Atividade</h2>
				</div>
				<div class="modal-body">
					<div id="erro-modal-atividade"></div>
					<div class="form-group">
						<div class="row">			
							<div class="col-md-6">									
								<label>Atividade</label>
								<input type="text" class="form-control" name="nome-atividade">
							</div>												
							
							<div class="col-md-6">
								<label>Horário</label>
								<input type="time" class="form-control" name="horario-atividade">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Descrição</label>
						<textarea class="form-control" name="descricao-atividade" rows="5"></textarea>
					</div>

					<div id="btn-modal">
						<button class="btn btn-success" onclick="adicionaRegistro('atividade', '<?php echo $usuario->getId(); ?>')">Adicionar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="registros-sintomas" class="tab-pane <?php echo $ativo_sintoma; ?>">
		<div class="panel panel-default" style="min-height: 90%">
			<div class="panel-body">
				<?php
				$lista_sintoma = $registroDAO->retornaRegistrosTipo("sintoma", date("Y-m-d"), $usuario->getId());
				
				foreach($lista_sintoma as $sintoma){
					
					?>
					<div class="sintoma">
						<a class="pull-right" href="#">
							<span class="glyphicon glyphicon-edit" onclick="modalAlteraRegistro(<?php echo $sintoma->getId(); ?>, 'sintoma')"></span>
						</a>
						<h4 style="margin: 0px" class="text-center"><span class="glyphicon glyphicon-list"></span><?php echo $sintoma->getNome(); ?></h3>
						<p><span class="glyphicon glyphicon-time"></span> <?php echo $sintoma->getHorario(); ?> </p>
						<ul>
							<li><?php echo $sintoma->getDescricao(); ?></li>
						</ul>
						<hr/>
					</div>
					<?php
				}
				?>				
				<button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-sintoma">Adicionar</button>
			</div>
		</div>
	</div>
	
	<div class="modal" id="modal-sintoma">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2>Adicionar Sintoma</h2>
				</div>
				<div class="modal-body">
					<div id="erro-modal-sintoma"></div>
					<div class="form-group">
						<div class="row">			
							<div class="col-md-6">									
								<label>Sintoma</label>
								<input type="text" class="form-control" name="nome-sintoma">
							</div>												
							
							<div class="col-md-6">
								<label>Horário</label>
								<input type="time" class="form-control" name="horario-sintoma">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Descrição</label>
						<textarea class="form-control" name="descricao-sintoma" rows="5"></textarea>
					</div>

					<div id="btn-modal">
						<button class="btn btn-success" onclick="adicionaRegistro('sintoma', '<?php echo $usuario->getId(); ?>')">Adicionar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="registros-medicacao" class="tab-pane <?php echo $ativo_medicacao; ?>">
		<div class="panel panel-default" style="min-height: 90%">
			<div class="panel-body">
				<?php
				$lista_medicacao = $registroDAO->retornaRegistrosTipo("medicacao", date("Y-m-d"), $usuario->getId());
				
				foreach($lista_medicacao as $medicacao){
					?>
					<div class="medicacao">
						<a class="pull-right" href="#">
							<span class="glyphicon glyphicon-edit" onclick="modalAlteraRegistro(<?php echo $medicacao->getId(); ?>, 'medicacao')"></span>
						</a>
						<h4 style="margin: 0px" class="text-center"><span class="glyphicon glyphicon-list"></span><?php echo $medicacao->getNome(); ?></h3>
						<p><span class="glyphicon glyphicon-time"></span> <?php echo $medicacao->getHorario(); ?> </p>
						<ul>
							<li><?php echo $medicacao->getDescricao(); ?></li>
						</ul>
						<hr/>
					</div>
					<?php
				}
				?>				
				<button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-medicacao">Adicionar</button>
			</div>
		</div>
	</div>
	
	<div class="modal" id="modal-medicacao">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2>Adicionar Medicação</h2>
				</div>
				<div class="modal-body">
					<div id="erro-modal-medicacao"></div>
					<div class="form-group">
						<div class="row">			
							<div class="col-md-6">									
								<label>Nome do Remédio</label>
								<input type="text" class="form-control" name="nome-medicacao">
							</div>												
							
							<div class="col-md-6">
								<label>Horário</label>
								<input type="time" class="form-control" name="horario-medicacao">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Descrição</label>
						<textarea class="form-control" name="descricao-medicacao" rows="5"></textarea>
					</div>

					<div id="btn-modal">
						<button class="btn btn-success" onclick="adicionaRegistro('medicacao', '<?php echo $usuario->getId(); ?>')">Adicionar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>