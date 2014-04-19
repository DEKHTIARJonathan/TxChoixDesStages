<?php
	// Page d'accueil : /index.php 
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    
?>
<!DOCTYPE html>
<html>
    <head>
    
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        
        <title>Suiveur TN09/TN10 GSM - Interface de choix des stages</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
              }
        </style>
        
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        
        <script src="scripts/jquery-1.9.1.min.js"  ></script>

        
        
    </head>
    <body>
    
      <div class="container">
       <?php
            include("parts/header.php");
        ?>
        
        <div class="container">
            <div id="wrap">
                
                    <div class="page-header">
                        <a href="#" id="logout" class="btn btn-danger pull-right" style="display: none;"> Logout </a>
                        <h1>Suiveur TN09/TN10 GSM - Choix des stages</h1> 
                    </div>
                    <div id="alert" style="display:none;" class="alert alert-block">
                        <h4 id="alert-title">Alert title</h4>
                        <p id="alert-content">Alert content</p>        
                    </div>
                    <p class="lead" id="lead"></p>
                    
                    <p id="content">
                    <?php
                    	if( !isset($_SESSION['login']) || $_SESSION['login'] == '')
                    	{
                    		echo '<div class="alert alert-error" style="width:780px; height:60px;">';
                    		echo '<div style="margin-top:20px">Pour pouvoir réaliser des voeux, vous devez vous authentifier.</div>';
							echo '<a href="inc/connect.php" class="btn btn-large btn-info pull-right" id="cas-connection" style="margin-top:-30px"> Me connecter </a>';
							echo '</div>';
						}
						else
						{
							echo '<div class="alert alert-success" style="width:780px; height:60px;">';
                    		echo "<div style='margin-top:20px'>Bienvenue <b>".$_SESSION["auth"]["login_utc"]."</b> sur l'interface de réalisation de voeux pour les stages TN09 & TN10</div>";
							echo '<a href="inc/disconnect.php" class="btn btn-large btn-danger pull-right" id="cas-disconnection" style="margin-top:-30px"> Me Déconnecter </a>';
							echo '</div>';
						}
					?>
					</p>
                    <br /><br /><br />
                </div>
            </div>
            
            <?php
                include("parts/footer.php");
            ?>
         
            <div class="modal hide fade" id="modal" style="display: none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 id="modal-header"></h3>
                </div>
                <div class="modal-body" id="modal-body">
                </div>
            </div>


        </div>
    
        <script src="bootstrap/js/bootstrap-modal.js"></script>
        <script src="bootstrap/js/bootstrap.min.js">