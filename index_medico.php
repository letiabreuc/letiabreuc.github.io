<?php
require_once "classes.php";
require_once "funcoes.php";
session_start();

$usuario = $_SESSION['usuario'];
?>

<html>
    <head>
        <meta charset="UTF-8">      
        <title>iCliniMed</title>
        <link rel="icon" href="/Imagens/favicon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/CSS" href="sticky-footer.css">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

        <link rel="stylesheet" type="text/CSS" href="style.css" />
        <script src="java.js"></script>
	</head>

    <body onload="mostraCalendario(); mostraIndexMedico()">
        <div class="container-fullwidth">           
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <img src="Imagens/logo.png" alt="icone" height="40px">
                        <a class="navbar-brand" href="#" onclick="mostraIndexMedico()">iCliniMed</a>
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
                                <button class="btn menu" onclick="mostraProntuarios()">Pacientes</button>
                                <button class="btn menu" onclick="mostraAgenda('dia', '<?php echo date("Y-m-d"); ?>')">Agenda</button>
                                <button class="btn menu" onclick="mostraProtocolos()">Protocolos</button>
                                <button class="btn menu">Médicos</button>
                                <button class="btn menu">Remédios</button>
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
                    <div class="panel panel-default" id="panel-notificacao">
                        <div class="panel-heading">
                            <h4 class="notificacao">Notificações</h4>
                        </div>
                        <div class="panel-body">
                            <div class="notificacao">
                                <h4>Carlos de Jesus</h4>
                                <p>Adicionou um exame</p>
                                <p class="pull-right text-muted">Por: Dr. Carlos Moreira</p>
                                <br/> <hr/>
                            </div>

                            <div class="notificacao">
                                <h4>Carlos de Jesus</h4>
                                <p>Adicionou um exame</p>
                                <p class="pull-right text-muted">Por: Dr. Carlos Moreira</p>
                                <br/> <hr/>
                            </div>

                          
                            <div class="notificacao">
                                <h4>Carlos de Jesus</h4>
                                <p>Adicionou um exame</p>
                                <p class="pull-right text-muted">Por: Dr. Carlos Moreira</p>
                                <br/> <hr/>
                            </div>
                            
                            <div class="notificacao">
                                <h4>Carlos de Jesus</h4>
                                <p>Adicionou um exame</p>
                                <p class="pull-right text-muted">Por: Dr. Carlos Moreira</p>
                                <br/> <hr/>
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