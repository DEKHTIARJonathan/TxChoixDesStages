<?php header("Content-Type: text/html; charset=UTF-8"); ?>
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
        <script type="text/javascript" src="scripts/general.js" charset="utf-8"></script>

        
        
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
                    <p id="content"></p>
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
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
