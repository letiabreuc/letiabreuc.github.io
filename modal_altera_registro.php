<?php

require_once "classes.php";
session_start();

$id = $_POST['id'];
$tipo = $_POST['tipo'];

$usuario = $_SESSION['usuario'];
$id_usuario = $usuario->getId();

$registro = $registroDAO->retornaRegistro($id);

?>

<div class="modal" id="modal-altera-registro-<?php echo $id; ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Alterar <?php echo $registro->getTipo(); ?></h2>
			</div>
			<div class="modal-body">
				<div id="erro-modal-altera-registro"></div>
				<div class="form-group">
					<div class="row">		
						<div class="col-md-6">
							<?php
							if($tipo != "refeicao"){
								?>
								<label><?php echo $registro->getTipo(); ?></label>
								<input type="text" class="form-control" name="nome-altera-<?php echo $id; ?>" value="<?php echo $registro->getNome(); ?>">
								<?php
							} else{
								$cafe_manha = "";
								$almoco = "";
								$jantar = "";
								$lanche = "";
								$cafe_tarde = "";
								$outra = "";
								switch($registro->getNome()){
									case "Café da manhã":
										$cafe_manha = "selected";
										break;
									case "Almoço": 
										$almoco = "selected";
										break;
									case "Jantar": 
										$jantar = "selected";
										break;
									case "Lanche": 
										$lanche = "selected";
										break;
									case "Café da tarde": 
										$cafe_tarde = "selected";
										break;
									case "Outra": 
										$outra = "selected";
										break;
								}
								?>
								<label>Refeição</label>
								<select class="form-control" name="nome-altera-<?php echo $id; ?>">
									<option value="Café da manhã" <?php echo $cafe_manha; ?> >Café da manhã</option>
									<option value="Almoço" <?php echo $almoco; ?> >Almoço</option>
									<option value="Jantar" <?php echo $jantar; ?> >Jantar</option>
									<option value="Lanche" <?php echo $lanche; ?> >Lanche</option>
									<option value="Café da tarde" <?php echo $cafe_tarde; ?> >Café da tarde</option>
									<option value="Outra" <?php echo $outra; ?> >Outra</option>
								</select>
								<?php
							}						
							?>
						</div>												
						
						<div class="col-md-6">
							<label>Horário</label>
							<input type="time" class="form-control" name="horario-altera-<?php echo $id; ?>" value="<?php echo $registro->getHorario(); ?>">
						</div>
					</div>
				</div>
				
				<?php
				if($tipo != "refeicao"){
					?>
					<div class="form-group">
						<label>Descrição</label>
						<textarea class="form-control" name="descricao-altera-<?php echo $id; ?>" rows="5"> <?php echo $registro->getDescricao(); ?> </textarea>
					</div>
					<?php
				} else {						
					$itens = explode(";", $registro->getDescricao());
					$adiciona = count($itens) + 1;
					?>
					<div class="form-group" id="altera-itens-refeicao">
						<label>Itens <button style="padding: 3px 10px;" id="adiciona-altera-item-refeicao" class="btn btn-success" onclick="adicionaAlteraItemRefeicao(<?php echo $adiciona; ?>)"><span class="grlyphicon glyphicon-plus"></span></button></label>

						<?php
						$num_item = 1;

						foreach($itens as $item){
							$dados = explode(":", $item);

							$unidade = "";
							$g = "";
							$ml = "";
							$colheres = "";

							switch($dados[2]){
								case "unidades":
									$unidade = "selected";
									break;
								case "g":
									$g = "selected";
									break;
								case "ml":
									$ml = "selected";
									break;
								case "colheres":
									$colheres = "selected";
									break;										
							}
							?>

							<div class="row altera-item-refeicao" id="altera-item-<?php echo $num_item; ?>">
								<div class="col-md-4">
									<input type="text" class="form-control" name="nome-altera-item-<?php echo $num_item; ?>" placeholder="Item" value="<?php echo $dados[0]; ?>">
								</div>

								<div class="col-md-2">
									<input type="number" class="form-control" name="qtd-altera-item-<?php echo $num_item; ?>" placeholder="Qtd" value="<?php echo $dados[1]; ?>">
								</div>

								<div class="col-md-4">
									<select class="form-control" name="medida-altera-item-<?php echo $num_item; ?>">
										<option value="unidades" <?php echo $unidade; ?> >unidade(s)</option>
										<option value="g" <?php echo $g; ?> >gramas</option>
										<option value="ml" <?php echo $ml; ?> >mililitros</option>
										<option value="colheres" <?php echo $colheres; ?> >colheres</option>
									</select>
								</div>
								<div class="col-md-2">
									<button class="btn btn-danger" onclick="removeAlteraItemRefeicao('altera-item-<?php echo $num_item; ?>')"> - </button>
								</div>
								<br/><br/>
							</div>

							<?php
							$num_item += 1;
						}
						?>
					</div>
					<?php
				}
				?>

				<div id="btn-modal">
					<button class="btn btn-success" data-dismiss="modal" onclick="alteraRegistro(<?php echo $id; ?>, '<?php echo $registro->getTipo(); ?>')">Salvar</button>
				</div> 
			</div>
		</div>
	</div>
</div>