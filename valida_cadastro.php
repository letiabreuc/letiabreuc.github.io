<?php 

if($_SERVER['REQUEST_METHOD'] != "POST"){
    header("location: login.php");
}
else{
    require_once "classes.php";
    require_once "funcoes.php";
    
	$id = NULL;
	
	$valores_cadastro['username'] = $_POST['username'];
	$valores_cadastro['senha'] = $_POST['senha'];
	$valores_cadastro['confirmacao_senha'] = $_POST['confirmacao_senha'];
	$valores_cadastro['email'] = $_POST['email'];
	$valores_cadastro['nome'] = $_POST['nome'];
	$valores_cadastro['sobrenome'] = $_POST['sobrenome'];
	$valores_cadastro['data_nascimento'] = $_POST['data_nascimento'];
	$valores_cadastro['telefone'] = $_POST['telefone'];
	$valores_cadastro['adm'] = 0;	
	$valores_cadastro['tipo'] = $_POST['tipo'];
	
	$valores_cadastro['especialidade'] = $_POST['especialidade'];
	$valores_cadastro['crm'] = $_POST['crm'];
	$valores_cadastro['endereco_comercial'] = $_POST['endereco_comercial'];
	
	$valores_cadastro['cod'] = $_POST['cod'];
	
	if($valores_cadastro['tipo'] == "Medico"){
		$usuario = new Medico($id, $valores_cadastro['username'], $valores_cadastro['senha'], $valores_cadastro['email'], $valores_cadastro['nome'], $valores_cadastro['sobrenome'], 
							  $valores_cadastro['data_nascimento'], $valores_cadastro['telefone'], $valores_cadastro['adm'], $valores_cadastro['especialidade'], 
							  $valores_cadastro['crm'], $valores_cadastro['endereco_comercial']);
	}else{		
		$usuario = new Paciente($id, $valores_cadastro['username'], $valores_cadastro['senha'], $valores_cadastro['email'], $valores_cadastro['nome'], $valores_cadastro['sobrenome'], 
								$valores_cadastro['data_nascimento'], $valores_cadastro['telefone'], $valores_cadastro['adm'], $valores_cadastro['cod']);
	}
	
	$valida_cadastro = validaCadastro($usuario, $valores_cadastro['tipo'], $valores_cadastro['confirmacao_senha']);

	if(empty(implode('', $valida_cadastro))){
		if($valores_cadastro['tipo'] == "Medico"){
			$medicoDAO->adicionaMedico($usuario);
		}else{
			$pacienteDAO->adicionaPaciente($usuario);			
		}
		
		header("location: login.php");
	}else{	
		$classe_form = array();
		$classe_span = array();
		
		foreach($valida_cadastro as $indice => $valor){
			if($valor != NULL){
				$classe_form[$indice] = "has-error has-feedback";
				$classe_span[$indice] = "glyphicon glyphicon-remove form-control-feedback";
			}
		}
		session_start();
		
		$_SESSION['classe_form'] = $classe_form;
		$_SESSION['classe_span'] = $classe_span;
		$_SESSION['valores_cadastro'] = $valores_cadastro;
		$_SESSION['valida_cadastro'] = $valida_cadastro;
		
		header("location: cadastro.php");
	}
}
?>