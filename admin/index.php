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
        
        <link rel="stylesheet" href="../css/popup.css">
        
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
              }
        </style>
        
        <link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        
        <script src="../scripts/jquery-1.9.1.min.js"  ></script>
                
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

</head>
    
    <body>
        
        <?php
            include("../parts/header.php");
        ?>
        
        <div class="container">

            <div class="jumbotron" style="padding-top:15px; overflow:hidden; height:150px">

                <h1 style="font-size:300%;">Page d'administration</h1>
                <p style="margin-top:-20px">
                    <font size='3' >
                        <br>** En Construction **
                    </font>
                </p>

            </div>
        </div>
        
        <script src="../scripts/jquery.bpopup.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
         <script src="../scripts/jquery.easing.1.3.js"></script>
        <script src="../scripts/scripting.min.js"></script>
        
        
    </body>

</html>