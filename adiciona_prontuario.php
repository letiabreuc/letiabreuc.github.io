<?php

require_once "classes.php";

$id = NULL;
$paciente_id = NULL;
$protocolo_id = $_POST['protocolo'];
$medico_id = $_POST['medico'];
$valores = NULL;

$prontuario = new Prontuario($id, $paciente_id, $medico_id, $protocolo_id, $valores);
$result = $prontuarioDAO->adicionaProntuario($prontuario);


echo $prontuarioDAO->retornaNovoProntuarioId();