<!DOCTYPE html>
<html>
<?php
    $nombre=$user->getNombre();
    $codigo=$user->getCodigo();
    $carrera=$user->getCarrera();
    $semestre=$user->getSemestre();
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UNE Estudiante</title>    
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <script src="vendor/bootstrap/js/jquery-1.11.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <style type="text/css">
       #footer {
           position:fixed;
           left:0px;
           bottom:0px;
           height:50px;
           width:100%;
           background:#0C440B;
           color: white;
           text-decoration-color: white;
        }            
    </style> 
</head>
<body>
    <div class="col-md-12" style="background-color: #0C440B;">     
        <br>       
        <div class="row">
            <div class="col-md-6" align="center"><img src="img/logo.png" width="60%" class="img-fluid" alt="Responsive image"></div>
            <br> 
            <div class="col-md-6" align="left">
                <p class="h6" style="color: white;"><?php echo $codigo;?></p>
                <p class="h6" style="color: white;"><?php echo $nombre;?></p>
                <p class="h6" style="color: white;"><?php echo $carrera;?></p>
                <!--<table class="table table-hover">
                    <tr>
                        <td class="text-muted small "><strong>Estudiante:</strong></td><td class="text-muted small"></td><td class="text-muted small"><strong>Carrera:</strong></td><td class="text-muted small"><?php //echo $carrera;?></td>
                    </tr>
                    <tr>
                        <td class="text-muted small"><strong>Código:</strong></td><td class="text-muted small"><?php //echo $codigo;?></td><td class="text-muted small"><strong>Semestre:</strong></td class="text-muted small"><td class="text-muted small"><?php //echo $semestre;?>° </td>
                    </tr>
                </table>-->
            </div>
        </div> 
        <br>         
    </div>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#ECEBD6;">
        <!--<a class="navbar-brand" href="#">MENU</a>-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active"> 
                    <a class="nav-link" href="#" onclick="ver_materias('<?php echo $codigo;?>')">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-card-checklist" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                            <path fill-rule="evenodd" d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        Materias Registradas
                    </a>
                </li>
                <li class="nav-item active"> 
                    <a class="nav-link" href="#" onclick="ver_materias_examenes('<?php echo $codigo;?>')">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-file-earmark-text" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M4 1h5v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6h1v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/>
                              <path d="M9 4.5V1l5 5h-3.5A1.5 1.5 0 0 1 9 4.5z"/>
                              <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                        Mis Exámenes
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="includes/logout.php">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-box-arrow-in-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.146 11.354a.5.5 0 0 1 0-.708L10.793 8 8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0z"/>
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 1 8z"/>
                             <path fill-rule="evenodd" d="M13.5 14.5A1.5 1.5 0 0 0 15 13V3a1.5 1.5 0 0 0-1.5-1.5h-8A1.5 1.5 0 0 0 4 3v1.5a.5.5 0 0 0 1 0V3a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5h-8A.5.5 0 0 1 5 13v-1.5a.5.5 0 0 0-1 0V13a1.5 1.5 0 0 0 1.5 1.5h8z"/>
                        </svg>Salir
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>-->
            </ul>
        </div>
    </nav>   
    <div id="contenedor"></div>
    <div id="footer">  
        <div class="col-md-12 text-left">
             <p class="text-white small" >© 2020 Copyright SAINCO ESTUDIANTE 2.0 Todos los derechos reservados.</p>
        </div>            
    </div> 
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">                
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" id="cuerpo_modal">                    
                </div>
                <div class="modal-footer" id="myModal_footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div> 
</body> 
<script src="contenedor/js/direcciones.js?v=1.1.3"></script> 
</html>