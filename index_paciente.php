<?php

require_once "classes.php";
session_start();

$usuario = $_SESSION['usuario'];

?>

<html>
	<head>
        <meta charset="UTF-8">      
        <title>iCliniMed</title>
        <link rel="icon" href="Imagens/favicon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/CSS" href="sticky-footer.css">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

        <link rel="stylesheet" type="text/CSS" href="style.css" />
        <script src="java.js"></script>
    </head>
	
	<body onload="mostraCalendario(); mostraIndexPaciente('refeicao')">
		<div class="container-fullwidth">           
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <img src="Imagens/logo.png" alt="icone" height="50px">
                        <a class="navbar-brand" href="#" onclick="mostraIndexPaciente('')">iCliniMed</a>
                    </div>   
				                  
					<ul class="nav navbar-nav navbar-right">
                        <li>                                
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-user"></span> 
                                <?php echo $usuario->getNome() . " " . $usuario->getSobrenome(); ?> 
                                <span class="caret"></span>
                                &emsp;
                            </a>    
                            <ul class="dropdown-menu">
                                <li><a href="#">Minha Conta</a></li>
                                <li><a href="#">Notificações</a></li>
                                <li><a href="login.php">Sair &emsp; <span class="glyphicon glyphicon-log-out"></span> </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>  
            </nav>              
        </div>

        <div class="container-fluid">            
            <div class="row">
                <div class="col-md-3">
                    <div class="row" id="row-calendario">
                        <div class="col-md-12" id="calendario">
							
                        </div>
                    </div>

                    <div class="row" id="row-menu">
                        <div class="col-md-12">
                            <div class="btn-group-vertical menu">
                                <button class="btn menu" onclick="mostraRegistros()">Registros</button>
								<button class="btn menu" onclick="mostraAgenda('dia', '<?php echo date("Y-m-d"); ?>')">Agenda</button>
                                <button class="btn menu">Medicação</button>
                                <button class="btn menu">Médicos</button>
                                <button class="btn menu">Doenças</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-1">
                </div>

                <div class="col-md-4" id="principal">
					
                </div>

                <div class="col-md-1">
                
                </div>

                <div class="col-md-3">
					<div class="row" id="row-estado">
						<div class="col-md-12">
							<h4 class="estado">Como foi seu dia?</h4>
							<div class="panel panel-default" id="panel-estado">
								<div class="row" id="row-estado-atual" style="height: 30%">
									<div class="col-md-4"></div>
									<div class="col-md-4">
										<img src="Imagens/sad.png" id="estado-atual" alt="Tristao" width="100%">
									</div>
									<div class="col-md-4"></div>
								</div>

								<div class="row">
									<div class="col-md-12">
										
									</div>
								</div>

								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-2 estado-diario " style="height: 10%">
										<a href="#" onclick="selecionaEstado('cry2')"><img src="Imagens/cry2.png" alt="Tristao" width="100%"></a>
									</div>

									<div class="col-md-2 estado-diario">
										<a href="#" onclick="selecionaEstado('sad')"><img src="Imagens/sad.png" alt="Tristinho" width="100%"></a>
									</div>

									<div class="col-md-2 estado-diario">
										<a href="#" onclick="selecionaEstado('normal')"><img src="Imagens/normal.png" alt="Normal" width="100%"></a>
									</div>

									<div class="col-md-2 estado-diario">
										<a href="#" onclick="selecionaEstado('bem')"><img src="Imagens/bem.png" alt="Bem" width="100%"></a>
									</div>

									<div class="col-md-2 estado-diario">
										<a href="#" onclick="selecionaEstado('feliz')"><img src="Imagens/feliz.png" alt="Showzao" width="100%"></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				
					<div class="row" id="row-lembretes">
						<div class="col-md-12">
							<div class="panel panel-default" id="panel-lembretes">
								<div class="panel-heading">
									<h4 class="lembrete">Lembretes</h4>
								</div>
								<div class="panel-body">
									<div class="lembretes-medicacao">
										<p><b>Medicação</b></p>
										<div class="lembrete">
											<p>
												<label><input type="checkbox" name="confirma-medicacao"> Ritalina </label>
												<span class="pull-right">14:00</span>
												<br/> 
												&emsp; <small class="text-muted">200 mg</small> 
											</p>
										</div>
										
										<div class="lembrete">
											<p>
												<label><input type="checkbox" name="confirma-medicacao"> Ritalina </label>
												<span class="pull-right">14:00</span>
												<br/> 
												&emsp; <small class="text-muted">200 mg</small> 
											</p>
										</div>
										<hr/>
									</div> 
									<div class="lembretes-consulta">
										<p><b>Consultas</b></p>
										<div class="lembrete">
											<p>
												Dr. Carlos Moreira
												<span class="pull-right">17:00</span>
											</p>
										</div>
										<hr/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>         
        </div>

        <footer class="footer">
            <div class="container">
                <p class="text-muted">All rights reserved &copy; 2018</p>
            </div>  
        </footer>
	</body>
</html>	