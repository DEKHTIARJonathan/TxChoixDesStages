<?php
    // Page d'administration : /admin/index.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
?>

<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Page d'administration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Jonathan Dekhtiar">

        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }

            .hide {
                display:none;
            }
            .show {
                display:inline;
            }
        </style>


        
        <script src="../scripts/jquery-1.9.1.min.js"  ></script>

    </head>

    <body>

        <div class="container">
            <?php
                include("../parts/header.php");
            ?>
 
            <div class="container">

                <div class="jumbotron col-md-12" style="padding-top:15px; overflow:hidden; height:150px">

                    <h1 style="font-size:300%;">Page d'administration</h1>
                    <p style="font-size:18px; padding-top:7px;">
                        Cette page permet d'exporter la base de données au format Excel.
                    </p>

                </div>

                <div class="col-md-12">
                    
                    <button class="btn btn-danger" id="exporterDB" style="margin-top:40px;width: 250px;">Exporter au format Excel</button>

                    <img id="loadingGIF" src="../images/loading.gif" class="hide" style="margin-top:20px">
                    <div class="hide" id="resultat" style="margin-top:20px"></div>

                </div>

            </div>
        </div>

        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../scripts/bootstrap.file-input.js"></script>
        

        <script type="text/javascript">
            $('#exporterDB').click(function()
            {
                $(document).ajaxStart(function(){ 
                    $("#loadingGIF").attr('class', 'show'); 
                    $("#resultatUpload").attr('class', 'hide'); 
                });
                $(document).ajaxComplete(function(){ 
                    $("#loadingGIF").attr('class', 'hide'); 
                });

                $.ajax(
                {
                    url : "generateExport.php",
                    type : "GET",
                    dataType : "html",

                    success: function(data){
                        $("#resultat").attr('class', 'alert alert-success show');
                        $("#resultat").html(data);
                    }
                    
                });

                return false;
            });
        </script>
        
    </body>
</html>