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
        <title>Page d'installation du Logiciel Billetterie Evenementielle UTC</title>
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

            function ajaxFileUpload()
            {
                $(document).ajaxStart(function(){ 
                    $("#loadingGIF").attr('class', 'show'); 
                    $("#resultatUpload").attr('class', 'hide'); 
                });
                $(document).ajaxComplete(function(){ 
                    $("#loadingGIF").attr('class', 'hide'); 
                });

                $.ajaxFileUpload
                (
                    {
                        url:'doajaxfileupload.php',
                        secureuri:false,
                        fileElementId:'excelInput',
                        dataType: 'json',
                        data:{name:'logan', id:'id'},
                        success: function (data, status)
                        {
                            if(typeof(data.error) != 'undefined')
                            {
                                if(data.error != '')
                                {
                                    //alert(data.error);
                                    $("#resultatUpload").attr('class', 'alert alert-warning show');
                                    $("#resultatUpload").html(data.error);
                                    
                                }else
                                {
                                    //alert(data.msg);
                                    $("#resultatUpload").attr('class', 'alert alert-success show');
                                    $("#resultatUpload").html(data.msg);
                                   
                                }
                            }
                        },
                        error: function (data, status, e)
                        {
                            alert(e);
                        }
                    }
                )
                
                return false;

            }
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
                    <p style="margin-top:-20px">
                        <font size='3' >
                            <br>** En Construction **
                        </font>
                    </p>

                </div>

                <div class="col-md-12">

                    <div class="alert alert-danger">
                        Veuillez choisir le fichier Excel à importer dans la base de données.<br><br>
                        <b>Attention, l'upload effacera la base de données au complet (votes / stages présents / notes ...)</b>
                    </div>
        
                    <img id="loadingGIF" src="../images/loading.gif" class="hide">
                    <form name="form" action="" method="POST" enctype="multipart/form-data">

                        <input type="file" title="Search for the Excel File to Import" class="btn-primary" id="excelInput"  name="excelInput" class="input">
                        <button class="btn btn-success" id="buttonUpload" onclick="return ajaxFileUpload();" style="margin-left:40px;">Upload</button>     
       
                    </form>     

                    <div class="hide" id="resultatUpload" style="margin-top:30px"> 
                
                    </div>

                </div>

            </div>
        </div>

        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../scripts/bootstrap.file-input.js"></script>
        <script type="text/javascript">
            $('input[type=file]').bootstrapFileInput();
            $('.file-inputs').bootstrapFileInput();
        </script>
        
    </body>
</html>