<?php 

require_once "classes.php";

$evento_id = $_POST['id'];

$eventoDAO->removeEvento($evento_id);
