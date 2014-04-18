<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Page d'installation du Serveur Offline Billetterie Evenementielle UTC</title>
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

            <div class="hero-unit">
                 <h1>Installation du serveur Offline</h1>

                <p>
                    <br>Cette page a pour but de vous aider à paramétrer et utiliser la Billetterie Évènementielle de l'UTC.<br>
                    Vous y trouverez toutes les informations nécessaires afin de faire fonctionner le serveur Offline nécessaire lors de l'évènement.
                </p>

            </div>
            <!-- Example row of columns -->
            <div class="row">
                <div class="span4">
                     <h2>Installation de Ubuntu</h2>

                    <p>
                        Pour faire fonctionner notre système, nous nous sommes basé sur l'utilisation d'un environnement Linux.<br><br>
                        Pour utiliser la Billetterie Évènementielle UTC, veuillez télécharger et installer Ubuntu ou Debian.<br><br><br>
                    </p>
                    <p align="center" style="margin-left:-20px;">
                        <a class="btn btn-info" href="http://www.ubuntu.com/download" target="_blank">Site Officiel de Ubuntu</a> <a class="btn btn-info" href="http://www.debian.org/distrib/" target="_blank">Site Officiel de Debian</a>
                    </p>
                </div>
                <div class="span4">
                     <h2>Configuration du NFC</h2>

                    <p>
                        La badgeuse sur laquelle le système a été développé : <b>ACR122U USB NFC Reader</b><br><br>
                        Veuillez suivre le tutoriel présent sur cette page pour installer la badgeuse NFC, partie essentiel du système.<br><br><br>
                    

                    <p align="center" style="margin-left:-20px;">
                        <a class="btn btn-info" style="width:170px;" href="../setup/" target="_blank">Installation du NFC</a>
                    </p>
                    
                </div>
                <div class="span4">
                     <h2>Installation du Serveur</h2>

                    <p>
                        Dans cette section vous trouverez les informations nécessaires pour paramétrer votre système Linux pour y installer le serveur offline.<br><br>
                        Veuillez vous munir de l'ordinateur connecté à Internet <br><br><br>
                    </p>
                    <p align="center" style="margin-left:-20px;">
                        <a id="pop_btn" style="width:170px;" class="btn btn-info small pop1" data-bpopup="{"transition":"slideDown","speed":850,"easing":"easeOutBack"}" target="_blank">Tutoriel d'installation</a> 
                    </p>

                </div>
            </div>
            
            <?php
                include("../parts/footer.php");
            ?>
            
            <div id="popup" style="left:200px; position: relative; top: 619px; z-index: 9999; display: none; opacity:1; width:1075px;">
                <span class="button b-close">
                    <span>Close</span>
                </span>
                
                <div class="hero-unit" style="padding-top:20px; height:90px;">
                    <h1>Installation du serveur Offline</h1>
                    <br>En cas de problème lors de l'installation, vous pouvez utiliser le <a href="../contact/" target="_blank">formulaire de contact</a> pour nous en faire part.<br><br>
                </div>
            
                <div class="container-fluid">
                    <div class="row-fluid">        
                        <div class="span3">
                            <h3>Menu</h3><br>
                                
                                <a href="#" class="btn btn-info btn-large" style="width: 95%; margin-left:-15px;" name="div1">Installation du serveur</a><br><br>
                                <a href="#" class="btn btn-info btn-large" style="width: 95%; margin-left:-15px;" name="div2">Installation de l'application</a><br><br>
                                <a href="#" class="btn btn-info btn-large" style="width: 95%; margin-left:-15px;" name="div3">Lancement du Serveur</a><br><br>
                                <a href="#" class="btn btn-info btn-large" style="width: 95%; margin-left:-15px;" name="div4">Import / Export de la DB</a><br><br>
                        </div>
                        <div class="span9" style="max-height : 370px; overflow:auto;">
                            <div id="div1" style="display:none">
                            <h3>Installation du serveur Linux Apache MySQL PHP</h3>
                           
                            Ouvrir un terminal et entrer : "<EM><b>apt-get update</b></EM>"<br><br>
                            
                            Toujours dans le même terminal entrer : "<EM><b>apt-get install apache2 php5 libapache2-mod-php5</b></EM>"<br><br>
                            
                            Toujours dans le même terminal entrer : "<EM><b>apt-get install mysql-server mysql-client php5-mysql</b></EM>"<br><br>
                            
                            <b><br><br>Maintenant une rapide configuration, toujours dans le terminal :</b>
                            
                            "<EM><b>mysql -u root</b></EM>"<br><br>
                            Le prompt devrait maintenant montrer : <b>mysql></b><br><br>
                            Nous allons donc rentrer les lignes suivantes : <br><br>
                            "<EM><b>USE mysql;</b></EM>"<br>
                            "<EM><b>UPDATE user SET Password=PASSWORD('billetterie') WHERE user='root';</b></EM>"<br>
                            "<EM><b>FLUSH PRIVILEGES;</b></EM>"<br>
                                                        
                            
                            </div>
                            
                            <div id="div2" style="display:none">
                            
                            
                                <h3>Installation de la Web-Application Serveur Offline</h3>
                                
                            
                                Télécharger notre logiciel maison : <a href="#" onClick="alert('Pas Encore Dispo');" class="btn btn-danger">Download</a><br>
                                

                            
        
                            </div>
                            
                            <div id="div3" style="display:none">
                            <h3>Lancement du Logiciel</h3>
        
                                Ouvrir le navigateur et taper dans la barre d'adresse :  <a href="http://localhost/">http://localhost/</a>
                                
                                L'adresse IP est affichée dans le navigateur, c'est par celle que les ordinateurs en réseau avec l'ordinateur serveur
                                pourront se connecter à celui.
        
                            </div>
                            <div id="div4" style="display:none">
                            <h3>Import / Export de la Base de Donnée</h3>
        
                                
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas condimentum auctor orci at lacinia. Etiam ornare, quam eu semper tincidunt, erat elit ultricies orci, at vestibulum mi leo in est. Proin vitae orci sit amet metus porttitor tincidunt vitae sed quam. Etiam rutrum aliquet diam vel fringilla. Nam ut porta ipsum. Pellentesque sed bibendum velit. Proin eget nunc dui, eget ultrices leo. Nullam placerat tellus in sem cursus vel mollis nulla hendrerit. Suspendisse ac odio eu tellus hendrerit egestas eu et enim.<br><br>

Fusce non lorem sed metus viverra iaculis quis eget risus. Curabitur cursus laoreet quam non commodo. Fusce velit dui, interdum non bibendum et, posuere sed turpis. Quisque sollicitudin pellentesque commodo. Donec in massa in erat tincidunt gravida. In neque lorem, egestas nec viverra quis, egestas ac nisl. Fusce dolor tellus, ultricies eget fermentum eu, cursus at nisl. Nam dignissim venenatis urna, commodo tincidunt ligula consequat in.
        
                            </div>
                            <div id="Start">
                            <h3>Tutoriel d'installation</h3>
        
                                Pour installer le serveur, veuillez suivre les étapes dans l'ordre du menu de gauche.<br><br>
                                Seul un des ordinateurs ne nécessitera d'être capable de faire tourner le serveur et il pourra être utilisé 
                                simmultannément pour le contrôle des places. 
                                Cependant nous vous conseillons d'installer le serveur sur tous les ordinateurs pour palier à tout problème.
        
                            </div>
                        </div>
                      </div>
                    </div>                 
                </div>
            </div>
        <!-- /container -->
 
        
        
        <script>
            $(document).ready(function()
            {
                $('a').click(function () 
                {
                    var divname= this.name;
                    $("#"+divname).show("slow").siblings().hide("slow");
                });
            });
        </script> 
        
        <script src="../scripts/jquery.bpopup.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
         <script src="../scripts/jquery.easing.1.3.js"></script>
        <script src="../scripts/scripting.min.js"></script>
        
        
    </body>

</html>