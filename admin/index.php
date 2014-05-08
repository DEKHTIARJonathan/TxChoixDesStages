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
        <script type="text/javascript" src="../scripts/ajaxfileupload.js"></script>
        
        <script type="text/javascript">

            function ajaxFileUploadTN09()
            {
                $(document).ajaxStart(function(){ 
                    $("#loadingGIF").attr('class', 'show'); 
                    $("#resultatUpload").attr('class', 'hide'); 
                });
                $(document).ajaxComplete(function(){ 
                    $("#loadingGIF").attr('class', 'hide'); 
                });

                $.ajaxFileUploadTN09
                (
                    {
                        url:'doajaxfileuploadTN09.php',
                        secureuri:false,
                        fileElementId:'excelInputTN09',
                        dataType: 'json',
                        data:{name:'logan', id:'id'},
                        success: function (data, status)
                        {
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
                    $("#loadingGIF").attr('class', 'hide'); 
                });

                $.ajaxFileUploadTN10
                (
                    {
                        url:'doajaxfileuploadTN10.php',
                        secureuri:false,
                        fileElementId:'excelInputTN10',
                        dataType: 'json',
                        data:{name:'logan', id:'id'},
                        success: function (data, status)
                        {
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
        
                    
                    
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <form name="form" action="" method="POST" enctype="multipart/form-data">

                                <input type="file" title="TN09 : Search for the Excel File to Import" class="btn-primary" id="excelInputTN09"  name="excelInputTN09" class="input">
                                <button class="btn btn-success" id="buttonUploadTN09" onclick="return ajaxFileUploadTN09();" style="margin-left:40px;">Upload TN09</button>     
               
                            </form>
                            <br><br>
                            <form name="form" action="" method="POST" enctype="multipart/form-data">

                                <input type="file" title="TN10 : Search for the Excel File to Import" class="btn-primary" id="excelInputTN10"  name="excelInputTN10" class="input">
                                <button class="btn btn-success" id="buttonUploadTN10" onclick="return ajaxFileUploadTN10();" style="margin-left:40px;">Upload TN10</button>     
               
                            </form>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-danger" id="buttonClear" style="margin-top:40px;width: 250px">Clear Database</button>
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
                    url : "clearDB.php",
                    type : "GET",
                    dataType : "json",

                    success: function(data){
                        //$("#resultatUpload").attr('class', 'alert alert-success show');
                        //$("#resultatUpload").html(data);
                        

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