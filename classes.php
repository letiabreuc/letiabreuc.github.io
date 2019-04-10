<?php

class BD{
	const HOST = "localhost";
	const USER = "root";
	const SENHA = "";
	const BANCO = "sistema";
	
	private $mysqli;
	
	public function __construct(){
		$this->mysqli = new mysqli(self::HOST, self::USER, self::SENHA, self::BANCO);
        $this->mysqli->set_charset("utf8");
		if($this->mysqli->connect_errno){
			echo "Falha na conexão: " . $this->mysqli->connect_error;
		}
	}
	
	public function getConexao(){
		return $this->mysqli;
	}
	
	public function retornaLinhasAfetadas(){
		return $this->mysqli->affected_rows;
	}
	
	public function executaComando($comando){
		$result = $this->mysqli->query($comando);
		return $result;
	}
	
	public function executaAtualizacao($comando){
		$success = $this->mysqli->query($comando);
		if(!$success){
			echo "Erro ao executar atualização: " . $this->mysqli->error;
		}
		return $success;
	}
	
	public function executaSelecao($comando){
		$result = $this->mysqli->query($comando);
		if(!$result){
			echo "Erro ao executar seleção: " . $this->mysqli->error;
		}
		return $result;
	}
	
	public function preparaStatement($comando){
		$statement = $this->mysqli->prepare($comando);
		if(!$statement){
			echo "Erro ao preparar statement: ". $this->mysqli->error;
		}
		return $statement;
	}
	
	public function fecha(){
		$this->mysqli->close();
	}
}


class Usuario {
	private $id;
	private $username;
	private $senha;
	private $email;
	private $nome;
	private $data_nascimento;
	private $telefone;
	private $adm;
	
	public function __construct($id, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm){
		$this->id = $id;
		$this->username = $username;
		$this->senha = $senha;
		$this->email = $email;
		$this->nome = $nome;
		$this->sobrenome = $sobrenome;
		$this->data_nascimento = $data_nascimento;
		$this->telefone = $telefone;
		$this->adm = $adm;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getUsername(){
		return $this->username;
	}

	public function getSenha(){
		return $this->senha;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getNome(){
		return $this->nome;
	}
	
	public function getSobrenome(){
		return $this->sobrenome;
	}

	public function getDataNascimento(){
		return $this->data_nascimento;
	}

	public function getTelefone(){
		return $this->telefone;
	}

	public function getAdm(){
		return $this->adm;
	}
}

class UsuarioDAO {
	private $banco;

	public function __construct($bd){
		$this->banco = $bd;
	}
	
	public function buscaUsuario($username){
	    $comando = "SELECT id FROM usuario WHERE username = ?";
	    $stmt = $this->banco->preparaStatement($comando);
	    $stmt->bind_param('s', $username);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $dados = $result->fetch_array();
	    
	    $id = $dados['id'];
	    
	    return $id;
	}
	
	public function validaSenha($id, $senha){
	    $comando = "SELECT senha FROM usuario WHERE id = ?";
	    $stmt = $this->banco->preparaStatement($comando);
	    $stmt->bind_param('i', $id); 
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $dados =  $result->fetch_array();
	    
	    $senha_banco = $dados['senha'];
		$senha_hash = hash("sha256", $senha);
	    
	    if($senha_hash == $senha_banco){
	        $validaSenha = true;
	    }else{
	        $validaSenha = false;
	    }
	    
	    return $validaSenha;
	}
	
	public function isAdm($id){
	    $comando = "SELECT adm FROM usuario WHERE id = ?";
	    $stmt = $this->banco->preparaStatement($comando);
	    $stmt->bind_param('i', $id);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $dados = $result->fetch_array();
	    
	    $isAdm = $dados['adm'];
	    
	    return $isAdm;
	}
	
	public function isMedico($id){
        $comando = "SELECT * FROM medico WHERE id_usuario = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
		$result = $stmt->get_result();
		$dados = $result->fetch_array();
        
        $is_medico = $dados['id_usuario'];
        
		return $is_medico;
    }
    
    public function retornaUsuario($id){
        $comando = "SELECT * FROM usuario WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $username = $dados['username'];
        $senha = $dados['senha'];
        $email = $dados['email'];
        $nome_completo = explode(" ", $dados['nome']);
		$nome = $nome_completo[0];
		$sobrenome = $nome_completo[1];
        $data_nascimento = $dados['data_nascimento'];
        $telefone = $dados['telefone'];
        $adm = $dados['adm'];
        
        $usuario = new Usuario($id, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm);
		
		return $usuario;
    }

}

class Medico extends Usuario {
    private $especialidade;
    private $endereco_comercial;
    private $crm;
    
    public function  __construct($id, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm, $especialidade, $crm, $endereco_comercial){
        parent:: __construct($id, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm);
        $this->especialidade = $especialidade;
		$this->crm = $crm;
        $this->endereco_comercial = $endereco_comercial;        
    }
    
    public function getEspecialidade(){
        return $this->especialidade;
    }
    
	public function getCrm(){
        return $this->crm;
    }
	
    public function getEnderecoComercial(){
        return $this->endereco_comercial;
    }     
}

class MedicoDAO {
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaMedico($medico){
        $id = $medico->getId();
        $username = $medico->getUsername();
        $senha = $medico->getSenha();
        $email = $medico->getEmail();
        $nome = $medico->getNome();
        $sobrenome = $medico->getSobrenome();
	    $nome_completo = $nome . " " . $sobrenome;
        $data_nascimento = $medico->getDataNascimento();
        $telefone = $medico->getTelefone();
        $adm = $medico->getAdm();
        $especialidade = $medico->getEspecialidade();
        $crm = $medico->getCrm();
        $endereco_comercial = $medico->getEnderecoComercial();
        
        $comando = "INSERT INTO usuario VALUES(?, ?, SHA2(?, 256), ?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('issssssi', $id, $username, $senha, $email, $nome_completo, $data_nascimento, $telefone, $adm);
        $result = $stmt->execute();
        
        if($result){
            $comando = "INSERT INTO medico VALUES(?, ?, ?, (SELECT MAX(id) FROM usuario))";
            $stmt = $this->banco->preparaStatement($comando);
            $stmt->bind_param('ssi', $especialidade, $crm, $endereco_comercial);
            $result = $stmt->execute();
            
            if($result){
                $adicionaMedico = true;
            }else{
                $adicionaMedico = false;
            }
        }else{
            $adicionaMedico = false;
        }
        
        return $adicionaMedico;
    }
    
    public function removeMedico($id){
        $comando = "DELETE FROM medico WHERE id_usuario = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $removeMedico = $result;
        
        return $removeMedico;
    }
    
    public function retornaMedico($id){
        $comando = "SELECT * FROM medico JOIN usuario WHERE id_usuario = ? AND id = id_usuario";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id_usuario = $dados['id_usuario'];
        $username = $dados['username'];
        $senha = $dados['senha'];
        $email = $dados['email'];
        $nome_completo = explode(" ", $dados['nome']);
		$nome = $nome_completo[0];
		$sobrenome = $nome_completo[1];
        $data_nascimento = $dados['data_nascimento'];
        $telefone = $dados['telefone'];
        $adm = $dados['adm'];
        $especialidade = $dados['especialidade'];
        $endereco_comercial = $dados['endereco_comercial'];
        $crm = $dados['crm'];
        
        $medico = new Medico($id_usuario, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm, $especialidade, $crm, $endereco_comercial);
        
        return $medico;
    }
}

class Paciente extends Usuario {
    private $cod;
    
    public function __construct($id, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm, $cod){
        parent:: __construct($id, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm);
        $this->cod = $cod;    
	}
    
    public function getCod(){
        return $this->cod;
    }

    
}


class PacienteDAO {
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaPaciente($paciente){
        $id = $paciente->getId();
        $username = $paciente->getUsername();
        $senha = $paciente->getSenha();
        $email = $paciente->getEmail();
        $nome = $paciente->getNome();
		$sobrenome = $paciente->getSobrenome();
		$nome_completo = $nome . " " . $sobrenome;
        $data_nascimento = $paciente->getDataNascimento();
        $telefone = $paciente->getTelefone();
        $adm = $paciente->getAdm();
        $cod = $paciente->getCod();
        
        $comando = "INSERT INTO usuario VALUES(?, ?, SHA2(?, 256), ?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('issssssi', $id, $username, $senha, $email, $nome_completo, $data_nascimento, $telefone, $adm);
        $result = $stmt->execute();
        
        if($result){
            $comando = "INSERT INTO paciente VALUES(?, (SELECT MAX(id) FROM usuario))";
            $stmt = $this->banco->preparaStatement($comando);
            $stmt->bind_param('s', $cod);
            $result = $stmt->execute();
            
            if($result){
                $adicionaPaciente = true;
            }else{
                $adicionaPaciente = false;
            }
        }else{
            $adicionaPaciente = false;
        }
        
        return $adicionaPaciente;
    }
    
    public function removePaciente($id){
        $comando = "DELETE FROM paciente WHERE id_usuario = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $removePaciente = $result;
        
        return $removePaciente;
    }
    
    public function retornaPaciente($id){
        $comando = "SELECT * FROM paciente JOIN usuario WHERE id_usuario = ? AND id = id_usuario";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id_usuario = $dados['id_usuario'];
        $username = $dados['username'];
        $senha = $dados['senha'];
        $email = $dados['email'];
        $nome_completo = explode(" ", $dados['nome']);
		$nome = $nome_completo[0];
		$sobrenome = $nome_completo[1];
        $data_nascimento = $dados['data_nascimento'];
        $telefone = $dados['telefone'];
        $adm = $dados['adm'];
        $cod = $dados['cod'];
        
        $paciente = new Paciente($id_usuario, $username, $senha, $email, $nome, $sobrenome, $data_nascimento, $telefone, $adm, $cod);
        
        return $paciente;
    }
}

class Prontuario{
    private $id;
    private $paciente_id;
    private $medico_id;
    private $protocolo_id;
    private $valores;

    public function __construct($id, $paciente, $medico, $protocolo, $val) {
        $this->id = $id;
        $this->funionario_id = $paciente;
        $this->medico_id = $medico;
        $this->protocolo_id = $protocolo;
        $this->valores = $val;
    }
    
    public function getId(){
        return $this->id;
	}
    
    public function getPacienteId(){
		return $this->paciente_id;
	}
    
    public function getMedicoId(){
        return $this->medico_id;
    }

    public function getProtocoloId(){
        return $this->protocolo_id;
    }

    public function getValores(){
        return $this->valores;
    }

    public function retornaNomePaciente(){
        $lista_modulos = explode(";", $this->valores);
        foreach($lista_modulos as $modulo){
            $lista_dados = explode(":", $modulo);
            if($lista_dados[0] == 1){                
                foreach($lista_dados as $dados){            
                    $valor = explode("=", $dados);
                    if($valor[0] == "Nome"){
                        return $valor[1];
                    }
                }
            }
        }
    }
}


class ProntuarioDAO{
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }

    public function adicionaProntuario($prontuario){
        $id = $prontuario->getId();
        $paciente_id = $prontuario->getPacienteId();
        $medico_id = $prontuario->getMedicoId();
        $protocolo_id = $prontuario->getProtocoloId();
        $valores = $prontuario->getValores();
        
        $comando = "INSERT INTO prontuario VALUES(?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('iiiis', $id, $paciente_id, $medico_id, $protocolo_id, $valores);
        $result = $stmt->execute();
        
        $adiciona_prontuario = $result;
        
        return $adiciona_prontuario;
    }

    public function atualizaProntuario($id, $valores){
        $comando = "UPDATE prontuario SET valores = ? WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param("si", $valores, $id);
        $result = $stmt->execute();

        $atualiza_prontuario = $result;

        return $atualiza_prontuario;
    }
    
    public function removeProntuario($id){
        $comando = "DELETE FROM prontuario WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_prontuario = $result;
        
        return $remove_prontuario;
    }
    
    public function retornaProntuario($id){
        $comando = "SELECT * FROM prontuario WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $paciente_id = $dados['id_paciente'];
        $medico_id = $dados['id_medico'];
        $protocolo_id = $dados['id_protocolo'];
        $valores = $dados['valores'];
        
        $prontuario = new Prontuario($id, $paciente_id, $medico_id, $protocolo_id, $valores);
        
        return $prontuario;
    }

    public function retornaNovoProntuarioId(){
        $comando = "SELECT MAX(id) as id FROM prontuario";
        $result = $this->banco->executaSelecao($comando);
        $dados = $result->fetch_array();
        $id = $dados['id'];

        return $id;
    }

    public function leProntuario($medico_id){
        $comando = "SELECT * FROM prontuario WHERE id_medico = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param("i", $medico_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $lista_prontuarios = array();

        while($dados = $result->fetch_array()){
            $id = $dados['id'];
            $paciente_id = $dados['id_paciente'];
            $medico_id = $dados['id_medico'];
            $protocolo_id = $dados['id_protocolo'];
            $valores = $dados['valores'];
            
            $prontuario = new Prontuario($id, $paciente_id, $medico_id, $protocolo_id, $valores);
            $lista_prontuarios[] = $prontuario;
        }

        return $lista_prontuarios;
    }
}


class Consulta{
    private $id;
    private $data;
    private $dados;
    private $protocolo_id;
    private $prontuario_id;
    
    public function __construct ($id, $data, $dados, $protocolo, $prontuario){
        $this->id = $id;
        $this->data = $data;
        $this->dados = $dados;
        $this->protocolo_id = $protocolo;
        $this->prontuario_id = $prontuario;
	}
    
    public function getId(){
        return $this->id;
    }
    
    public function getData(){
        return $this->data;
	}
    
    public function getDados(){
        return $this->dados;
    }    
    
    public function getProtocoloId(){
        return $this->protocolo_id;
    }
    
    public function getProntuarioId(){
        return $this->prontuario_id;
    }
}



class ConsultaDAO {
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaConsulta($consulta){
        $id = $consulta->getId();
        $data = $consulta->getData();
        $dados = $consulta->getDados();
        $protocolo_id = $consulta->getProtocoloId();
        $prontuario_id = $consulta->getProntuarioId();
        
        $comando = "INSER INTO consulta VALUES(?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isssi', $id, $data, $dados, $protocolo_id, $prontuario_id);
        $result = $stmt->execute();
        
        $adiciona_consulta = $result;
        
        return $adiciona_consulta;
    }
    
    public function removeConsulta($id){
        $comando = "DELETE FROM consulta WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_consulta = $result;
        
        return $remove_consulta();
    }
    
    public function retornaConsulta($id){
        $comando = "SELECT * FROM consulta WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $data = $dados['data'];
        $dados = $dados['dados'];
        $protocolo_id = $dados['id_protocolo'];
        $prontuario_id = $dados['id_prontuario'];
        
        $consulta = new Consulta($id, $data, $dados, $protocolo_id, $prontuario_id);
        
        return $consulta;
    }
}
class Exame{
    private $id;
    private $nome;
    private $data;
    private $laudo;
    private $prontuario_id;
    
    public function __construct($id, $nome, $data, $laudo, $prontuario){
        $this->id = $id;
        $this->nome = $nome;
        $this->data = $data;
        $this->laudo = $laudo;
        $this->prontuario_id = $prontuario;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getNome(){
        return $this->nome;
    }
    
    public function getData(){
        return $this->data;
    }
    
    public function getLaudo(){
        return $this->laudo;
    }
    
    public function getProntuarioId(){
        return $this->prontuario_id;
    }
}



class ExameDAO {
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaExame($exame){
        $id = $exame->getId();
        $nome = $exame->getNome();
        $data = $exame->getData();
        $laudo = $exame->getLaudo();
        $prontuario_id = $exame->getProntuarioId();
        
        $comando = "INSERT INTO exame VALUES(?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isssi', $id, $nome, $data, $laudo, $prontuario_id);
        $result = $stmt->execute();
        
        $adiciona_exame = $result;
        
        return $adiciona_exame;
    }
    
    public function removeExame($id){
        $comando = "DELETE FROM exame WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt = bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_exame = $result;
        
        return $remove_exame;
    }
    
    public function retornaExame($id){
        $comando = "SELECT * FROM exame WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $nome = $dados['nome'];
        $data = $dados['data'];
        $laudo = $dados['laudo'];
        $prontuario_id = $dados['id_prontuario'];
        
        $exame = new Exame($id, $nome, $data, $laudo, $prontuario_id);
        
        return $exame;
    }
}

class Modulo{
    private $id;
    private $nome;
    private $campos;

    public function __construct($id, $nome, $campos){
        $this->id = $id;
        $this->nome = $nome;
        $this->campos = $campos;
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function getCampos(){
        return $this->campos;
    }

    public function mostraModulo($pagina = NULL){
        $campos = explode(";", $this->campos);
		echo 
            '<div class="panel panel-success" id="modulo' . $this->id . '">
				<div class="panel-heading text-center">';
        if(($this->id > 3) and ($pagina == "protocolo")){
            echo '<button class="close"><span class="glyphicon glyphicon-remove" title="Remover Módulo" onclick="removeModulo(' . $this->id . ')"></span></button>';
        }
        
        echo '    <h4>' . $this->nome . '</h4>                
				</div>
				<div class="panel-body">';
        foreach($campos as $campo){
            $dados = explode(":", $campo);
            if ($dados[1]=="textarea"){
                echo '
                <div class="form-group">
                    <label>' . $dados[0] . '</label>
                    <textarea class="form-control" name="' . $this->id . '-' . $dados[0] . '" rows="5"></textarea>
                </div>';
                
            }else if ($dados[1]=="radio"){
                $opcoes = explode("-", $dados[2]);
                echo '<label>' . $dados[0] . '</label>';
				echo '<div class="radio">';
                foreach($opcoes as $opcao){
                    echo '
                        <label class="radio-inline"><input type="radio" name="' . $this->id . '-' . $dados[0] . '" value="' . $opcao . '"> '. $opcao . ' </label>
                    ';
                }
				echo '</div>';
            }else if($dados[1]=="select"){
                $opcoes = explode("-", $dados[2]);
                echo '
                <div class="form-group">
                    <label>' . $dados[0] . '</label>
                    <select class="form-control" name="' . $this->id . '-' . $dados[0] . '"> ';
                    foreach($opcoes as $opcao){
                        echo '<option value="' . $opcao . '">' . $opcao . '</option>';
                    }
                    echo '
                    </select>
                </div>';
            }else{
                echo '
                <div class="form-group">
                    <label>' . $dados[0] . '</label>
                    <input class="form-control" type="' . $dados[1] . '" name="' . $this->id . '-' . $dados[0] . '">
                </div>';
            }
        }
		echo '	</div>
		</div>';
    }

    public function mostraModuloProntuario($lista_valores){
        $campos = explode(";", $this->campos);
        echo 
            '<div class="panel panel-success" id="modulo' . $this->id . '">
                <div class="panel-heading text-center">
				<h4>' . $this->nome . '</h4>                
                </div>
                <div class="panel-body">';
        foreach($campos as $chave => $campo){
            $dados = explode(":", $campo);  
            foreach($lista_valores as $valores){
                $valor = explode("=", $valores);
                if($dados[0] == $valor[0]){
                    break;
                }
            }          
            if ($dados[1]=="textarea"){ 
                echo '
                <div class="form-group">
                    <label>' . $dados[0] . '</label>
                    <textarea class="form-control" name="' . $this->id . '-' . $dados[0] . '" rows="5">' . $valor[1] . '</textarea>
                </div>';
                
            }else if ($dados[1]=="radio"){
                $opcoes = explode("-", $dados[2]);
                echo '<label>' . $dados[0] . '</label>';
                echo '<div class="radio">';
                foreach($opcoes as $opcao){
                    if($opcao == $valor[1]){
                        $check = "checked";
                    }else{
                        $check = "";
                    }
                    echo '
                        <label class="radio-inline"><input type="radio" name="' . $this->id . '-' . $dados[0] . '" value="' . $opcao . '"' . $check . '> '. $opcao . ' </label>
                    ';
                }
                echo '</div>';
            }else if($dados[1]=="select"){
                $opcoes = explode("-", $dados[2]);
                echo '
                <div class="form-group">
                    <label>' . $dados[0] . '</label>
                    <select class="form-control" name="' . $this->id . '-' . $dados[0] . '"> ';
                    foreach($opcoes as $opcao){
                        if($opcao == $valor[1]){
                            $select = "selected";
                        }else{
                            $select = "";
                        }
                        echo '<option value="' . $opcao . '"' . $select . '>' . $opcao . '</option>';
                    }
                    echo '
                    </select>
                </div>';
            }else{
                echo '
                <div class="form-group">
                    <label>' . $dados[0] . '</label>
                    <input class="form-control" type="' . $dados[1] . '" name="' . $this->id . '-' . $dados[0] . '" value="' . $valor[1] . '">
                </div>';
            }
        }
        echo '  </div>
        </div>';
    }
}

class ModuloDAO{
    private $bd;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaModulo($modulo){
        $id = $modulo->getId();
        $nome = $modulo->getNome();
        $campos = $modulo->getCampos();
        
        $comando = "INSERT INTO modulo VALUES(?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isi', $id, $nome, $campos);
        $result = $stmt->execute();
        
        $adiciona_modulo = $result;
        
        return $adiciona_modulo;
    }
    
    public function atualizaModulo($id, $nome, $campos){
        $comando = "UPDATE modulo SET nome = ?, campos = ? WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('ssi', $nome, $campos, $id);
        $result = $stmt->execute();
        
        $atualiza_modulo = $result;
        
        return $atualiza_modulo;
    }
    
    public function removeModulo($id){
        $comando = "DELETE FROM modulo WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_modulo = $result;
        
        return $remove_modulo;
    }
    
    public function retornaModulo($id){
        $comando = "SELECT * FROM modulo WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $nome = $dados['nome'];
        $campos = $dados['campos'];
        
        $modulo = new Modulo($id, $nome, $campos);
        
        return $modulo;
    }
    
    public function leModulo(){
        $comando = "SELECT * FROM modulo WHERE id > 3";
        $result = $this->banco->executaSelecao($comando);
        $lista_modulos = array();
        
        while($dados = $result->fetch_array()){
            $id = $dados['id'];
            $nome = $dados['nome'];
            $campos = $dados['campos'];
            
            $modulo = new Modulo($id, $nome, $campos);
            
            $lista_modulos[] = $modulo;
        }
        
        return $lista_modulos;
    }
}

class Protocolo{
    private $id;
    private $nome;
    private $medico_id;
    
    public function __construct($id,$nome,$medico){
        $this->id = $id;
        $this->nome = $nome;
        $this->medico_id = $medico;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getNome(){
        return $this->nome;
    }
    
    public function getMedicoId(){
        return $this->medico_id;
    }
}



class ProtocoloDAO {
    private $bd;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaProtocolo($protocolo){
        $id = $protocolo->getId();
        $nome = $protocolo->getNome();
        $medico_id = $protocolo->getMedicoId();
        
        $comando = "INSERT INTO protocolo VALUES(?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isi', $id, $nome, $medico_id);
        $result = $stmt->execute();
        
        $adiciona_protocolo = $result;
        
        return $adiciona_protocolo;
    }
	
	public function atualizaProtocolo($id, $nome){
		$comando = "UPDATE protocolo SET nome = ? WHERE id = ?";
		$stmt = $this->banco->preparaStatement($comando);
		$stmt->bind_param('si', $nome, $id);
		$result = $stmt->execute();
		
		$atualiza_protocolo = $result;
		
		return $atualiza_protocolo;
	}
    
    public function removeProtocolo($id){
        $comando = "DELETE FROM protocolo WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_protocolo = $result;
        
        return $remove_protocolo;
    }
    
    public function retornaProtocolo($id){
        $comando = "SELECT * FROM protocolo WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $nome = $dados['nome'];
        $medico_id = $dados['id_medico'];
        
        $protocolo = new Protocolo($id, $nome, $medico_id);
        
        return $protocolo;
    }
	
	public function leProtocolo($medico){
		$comando = "SELECT * FROM protocolo WHERE id_medico = ? AND id > 1";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param("i", $medico);
        $stmt->execute();
        $result = $stmt->get_result();
		$lista_protocolos = array();
		
		while($dados = $result->fetch_array()){
			$id = $dados['id'];
			$nome = $dados['nome'];
			$medico_id = $dados['id_medico'];
			
			$protocolo = new Protocolo($id, $nome, $medico_id);
			
			$lista_protocolos[] = $protocolo;
		}
		
		return $lista_protocolos;
	}
	
}

class ModuloProtocolo{
    private $modulo_id;
    private $protocolo_id;

    public function __construct($modulo, $protocolo){
        $this->modulo_id = $modulo;
        $this->protocolo_id = $protocolo;
    }

    public function getModuloId(){
        return $this->modulo_id;
    }

    public function getProtocoloId(){
        return $this->protocolo_id;
    }
}

class ModuloProtocoloDAO{
    private $banco;

    public function __construct($bd){
        $this->banco = $bd;
    }

    public function adicionaModuloProtocolo($moduloProtocolo){
        $modulo_id = $moduloProtocolo->getModuloId();
        $protocolo_id = $moduloProtocolo->getProtocoloId();      
		
		if($protocolo_id == NULL){
			$comando = "INSERT INTO modulo_protocolo VALUES(?, (SELECT MAX(id) FROM protocolo))";
			 $stmt = $this->banco->preparaStatement($comando);
			$stmt->bind_param("i", $modulo_id);
		}else{
			$comando = "INSERT INTO modulo_protocolo VALUES(?, ?)";
			$stmt = $this->banco->preparaStatement($comando);
			$stmt->bind_param("ii", $modulo_id, $protocolo_id);
		}
        $result = $stmt->execute();

        $adiciona_moduloProtocolo = $result;

        return $adiciona_moduloProtocolo;
    }

    public function removeModuloProtocolo($protocolo){
        $comando = "DELETE FROM modulo_protocolo WHERE id_protocolo = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $protocolo);
        $result = $stmt->execute();
        
        $remove_moduloProtocolo = $result;
        
        return $remove_moduloProtocolo;
    }
    
    public function retornaModuloProtocolo($protocolo){
		$comando = "SELECT * FROM modulo_protocolo WHERE id_protocolo = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $protocolo);
        $stmt->execute();
        $result = $stmt->get_result();

        $lista_moduloProtocolo = array();

        while($dados = $result->fetch_array()){
            $modulo_id = $dados['id_modulo'];
            $protocolo_id = $dados['id_protocolo'];

            $moduloProtocolo = new ModuloProtocolo($modulo_id, $protocolo_id);

            $lista_moduloProtocolo[] = $moduloProtocolo;
        }     
        
        return $lista_moduloProtocolo;
    }
	
}

class Registro{
    private $id;
	private $nome;
    private $tipo;
	private $data;
    private $horario;
    private $descricao;
    private $paciente_id;
    
    public function __construct($id, $nome, $tipo, $data, $horario, $descricao, $paciente_id){
        $this->id = $id;
		$this->nome = $nome;
        $this->tipo = $tipo;
		$this->data = $data;
        $this->horario = $horario;
        $this->descricao = $descricao;
        $this->paciente_id = $paciente_id;
    }
    
    public function getId(){
        return $this->id;
    }
    
	public function getNome(){
		return $this->nome;
	}
	
    public function getTipo(){
        return $this->tipo;
    }
    
	public function getData(){
		return $this->data;
	}
	
    public function getHorario(){
        return $this->horario;
    }
    
    public function getDescricao(){
        return $this->descricao;
    }
    
    public function getPacienteId(){
        return $this->paciente_id;
    }
}



class RegistroDAO {
    private $bd;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaRegistro($registro){
        $id = $registro->getId();
		$nome = $registro->getNome();
        $tipo = $registro->getTipo();
		$data = $registro->getData();
        $horario = $registro->getHorario();
        $descricao = $registro->getDescricao();
        $paciente_id = $registro->getPacienteId();
        
        $comando = "INSERT INTO registro VALUES(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isssssi', $id, $nome, $tipo, $data, $horario, $descricao, $paciente_id);
        $result = $stmt->execute();
        
        $adiciona_registro = $result;
        
        return $adiciona_registro;
    }
	
	public function atualizaRegistro($id, $nome, $horario, $descricao){
		$comando = "UPDATE registro SET nome = ?, horario = ?, descricao = ? WHERE id = ?";
		$stmt = $this->banco->preparaStatement($comando);
		$stmt->bind_param("sssi", $nome, $horario, $descricao, $id);
		$result = $stmt->execute();
		
		$atualizaRegistro = $result;
		
		return $atualizaRegistro;
	}
    
    public function removeRegistro($id){
        $comando = "DELETE FROM registro WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_registro = $result;
        
        return $remove_registro;
    }
	
	public function leRegistro($data, $id_usuario){
		$comando = "SELECT * FROM registro WHERE data <= ? AND id_paciente = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('si', $data, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
		
		$lista_registros = array();
		
		while($dados = $result->fetch_array()){
			$id = $dados['id'];
			$nome = $dados['nome'];
			$tipo = $dados['tipo'];
			$data = $dados['data'];
			$horario = $dados['horario'];
			$descricao = $dados['descricao'];
			$paciente_id = $dados['id_paciente'];
			
			$registro = new Registro($id, $nome, $tipo, $data, $horario, $descricao, $paciente_id);
			
			$lista_registros[] = $registro;
		}    
        
        return $lista_registros;
	}
    
    public function retornaRegistro($id){
        $comando = "SELECT * FROM registro WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
		$nome = $dados['nome'];
        $tipo = $dados['tipo'];
		$data = $dados['data'];
        $horario = $dados['horario'];
        $descricao = $dados['descricao'];
        $paciente_id = $dados['id_paciente'];
        
        $registro = new Registro($id, $nome, $tipo, $data, $horario, $descricao, $paciente_id);
        
        return $registro;
    }
	
	public function retornaRegistrosTipo($tipo, $data, $id_usuario){
        $comando = "SELECT * FROM registro WHERE tipo = ? AND data = ? AND id_paciente = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('ssi', $tipo, $data, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
		
		$lista_registros = array();
		
		while($dados = $result->fetch_array()){
			$id = $dados['id'];
			$nome = $dados['nome'];
			$tipo = $dados['tipo'];
			$data = $dados['data'];
			$horario = $dados['horario'];
			$descricao = $dados['descricao'];
			$paciente_id = $dados['id_paciente'];
			
			$registro = new Registro($id, $nome, $tipo, $data, $horario, $descricao, $paciente_id);
			
			$lista_registros[] = $registro;
		}    
        
        return $lista_registros;
    }
}
class Medicacao{
    private $id;
    private $remedio;
    private $intervalo;
    private $dosagem;
    private $periodo;
    private $data_inicial;
    private $descricao;
    private $confirmacao;
    private $paciente_id;
    
    public function __construct($id, $remedio, $intervalo, $dosagem, $periodo, $data_inicial, $descricao, $confirmacao, $paciente){
        $this->id = $id;
        $this->remedio = $remedio;
        $this->intervalo = $intervalo;
        $this->dosagem = $dosagem;
        $this->periodo = $periodo;
        $this->data_inicial = $data_inicial;
        $this->descricao = $descricao;
        $this->confirmacao = $confirmacao;
        $this->paciente_id = $paciente;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getRemedio(){
        return $this->remedio;
    }
    
    public function getIntervalo(){
        return $this->intervalo;
    }
    
    public function getDosagem(){
        return $this->dosagem;
    }
    
    public function getPeriodo(){
        return $this->periodo;
    }
    
    public function getDataInicial(){
        return $this->data_inicial;
    }
    
    public function getDescricao(){
        return $this->descricao;
    }
    
    public function getConfirmacao(){
        return $this->confirmacao;
    }
    
    public function getPacienteId(){
        return $this->paciente_id;
    }
}



class MedicacaoDAO {
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaMedicacao($rotina){
        $id = $rotina->getId();
        $remedio = $rotina->getRemedio();
        $intervalo = $rotina->getIntervalo();
        $dosagem = $rotina->getDosagem();
        $periodo = $rotina->getPeriodo();
        $data_inicial = $rotina->getDataInicial();
        $descricao = $rotina->getDescricao();
        $confirmacao = $rotina->getConfirmacao();
        $paciente_id = $rotina->getPacienteId();
        
        $comando = "INSERT INTO medicacao VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isisissii', $id, $remedio, $intervalo, $dosagem, $periodo, $data_inicial, $descricao, $confirmacao, $paciente_id);
        $result = $stmt->execute();
        
        $adiciona_medicacao = $result;
        
        return $adiciona_medicacao;
    }
    
    public function removeMedicacao($id){
        $comando = "DELETE FROM medicacao WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_medicacao = $result;
        
        return $remove_medicacao;
    }
    
    public function retornaMedicacao($id){
        $comando = "SELECT * FROM medicacao WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $remedio = $dados['remedio'];
        $intervalo = $dados['intervalo'];
        $dosagem = $dados['dosagem'];
        $periodo = $dados['periodo'];
        $data_inicial = $dados['data_inicial'];
        $descricao = $dados['descricao'];
        $confirmacao = $dados['confirmacao'];
        $paciente_id = $dados['id_paciente'];
        
        $medicacao = new Medicacao($id, $remedio, $intervalo, $dosagem, $periodo, $data_inicial, $descricao, $confirmacao, $paciente_id);
        
        return $medicacao;
    }
}

class Doenca{
    private $id;
    private $nome;
    private $descricao;
    private $tratamento;
    
    public function __construct($id, $nome, $descricao, $tratamento){
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->tratamento = $tratamento;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getNome(){
        return $this->nome;
    }
    
    public function getDescricao(){
        return $this->descricao;
    }
    
    public function getTratamento(){
        return $this->tratamento;
    }
}





class DoencaDAO {
    
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaDoenca($doenca){
        $id = $doenca->getId();
        $nome = $doenca->getNome();
        $descricao = $doenca->getDescricao();
        $tratamento = $doenca->getTratamento();
        
        $comando = "INSERT INTO doenca VALUES(?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isss', $id, $nome, $descricao, $tratamento);
        $result = $stmt->execute();
        
        $adiciona_doenca = $result;
        
        return $adiciona_doenca;
    }
    
    public function removeDoenca($id){
        $comando = "DELETE FROM doenca WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_doenca = $result;
        
        return $remove_doenca;
    }
    
    public function retornaDoenca($id){
        $comando = "SELECT * FROM doenca WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $nome = $dados['nome'];
        $descricao = $dados['descricao'];
        $tratamento = $dados['tratamento'];
        
        $doenca = new Doenca($id, $nome, $descricao, $tratamento);
        
        return $doenca;
    }
}

class Remedio{
    private $id;
    private $nome;
    private $formula;
    private $indicacoes;
    private $contra_indicacoes;
    private $efeitos_colaterais;
    
    public function __construct($id, $nome, $formula, $indicacoes, $contra, $efeitos){
        $this->id = $id;
        $this->nome = $nome;
        $this->formula = $formula;
        $this->indicacoes = $indicacoes;
        $this->contra_indicacoes = $contra;
        $this->efeitos_colaterais = $efeitos;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getNome(){
        return $this->nome;
    }
    
    public function getFormula(){
        return $this->formula;
    }
    
    public function getIndicacoes(){
        return $this->indicacoes;
    }
    
    public function getContraIndicacoes(){
        return $this->contra_indicacoes;
    }
    
    public function getEfeitosColaterais(){
        return $this->efeitos_colaterais;
    }
}

class RemedioDAO {
    
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaRemedio($remedio){
        $id = $remedio->getId();
        $nome = $remedio->getNome();
        $formula = $remedio->getFormula();
        $indicacoes = $remedio->getIndicacoes();
        $contra_indicacoes = $remedio->getContraIndicacoes();
        $efeitos_colaterais = $remedio->getEfeitosColaterais();
        
        $comando = "INSERT INTO remedio VALUES(?, ?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('isssss', $id, $nome, $formula, $indicacoes, $contra_indicacoes, $efeitos_colaterais);
        $result = $stmt->execute();
        
        $adiciona_remedio = $result;
        
        return $adiciona_remedio;
    }
    
    public function removeRemedio($id){
        $comando = "DELETE FROM remedio WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_remedio = $result;
        
        return $remove_remedio;
    }
    
    public function retornaRemedio($id){
        $comando = "SELECT * FROM remedio WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $nome = $dados['nome'];
        $formula = $dados['formula'];
        $indicacoes = $dados['indicacoes'];
        $contra_indicacoes = $dados['contra_indicacoes'];
        $efeitos_colaterais = $dados['efeitos_colaterais'];
        
        $remedio = new Remedio($id, $nome, $formula, $indicacoes, $contra_indicacoes, $efeitos_colaterais);
        
        return $remedio;
    }
}

class Evento{
    private $id;
    private $descricao;
    private $tipo;
    private $data;
    private $horario;
    private $usuario_id;
    private $prontuario_id;
    
    public function __construct($id, $descricao, $tipo, $data, $horario, $usuario, $prontuario){
        $this->id = $id;
        $this->descricao = $descricao;
        $this->tipo = $tipo;
        $this->data = $data;
        $this->horario = $horario;
        $this->usuario_id = $usuario;
        $this->prontuario_id = $prontuario;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getDescricao(){
        return $this->descricao;
    }
    
    public function getTipo(){
        return $this->tipo;
    }
    
    public function getData(){
        return $this->data;
    }
    
    public function getHorario(){
        return $this->horario;
    }
    
    public function getUsuarioId(){
        return $this->usuario_id;
    }
    
    public function getProntuarioId(){
        return $this->prontuario_id;
    }
}

class EventoDAO {
    
    private $banco;
    
    public function __construct($bd){
        $this->banco = $bd;
    }
    
    public function adicionaEvento($evento){
        $id = $evento->getId();
        $descricao = $evento->getDescricao();
        $tipo = $evento->getTipo();
        $data = $evento->getData();
        $horario = $evento->getHorario();
        $usuario_id = $evento->getUsuarioId();
        $prontuario_id = $evento->getProntuarioId();
        
        $comando = "INSERT INTO evento VALUES(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('issssii', $id, $descricao, $tipo, $data, $horario, $usuario_id, $prontuario_id);
        $result = $stmt->execute();
        
        $adiciona_evento = $result;
        
        return $adiciona_evento;
    }
    
    public function removeEvento($id){
        $comando = "DELETE FROM evento WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        
        $remove_evento = $result;
        
        return $remove_evento;
    }
    
    public function retornaEvento($id){
        $comando = "SELECT * FROM evento WHERE id = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dados = $result->fetch_array();
        
        $id = $dados['id'];
        $descricao = $dados['descricao'];
        $tipo = $dados['tipo'];
        $data = $dados['data'];
        $horario = $dados['horario'];
        $usuario_id = $dados['id_usuario'];
        $prontuario_id = $dados['id_prontuario'];
        
        $evento = new Evento($id, $descricao, $tipo, $data, $horario, $usuario_id, $prontuario_id);
        
        return $evento;
    }

    public function retornaEventosData($data){
        $comando = "SELECT id FROM evento WHERE data = ?";
        $stmt = $this->banco->preparaStatement($comando);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result){
            $lista_evento_id = array();
        
            while($dados = $result->fetch_array()){
                $id = $dados['id'];
                $lista_evento_id[] = $id;
            }

            return $lista_evento_id;
        }else{
            return FALSE;
        }        
    }
}

$bd = new BD();
$usuarioDAO = new UsuarioDAO($bd);
$medicoDAO = new MedicoDAO($bd);
$pacienteDAO = new PacienteDAO($bd);
$prontuarioDAO = new ProntuarioDAO($bd);
$consultaDAO = new ConsultaDAO($bd);
$exameDAO = new ExameDAO($bd);
$moduloDAO = new ModuloDAO($bd);
$protocoloDAO = new ProtocoloDAO($bd);
$moduloProtocoloDAO = new ModuloProtocoloDAO($bd);
$registroDAO =  new RegistroDAO($bd);
$medicacaoDAO = new MedicacaoDAO($bd);
$eventoDAO = new EventoDAO($bd);
$doencaDAO = new DoencaDAO($bd);
$remedioDAO = new RemedioDAO($bd);

date_default_timezone_set('America/Sao_Paulo');
?>