<?php

require_once "classes.php";

function vazio($valor){
    if (empty($valor)){
        $vazio = "Este campo deve ser preenchido";
    }else{
        $vazio = NULL; //É vazio
    }
    
    return $vazio;
}

function alfabetico($valor){
    if (!preg_match("/^[a-zA-Z]+$/",$valor) and ($valor != NULL)){
        $alfabetico = "Este campo pode conter apenas letras";
    }else{
        $alfabetico = NULL; //É alfabetico
    
    }
    return $alfabetico;
}

function numerico($valor){
    if (!is_numeric($valor) and ($valor != NULL)){
        $numerico = "Este campo pode conter apenas números";
    }else{
        $numerico = NULL; //É numérico
    }
    
    return $numerico;
}

function espaco($valor){
    if (preg_match("[ ]",$valor)){
        $espaco = "Este campo não pode conter espaço";
    }else{
        $espaco = NULL; //Não tem espaço.
    }
    
    return $espaco;
}

function validaData($data){
	$ano_atual = date("Y");
	$mes_atual = date("m");
	$dia_atual = date("d");
	
	$data_nascimento = explode('-', $data);
	
	$dia_nascimento = $data_nascimento[2];
	$mes_nascimento = $data_nascimento[1];
	$ano_nascimento = $data_nascimento[0];
	
	if ($ano_nascimento > $ano_atual){
		$data = "Data de nascimento inválida";
		
	}else if ($mes_nascimento > $mes_atual and $ano_nascimento==$ano_atual){
		$data = "Data de nascimento inválida";
		
	}else if ($dia_nascimento > $dia_atual and $mes_nascimento==$mes_atual and $ano_nascimento==$ano_atual){
		$data = "Data de nascimento inválida";
	
	}else{
		$data = NULL; //Data de nascimento válida
	}
	
	return $data;
}

function validaUsername($username){
	$bd = new BD();
	$usuarioDAO = new UsuarioDAO($bd);
	$id = $usuarioDAO->buscaUsuario($username);
	
	if($id > 0){
		$valida_username = "Este nome de usuário já está em uso";
	}else{
		$valida_username = NULL; //Username não existe
	}
	
	return $valida_username;
}

function confirmaSenha($senha, $confirmacao_senha){
    if ($confirmacao_senha != $senha){
        $confirma_senha = "Senhas não correspondem";
    }else{
        $confirma_senha = NULL; //Senhas iguais
    }
    
    return $confirma_senha;
}

function validaEmail($email){
	if((!substr_count($email, '@') or !substr_count(substr($email, -4), ".")) and ($email != NULL)){
		$valida_email = "Email inválido";
	}else{
		$valida_email = NULL; //Email válido
	}
	
	return $valida_email;
}



function validaCadastro($usuario, $tipo, $confirmacao_senha){
    $username = $usuario->getUsername();
    $senha = $usuario->getSenha();
    $email = $usuario->getEmail();
    $nome = $usuario->getNome();
	$sobrenome = $usuario->getSobrenome();
    $telefone = $usuario->getTelefone();
	$data_nascimento = $usuario->getDataNascimento();

	
    //Validacao do username     
    $valida_cadastro['username'] = vazio($username);
	$valida_cadastro['username'] .= espaco($username);
	$valida_cadastro['username'] .= validaUsername($username);
	
	//Validacao da senha
	$valida_cadastro['senha'] = vazio($senha);
	$valida_cadastro['senha'] .= confirmaSenha($senha, $confirmacao_senha);
	
	//Validacao do email
	$valida_cadastro['email'] = vazio($email);
	$valida_cadastro['email'] .= espaco($email);
	$valida_cadastro['email'] .= validaEmail($email);

	//Validacao do nome
	$valida_cadastro['nome'] = vazio($nome);
	$valida_cadastro['nome'] .= alfabetico($nome);
	
	//Validacao do sobrenome
	$valida_cadastro['sobrenome'] = vazio($sobrenome);
	$valida_cadastro['sobrenome'] .= alfabetico($sobrenome);
	
	//Validacao do telefone
	$valida_cadastro['telefone'] = vazio($telefone);
	$valida_cadastro['telefone'] .= numerico($telefone);
	
	//Validacao da data
	$valida_cadastro['data_nascimento'] = vazio($data_nascimento);
	$valida_cadastro['data_nascimento'] .= validaData($data_nascimento);
	
	//Validacao medico/funcionario
	$valida_cadastro['especialidade'] = NULL;
	$valida_cadastro['crm'] = NULL;
	$valida_cadastro['endereco_comercial'] = NULL;
	
	$valida_cadastro['endereco_residencial'] = NULL;
	
	if($tipo == "Medico"){
		$especialidade = $usuario->getEspecialidade();
		$crm = $usuario->getCrm();		
		$endereco_comercial = $usuario->getEnderecoComercial();

		
		//Validacao da especialidade
		$valida_cadastro['especialidade'] = vazio($especialidade);
		$valida_cadastro['especialidade'] .= alfabetico($especialidade);
		
		//Validacao do CRM
		$valida_cadastro['crm'] = vazio($crm);
		$valida_cadastro['crm'] .= numerico($crm);
		
		//Validacao do endereco comercial
		$valida_cadastro['endereco_comercial'] = vazio($endereco_comercial);
	}else{
		$cod = $usuario->getCod();
		
		//Validacao do codigo de matricula
		$valida_cadastro['cod'] = vazio($cod);
	}
	
	return $valida_cadastro;
	
}

function validaLogin($username, $senha){
    $bd = new BD();
    $usuarioDAO = new UsuarioDAO($bd);
    $busca_usuario = $usuarioDAO->buscaUsuario($username);
    
    if($busca_usuario){
        $id = $busca_usuario;
        $valida_senha = $usuarioDAO->validaSenha($id, $senha);
        
        if($valida_senha){
            $valida_login = NULL;
        }else{
            $valida_login = "Senha inválida";
        }
    }else{
        $valida_login = "Usuário inválido";
    }
    return $valida_login;
}


function mostraMes($mes){
    switch($mes){
        case 1: 
            return "Janeiro";
            break;
        case 2:
            return "Fevereiro";
            break;
        case 3:
            return "Março";
            break;
        case 4:
            return "Abril";
            break;
        case 5: 
            return "Maio";
            break;
        case 6:
            return "Junho";
            break;
        case 7:
            return "Julho";
            break;
        case 8:
            return "Agosto";
            break;
        case 9:
            return "Setembro";
            break;
        case 10:
            return "Outubro";
            break;
        case 11:
            return "Novembro";
            break;
        case 12:
            return "Dezembro";
            break;
    }
}

function validaEvento($evento){
	$horario = $evento->getHorario();
	$descricao = $evento->getDescricao();
	$tipo = $evento->getTipo();
	
	$erro = "";
	
	if(empty($horario)){
		$erro .= "Selecione o horário do evento <br/>";
	}
	
	if(empty($descricao)){
		$erro .= "Preencha a descrição do evento <br/>";
	}
	
	if($tipo == 0){
		$erro .=  "Selecione o tipo do evento <br/>";
	}
	
	return $erro;
}

function validaRegistro($registro){
	$nome = $registro->getNome();
	$horario = $registro->getHorario();
	
	$erro = "";
	
	if(empty($nome)){
		$erro .= "Preencha o nome do registro <br/>";
	}
	
	if(empty($horario)){
		$erro .= "Preencha o horário do registro <br/>";
	}
	
	return $erro;
}

function validaProntuario($valores){
	$modulos = explode(";", $valores);
	$campos = explode(":", $modulos[0]);
	$campo = explode("=", $campos[1]);
	$nome = $campo[1];
	
	if(empty($nome)){
		$erro = "Insira o nome do paciente";
		return $erro;
	}else{
		return NULL;
	}
}

function mostraTipoRegistro($tipo){
	switch($tipo){
		case "refeicao":
			return "Alimentação";
			break;
		case "atividade":
			return "Atividades";
			break;
		case "sintoma":
			return "Sintomas";
			break;
		case "medicacao":
			return "Medicação";
			break;
	}
}


