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

            <div class="hero-unit">
                 <h1>Installation de la Badgeuse NFC</h1>

                <p>
                    <br>Cette page a pour but de vous aider à paramétrer et utiliser la Billetterie Évènementielle de l'UTC.<br>
                    Vous y trouverez toutes les informations nécessaires afin de faire fonctionner le système Badgeuse NFC
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
                     <h2>Achat de la badgeuse</h2>

                    <p>
                        La badgeuse sur laquelle le système a été développé : <b>ACR122U USB NFC Reader</b><br><br>
                        Elle se trouve facilement pour 50€ sur internet => Ebay. <br>
                        Attention les <b>delais de livraison</b> peuvent être long :<br> 3 Semaines en provenance de Chine<br><br>
                    </p>

                    <p align="center" style="margin-left:-20px;">
                        <a class="btn btn-info" href="http://www.acs.com.hk/index.php?pid=product&id=ACR122U" target="_blank">Site Officiel</a> <a class="btn btn-info" href="http://www.ebay.fr/sch/i.html?_nkw=NFC+ACR122U+&_sacat=0&_odkw=ACR122U&_osacat=0&_from=R40&LH_PrefLoc=2" target="_blank">Achat Badgeuse</a>
                    </p>
                    
                </div>
                <div class="span4">
                     <h2>Installation du Logiciel</h2>

                    <p>
                        Dans cette section vous trouverez les informations nécessaires pour paramétrer votre badgeuse une fois le système Linux installée et la badgeuse achetée.<br><br>
                        Veuillez vous munir de la badgeuse et de l'ordinateur connecté à Internet <br><br>
                    </p>
                    <p align="center" style="margin-left:-20px;">
                        <a id="pop_btn" class="btn btn-info small pop1" data-bpopup="{"transition":"slideDown","speed":850,"easing":"easeOutBack"}" target="_blank">Tutoriel d'installation</a> 
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
                    <h1>Installation Badgeuse NFC</h1>
                    <br>En cas de problème lors de l'installation, vous pouvez utiliser le <a href="../contact/" target="_blank">formulaire de contact</a> pour nous en faire part.<br><br>
                </div>
            
                <div class="container-fluid">
                    <div class="row-fluid">        
                        <div class="span3">
                            <h3>Menu</h3><br>
                                
                                <a href="#" class="btn btn-info btn-large" style="width: 85%;" name="div1">Installation du Driver</a><br><br>
                                <a href="#" class="btn btn-info btn-large" style="width: 85%;" name="div2">Installation des Librairies</a><br><br>
                                <a href="#" class="btn btn-info btn-large" style="width: 85%;" name="div3">Lancement du Logiciel</a><br><br>
                        </div>
                        <div class="span9" style="max-height : 370px; overflow:auto;">
                            <div id="div1" style="display:none">
                            <h3> Installation du Driver PC/SC</h3>
                            Installation du Driver Linux : <a href="../driver/drivers.zip" class="btn btn-danger">Download</a><br>
                            
                            Vous allez devoir choisir le package relatif à votre version de votre linux.<br><br>
                            Ouvrir un terminal et entrer : "<EM><b>cat /etc/issue</b></EM>"<br><br>
                            Choisissez par la suite le bon package à installer<br><br>
                            
                            <table>
                                <tr align="center">
                                    <td align="center" style="width:200px;"><b>Ubuntu</b></td><td align="center" style="width:200px;"><b>Debian</b></td>
                                </tr>
                                <tr align="center">
                                    <td align="center">12.10 : Quantal</td align="center"><td>7.0 : Wheezy</td>
                                </tr> 
                                <tr align="center">
                                    <td align="center">12.04 : Precise</td><td>6.0 : Squeeze</td>
                                </tr> 
                                <tr align="center">
                                    <td align="center">11.10 : Oneiric</td align="center"><td>-</td>
                                </tr>  
                                <tr align="center">
                                    <td align="center">11.04 : Natty</td align="center"><td>-</td>
                                </tr> 
                                <tr align="center">
                                    <td align="center">10.04 : Lucid</td align="center"><td>-</td>
                                </tr> 
                                <tr align="center">
                                    <td align="center">8.04 : Hardy</td align="center"><td>-</td>
                                </tr>
                            </table>
                            </div>
                            
                            <div id="div2" style="display:none">
                            
                            
                                <h3>Installation des Librairies pour l'éxécution du Logiciel</h3>
                                
                                Ouvrir un terminal et entrer : "<EM><b>apt-get install python-pyscard pcscd pcsc-tools</b></EM>"<br><br>
                            
                                Télécharger notre logiciel maison : <a href="../daemon.py" class="btn btn-danger">Download</a><br>
                                
                                Déplacer le fichier <em><b>daemon.py</b></em> sur le bureau.
                                
                                Toujours dans le terminal éxécuter : "<EM><b>cd ~/Bureau</b></EM>" ou "<EM><b>cd ~/Desktop</b></EM>"<br>
                                Selon si votre version est <b>française ou anglaise</b>. <br><br>NE PAS FERMER LE TERMINAL !<br><br>
                                
                                
                                Toujours dans le même terminal, éxécuter : "<EM><b>chmod +x daemon.py</b></EM>"<br><br>
                            
        
                            </div>
                            
                            <div id="div3" style="display:none">
                            <h3>Lancement du Logiciel --> À chaque utilisation de la badgeuse</h3>
        
                                
                                Ouvrir le terminal et éxécuter : "<EM><b>cd ~/Bureau</b></EM>" ou "<EM><b>cd ~/Desktop</b></EM>"<br>
                                Selon si votre version est <b>française ou anglaise</b>. <br><br>NE PAS FERMER LE TERMINAL !<br><br>
                                
                                
                                Toujours dans le même terminal, éxécuter : "<EM><b>python daemon.py</b></EM>"<br><br>
                                
                                <b>Ne PLUS fermer le terminal</b>. Se connecter au site web et utiliser le navigateur normalement.
        
                            </div>
                            <div id="Start">
                            <h3>Tutoriel d'installation</h3>
        
                                Pour installer le logiciel, veuillez suivre les étapes dans l'ordre du menu de gauche.
        
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