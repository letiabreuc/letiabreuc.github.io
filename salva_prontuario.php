<?php

require_once "classes.php";
require_once "funcoes.php";

$id = $_POST['id'];
$valores = $_POST['valores'];

$valida_prontuario = validaProntuario($valores);

if(empty($valida_prontuario)){
	$prontuarioDAO->atualizaProntuario($id, $valores);
	echo "";
}else{
	?>
	<span class="text-danger"> <?php echo $valida_prontuario; ?> </span>
	<?php
}
