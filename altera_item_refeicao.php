<?php

$item = $_POST['item'];

?>

<div class="row altera-item-refeicao" id="altera-item-<?php echo $item; ?>">
	<div class="col-md-4">
		<input type="text" class="form-control" name="nome-altera-item-<?php echo $item; ?>" placeholder="Item">
	</div>
	<div class="col-md-2">
		<input type="number" class="form-control" name="qtd-altera-item-<?php echo $item; ?>" placeholder="Qtd">
	</div>
	<div class="col-md-4">
		<select class="form-control" name="medida-altera-item-<?php echo $item; ?>">
			<option value="unidade(s)">unidade(s)</option>
			<option value="g">gramas</option>
			<option value="ml">mililitros</option>
			<option value="colheres">colheres</option>
		</select>
	</div>
	<div class="col-md-2">
		<button class="btn btn-danger" onclick="removeAlteraItemRefeicao('altera-item-<?php echo $item; ?>')"> - </button>
	</div>	
	<br/><br/>
</div>
