<?php
    // Page d'administration : /admin/index.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkassist.php';
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
        <script type="text/javascript" src="../scripts/ajaxfileupload.js"></script>
        
        <script type="text/javascript">

            function ajaxFileUploadTN09()
            {
                $(document).ajaxStart(function(){ 
                    $("#loadingGIF").attr('class', 'show'); 
                    $("#resultatUpload").attr('class', 'hide'); 
                });

                $.ajaxFileUploadTN09
                (
                    {
                        url:'/inc/doajaxfileuploadTN09.php',
                        secureuri:false,
                        fileElementId:'excelInputTN09',
                        dataType: 'json',
                        data:{name:'logan', id:'id'},
                        success: function (data, status)
                        {
                            if(typeof(data.error) != 'undefined')
                            {
                                $("#loadingGIF").attr('class', 'hide'); 
                                if(data.error != '')
                                {   
                                    $("#resultatUpload").attr('class', 'alert alert-warning show');
                                    $("#resultatUpload").html(data.error);
                                    
                                }
                                else
                                {
                                    $("#resultatUpload").attr('class', 'alert alert-success show');
                                    $("#resultatUpload").html(data.msg);
                                }
                            }
                            document.getElementsByName('fileName')[0].innerHTML = "";
                        },
                        error: function (data, status, e)
                        {
                            alert(e);
                        }
                    }
                )

                return false;

            };

        </script>

        <script type="text/javascript">

            function ajaxFileUploadTN10()
            {
                $(document).ajaxStart(function(){ 
                    $("#loadingGIF").attr('class', 'show'); 
                    $("#resultatUpload").attr('class', 'hide'); 
                });
                $(document).ajaxComplete(function(){ 
                    
                });

                $.ajaxFileUploadTN10
                (
                    {
                        url:'/inc/doajaxfileuploadTN10.php',
                        secureuri:false,
                        fileElementId:'excelInputTN10',
                        dataType: 'json',
                        data:{name:'logan', id:'id'},
                        success: function (data, status)
                        {
                            if(typeof(data.error) != 'undefined')
                            {
                                $("#loadingGIF").attr('class', 'hide'); 
                                if(data.error != '')
                                {   
                                    $("#resultatUpload").attr('class', 'alert alert-warning show');
                                    $("#resultatUpload").html(data.error);
                                }
                                else
                                {
                                    $("#resultatUpload").attr('class', 'alert alert-success show');
                                    $("#resultatUpload").html(data.msg);
                                }
                            }
                            document.getElementsByName('fileName')[0].innerHTML = "";
                            document.getElementsByName('fileName')[1].innerHTML = "";

                        },
                        error: function (data, status, e)
                        {
                            alert(e);
                        }
                    }
                )

                return false;

            };
        </script>

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
                        Cette page permet de nettoyer le système et d'importer les nouvelles données au sein du système.
                    </p>

                </div>

                <div class="col-md-12">
                    
                    <div class="alert alert-danger">
                        <b>Instructions :<br><br></b>
                            <ol>
                                <li>Nettoyer la base de données des anciennes données en cliquant sur : "Vider la Base de Données".</li>
                                <li>Rechercher le fichier Excel contenant les stages TN09 en cliquant sur "TN09 : Selectionner le fichier à importer".</li>
                                <li>Envoyer le fichier sur le serveur en cliquant sur "Envoyer le fichier TN09".</li>
                                <li>Répéter les instructions 2 et 3 pour les stages TN10.</li>
                            </ol>
                    </div>
        
                    
                    
                    <div class="col-md-12">
                        <div class="col-md-9">
                            <form name="form" action="" method="POST" enctype="multipart/form-data">

                                <input type="file" title="TN09 : Selectionner le fichier à importer" class="btn-primary" id="excelInputTN09"  name="excelInputTN09" class="input">
                                <button class="btn btn-success" id="buttonUploadTN09" onclick="return ajaxFileUploadTN09();" style="margin-left:40px;">Envoyer le fichier TN09</button>     
               
                            </form>
                            <br><br>
                            <form name="form" action="" method="POST" enctype="multipart/form-data">

                                <input type="file" title="TN10 : Selectionner le fichier à importer" class="btn-primary" id="excelInputTN10"  name="excelInputTN10" class="input">
                                <button class="btn btn-success" id="buttonUploadTN10" onclick="return ajaxFileUploadTN10();" style="margin-left:40px;">Envoyer le fichier TN10</button>     
               
                            </form>
                        </div>
                        <div class="col-md-3" style="">
                            <button class="btn btn-danger" id="buttonClear" style="margin-top:40px;width: 250px;">Vider la Base de Données</button>
                        </div>
                    </div>


                    <img id="loadingGIF" src="../images/loading.gif" class="hide">
                    <div class="hide" id="resultatUpload" style="margin-top:200px"></div>

                </div>

            </div>
        </div>

        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../scripts/bootstrap.file-input.js"></script>
        
        <script type="text/javascript">
            $('input[type=file]').bootstrapFileInput();
            $('.file-inputs').bootstrapFileInput();
        </script>

        <script type="text/javascript">
            $('#buttonClear').click(function()
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
                    url : "/inc/clearDB.php",
                    type : "GET",
                    dataType : "json",

                    success: function(data){                        

                        if(typeof(data.error) != 'undefined')
                        {
                            if(data.error != '')
                            {   
                                $("#resultatUpload").attr('class', 'alert alert-warning show');
                                $("#resultatUpload").html(data.error);
                            }
                            else
                            {
                                $("#resultatUpload").attr('class', 'alert alert-success show');
                                $("#resultatUpload").html(data.msg);
                            }
                        }

                    }
                    
                });

                return false;
            });
        </script>
        
    </body>
</html>