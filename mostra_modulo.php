<?php

require_once "classes.php";

$modulo_id = $_POST['id'];

$modulo = $moduloDAO->retornaModulo($modulo_id);

$modulo->mostraModulo();