<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Page de Contact de l'équipe Billetterie Evenementielle UTC</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Jonathan Dekhtiar">

        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
              }
        </style>
        
        <link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        
        <script src="../scripts/jquery-1.9.1.min.js"  ></script>
        <script src="../scripts/mailer.js"  ></script>
                
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script type="text/javascript">
            var RecaptchaOptions = {
                lang : 'fr',
                theme : 'white',
            };
        </script>


</head>
    
    <body>
        
        <?php
            include("../parts/header.php");
        ?>
        
        <div class="container">

            <div class="hero-unit" style="height:170px; padding-top:25px; overflow:hidden;">
                 <h1>Contacte la Team</h1>
                    Vous souhaitez prendre part au projet de la Billetterie Évènementielle ?<br>
                    Vous rencontrez un problème avec l'utilisation de la solution logicielle ?<br><br>
                    <b>N'hésitez pas à nous contacter</b>
             </div>
             <br>
             
            <form method="post" name="MailerForm" id="MailerForm" action="#">
            
                <div class="row-fluid controls">
                    <input id="name" name="name" type="text" class="span6" placeholder="Nom"> &#160;&#160;&#160;&#160;&#160;
                    <input id="email" name="email" type="email" class="span6" placeholder="Adresse Email"><br>
                    <div class="row-fluid">
                            <textarea id="message" name="message" class="span12" placeholder="Votre Message" rows="8"></textarea>
                    </div><br>
                    
                    
                    
                    
                     <div class="row-fluid" style="margin-bottom:-50px;">
                        <div class="span4">
                        
                        <?php
                            require_once('recaptchalib.php');
                            require_once('mail_conf.inc.php');

                            echo recaptcha_get_html($publickey);
                        ?>
                        </div>
                        <div class="span2" style="margin-top:95px;">
                            <input type="submit" id="contact-submit" class="btn btn-primary" style="width:150px;" value="Envoyer" />
                        </div>

                    </div>
                    
                </div>
                  
              </form>
                  
            </div> 
            
                     
            <?php
                include("../parts/footer.php");
            ?>
    
        
        
         
    </body>

</html>