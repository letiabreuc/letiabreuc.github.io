<?php

require_once "classes.php";
require_once "funcoes.php";

$id = NULL;
$descricao = $_POST['descricao'];
$tipo = $_POST['tipo'];
$data = $_POST['data'];
$horario = $_POST['horario'];
$usuario_id = $_POST['usuario'];

$paciente_id = NULL;

$evento = new Evento($id, $descricao, $tipo, $data, $horario, $usuario_id, $paciente_id);

$valida_evento = validaEvento($evento);

if(empty($valida_evento)){	
	$eventoDAO->adicionaEvento($evento);
	echo "";
}else{
	?>
	<span class="text-danger"><?php echo $valida_evento; ?> </span>
	<?php
}
