<?php

require_once "classes.php";

$id = $_POST['id'];
$nome = $_POST['nome'];
$medico = $_POST['medico'];
$modulos = $_POST['modulos'];
$lista_moduloId = explode(",", $modulos);

if($id == 1){
	$protocolo = new Protocolo(NULL,$nome,$medico);
	$protocoloDAO->adicionaProtocolo($protocolo);
	foreach($lista_moduloId as $modulo_id){
		$moduloProtocolo = new ModuloProtocolo($modulo_id, NULL);
		$moduloProtocoloDAO->adicionaModuloProtocolo($moduloProtocolo);
	}
}else{
	$protocoloDAO->atualizaProtocolo($id, $nome);
	$moduloProtocoloDAO->removeModuloProtocolo($id);
	foreach($lista_moduloId as $modulo_id){
		$moduloProtocolo = new ModuloProtocolo($modulo_id, $id);
		$moduloProtocoloDAO->adicionaModuloProtocolo($moduloProtocolo);
	}
}