<?php 

if($_SERVER['REQUEST_METHOD'] != "POST"){
    header("location: login.php");
}
else{
    require_once "classes.php";
    require_once "funcoes.php";
    
    $username = $_POST['username'];
    $senha = $_POST['senha'];
    
    $valida_login = validaLogin($username, $senha);
    
	if(empty($valida_login)){
		$id = $usuarioDAO->buscaUsuario($username);
		$is_medico = $usuarioDAO->isMedico($id);               
		
		session_start();
		
		if($is_medico){
			$usuario = $medicoDAO->retornaMedico($id);
			
			$_SESSION['usuario'] = $usuario;
			
			header("location: index_medico.php");
		}else{
			$usuario = $pacienteDAO->retornaPaciente($id);
			
			$_SESSION['usuario'] = $usuario;
			
			header("location: index_paciente.php");
		}
	}else{
		session_start();
		$_SESSION['valida_login'] = $valida_login;    
		
		header("location: login.php");
	}
}


?>