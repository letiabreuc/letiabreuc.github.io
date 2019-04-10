<?php
 
session_start();

if(isset($_SESSION['valida_cadastro'])){
	$valida_cadastro = $_SESSION['valida_cadastro'];
	$valores_cadastro = $_SESSION['valores_cadastro'];
	$classe_form = $_SESSION['classe_form'];
	$classe_span = $_SESSION['classe_span'];
	
	session_destroy();
}else{
	$classe_span = array("username" => NULL, "senha" => NULL, "email" => NULL, "nome" => NULL, "sobrenome" => NULL, "telefone" => NULL, "data_nascimento" => NULL, 
							 "especialidade" => NULL, "crm" => NULL, "endereco_comercial" => NULL, "cod" => NULL);	
	
	$classe_form = array("username" => NULL, "senha" => NULL, "email" => NULL, "nome" => NULL, "sobrenome" => NULL, "telefone" => NULL, "data_nascimento" => NULL, 
							 "especialidade" => NULL, "crm" => NULL, "endereco_comercial" => NULL, "cod" => NULL);
	
	$valida_cadastro = array("username" => NULL, "senha" => NULL, "email" => NULL, "nome" => NULL, "sobrenome" => NULL, "telefone" => NULL, "data_nascimento" => NULL, 
							 "especialidade" => NULL, "crm" => NULL, "endereco_comercial" => NULL, "cod" => NULL);
	
	$valores_cadastro = array("username" => NULL, "senha" => NULL, "email" => NULL, "nome" => NULL, "sobrenome" => NULL, "telefone" => NULL, "data_nascimento" => NULL, 
							  "especialidade" => NULL, "crm" => NULL, "endereco_comercial" => NULL, "cod" => NULL, "tipo" => NULL);
}
?>

<html>
    <head>
        <meta charset="UTF-8" />
        <title>Cadastro</title>
        <script src="java.js"></script>
		<link rel="icon" href="Imagens/favicon.png">
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
                <script src="bootstrap/js/jquery.min.js"></script>
                <script src="bootstrap/js/bootstrap.min.js"></script>	
		
		<link rel="stylesheet" href="style.css" type="text/CSS">
    </head>
    <body class="cadastro" onLoad="cadastro<?php echo $valores_cadastro['tipo']; ?>()">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
				</div>
				<div class="col-sm-4">
					<h1>Cadastro</h1>
				</div>
				<div class="col-sm-4">
				</div>
			</div>	
			<div class="row">
				<div class="col-sm-4">
				</div>
				<div class="col-sm-4">
					<div class="panel panel-default">
						<div class="panel-body" id="panel-cadastro">
							<form action="valida_cadastro.php" method="post" id="formulario_cadastro" novalidate>
								<label ><input type="radio" name="tipo" value="paciente" id="radio_paciente" onClick="cadastroPaciente()" checked/> Paciente</label> &emsp;
								<label ><input type="radio" name="tipo" value="Medico" id="radio_medico" onClick="cadastroMedico()" /> Médico</label>
								
								<div class="form-group <?php echo $classe_form['nome']; ?>">
									<label for="nome">Nome</label> <small class="text-danger"><?php echo $valida_cadastro['nome']; ?></small>
									<input class="form-control" id="nome" type="text" name="nome" value="<?php echo $valores_cadastro['nome']; ?>" />
									<span class="<?php echo $classe_span['nome']; ?>"></span>
								</div>
								
								<div class="form-group <?php echo $classe_form['sobrenome']; ?>">
									<label>Sobrenome</label> <small class="text-danger"><?php echo $valida_cadastro['sobrenome']; ?></small>   
									<input class="form-control" type="text" name="sobrenome" value="<?php echo $valores_cadastro['sobrenome']; ?>" />									
									<span class="<?php echo $classe_span['sobrenome']; ?>"></span>
								</div>
								
								<div class="form-group <?php echo $classe_form['data_nascimento']; ?>">
									<label>Data de Nascimento</label> <small class="text-danger"><?php echo $valida_cadastro['data_nascimento']; ?></small>   
									<input class="form-control" type="date" name="data_nascimento" value="<?php echo $valores_cadastro['data_nascimento']; ?>" />
									<span class="<?php echo $classe_span['data_nascimento']; ?>"></span>
								</div>
								
								<div id="formulario_paciente">
									<div class="form-group <?php echo $classe_form['cod']; ?>">
										<label>Matrícula</label> <small class="text-danger"><?php echo $valida_cadastro['cod']; ?></small>   
										<input class="form-control" type="text" name="cod" value="<?php echo $valores_cadastro['cod']; ?>" />  
										<span class="<?php echo $classe_span['cod']; ?>"></span>
									</div>
								</div>
								
								<div id="formulario_medico" hidden>
									<div class="form-group <?php echo $classe_form['especialidade']; ?>">
										<label>Especialidade</label> <small class="text-danger"><?php echo $valida_cadastro['especialidade']; ?></small>   
										<input class="form-control" type="text" name="especialidade" value="<?php echo $valores_cadastro['especialidade']; ?>" />  
										<span class="<?php echo $classe_span['especialidade']; ?>"></span>
									</div>
									
									<div class="form-group <?php echo $classe_form['crm']; ?>">
										<label>CRM</label> <small class="text-danger"><?php echo $valida_cadastro['crm']; ?></small>   
										<input class="form-control" type="text" name="crm" value="<?php echo $valores_cadastro['crm']; ?>" />  
										<span class="<?php echo $classe_span['crm']; ?>"></span>
									</div>
									
									<div class="form-group <?php echo $classe_form['endereco_comercial']; ?>">
										<label>Endereço Comercial</label> <small class="text-danger"><?php echo $valida_cadastro['endereco_comercial']; ?></small>   
										<input class="form-control" type="text" name="endereco_comercial" value="<?php echo $valores_cadastro['endereco_comercial']; ?>" /> 
										<span class="<?php echo $classe_span['endereco_comercial']; ?>"></span> 
									</div>
								</div>
								
								<div class="form-group <?php echo $classe_form['telefone']; ?>">
									<label>Telefone</label> <small class="text-danger"><?php echo $valida_cadastro['telefone']; ?></small>   
									<input class="form-control" type="text" name="telefone" value="<?php echo $valores_cadastro['telefone']; ?>" />  
									<span class="<?php echo $classe_span['telefone']; ?>"></span>
								</div>
								
								<div class="form-group <?php echo $classe_form['email']; ?>">
									<label>E-mail</label> <small class="text-danger"><?php echo $valida_cadastro['email']; ?></small>   
									<input class="form-control" type="text" name="email" value="<?php echo $valores_cadastro['email']; ?>" />  
									<span class="<?php echo $classe_span['email']; ?>"></span>
								</div>
								
								<div class="form-group <?php echo $classe_form['username']; ?>">
									<label>Nome de Usuário</label> <small class="text-danger"><?php echo $valida_cadastro['username']; ?></small>   
									<input class="form-control" type="text" name="username" value="<?php echo $valores_cadastro['username']; ?>" />  
									<span class="<?php echo $classe_span['username']; ?>"></span>
								</div>
								
								<div class="form-group <?php echo $classe_form['senha']; ?>">
									<label>Senha</label> <small class="text-danger"><?php echo $valida_cadastro['senha']; ?></small>   
									<input class="form-control" type="password" name="senha" />  
									<span class="<?php echo $classe_span['senha']; ?>"></span>
								</div>
								
								<div class="form-group">
									<label>Confirme sua Senha</label>   
									<input class="form-control" type="password" name="confirmacao_senha" />  
								</div>								  
							</form>
							
							<a class="btn btn-primary" href="login.php">Voltar</a>
							<button class="btn btn-primary  pull-right" form="formulario_cadastro" type="submit"> Enviar </button> 						
						</div>
					</div>
				</div>
				<div class="col-sm-4">
				</div>
		</div>	
    </body>
</html>
