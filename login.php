<?php

session_start();

if(isset($_SESSION['valida_login'])){
    $valida_login = $_SESSION['valida_login'];
	session_destroy();
}else{
    $valida_login = "";
	session_destroy();
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
		<link rel="icon" href="Imagens/favicon.png">
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
                <script src="bootstrap/js/jquery.min.js"></script>
                <script src="bootstrap/js/bootstrap.min.js"></script>	
		
		<link rel="stylesheet" href="style.css" type="text/CSS">
    </head>
    <body class="login">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-2">
				</div>
				
				<div class="col-md-9">
					<h1> iCliniMed <img src="Imagens/logo2.png" alt="Logo" width="50px"/> </h1>
				</div>
				
				<div class="col-md-1">
				</div>
			</div>
				
			<div class="row">
                <div class="col-md-1"></div>
                         				
				<div class="col-md-2">
					<br/> <br/> <br/>
                    <h3><img src="Imagens/registro.png" alt="registro" height="40px"> <b>Registro</b> </h3> 
					<p class="text-justify descricao">De forma prática e eficiente, o paciente pode registrar suas atividades diárias, e assim ter maior controle sobre a sua rotina</p>
					
					<br/>					
                    <h3><img src="Imagens/agenda.png" alt="agenda" height="40px"> <b>Agenda</b> </h3>
					<p class="text-justify descricao">É possível agendar consultas e exames de maneira prática e organizada, facilitando o manuseio do usuário. Localizada na página inicial podendo ser acessada com um clique.</p>
					
					<br/>
                    <h3><img src="Imagens/pills.png" alt="comprimido" height="40px"> <b>Remédios</b> </h3> 
					<p class="text-justify descricao">Conta com um vasto banco de informações sobre medicamentos, contendo formulação, contra-indicações e efeitos colaterais</p>
				</div>
				
				<div class="col-md-2">
					<br/> <br/> <br/>				
                    <h3><img src="Imagens/folder.png" alt="prontuario" height="40px"> <b>Prontuário</b> </h3>  
					<p class="text-justify descricao">Personalizado e de fácil usabilidade, garante comodidade no processo de gerenciamento dos arquivos dos pacientes</p>
					
					<br/>	<br>				
					<h3><img src="Imagens/conection.png" alt="conexao" height="40px"> <b>Conexão</b> </h3> 
					<p class="text-justify descricao">Facilita o contato e acesso aos registros feitos pelos pacientes e por outros médicos, facilitando acompanhamento do quadro e obtenção do diagnóstico</p>	

					<br/>
                    <h3><img src="Imagens/doenca.png" alt="doenca" height="40px"> <b>Doenças</b> </h3> 
					<p class="text-justify descricao">Possui um índice completo e categorizado de doenças, com informações sobre sintomas, tratamentos e prevenção</p>
				</div>
				
				<div class="col-md-2">
				</div>
				
				<div class="col-md-3" id="form-login">
					<div class="panel panel-default" id="panel-login">
						<div class="panel-body">
							<h1>Login</h1> <br/>							
							<p class="text-danger"><?php echo $valida_login; ?></p>
							<form action="valida_login.php" method="post">
								<div class="form-group">
									<label>Nome de Usuário</label>
									<input class="form-control" type="text" name="username" />
								</div>
								
								<div class="form-group">
									<label>Senha</label>
									<input class="form-control" type="password" name="senha" />
								</div>
								<a class="text-primary" href="cadastro.php">Cadastre-se</a>
								<button class="btn btn-primary pull-right">Enviar</button> <br/>
							</form>
						</div>
					</div>	
				</div>
				
				<div class="col-md-1">
				</div>
		</div>	
    </body>
</html>