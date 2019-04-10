<?php

require_once "classes.php";

$id = $_POST['id'];
$tipo = $_POST['tipo'];
$nome = $_POST['nome'];
$horario = $_POST['horario'];

if($tipo == "refeicao"){
	$descricao = substr($_POST['descricao'], 0, -1);
} else{
	$descricao = $_POST['descricao'];
}

$registroDAO->atualizaRegistro($id, $nome, $horario, $descricao);

?>