<?php

require_once "classes.php";

$id = $_POST['id'];

$remove = $registroDAO->removeRegistro($id);

if(!$remove){
    echo "Não foi possível remover o registro";
}