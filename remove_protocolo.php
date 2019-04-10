<?php

require_once "classes.php";

$id = $_POST['id'];

$remove = $protocoloDAO->removeProtocolo($id);

if(!$remove){
	echo "Não foi possível remover o protocolo";
}