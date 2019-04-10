<?php

require_once "classes.php";
require_once "funcoes.php";
session_start();

$usuario = $_SESSION['usuario'];
$usuario_id = $usuario->getId();

$id = NULL;
$nome = $_POST['nome'];
$tipo = $_POST['tipo'];
$data = date("Y-m-d");
$horario = $_POST['horario'];

if($tipo == "refeicao"){
	$descricao = substr($_POST['descricao'], 0, -1);
}else{	
	$descricao = $_POST['descricao'];
}

$registro = new Registro($id, $nome, $tipo, $data, $horario, $descricao, $usuario_id);

$valida_registro = validaRegistro($registro);

if(empty($valida_registro)){	
	$registroDAO->adicionaRegistro($registro);
	echo "";
}else{
	?>
	<span class="text-danger"><?php echo $valida_registro; ?> </span>
	<?php
}