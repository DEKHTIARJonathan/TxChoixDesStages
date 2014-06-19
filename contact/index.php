<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Page de Contact</title>
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
        /*
            var RecaptchaOptions = {
                lang : 'fr',
                theme : 'white',
            };
        */
        </script>


</head>
    
    <body>
        
        <?php
            include("../parts/header.php");
        ?>
        
        <div class="container">

            <div class="jumbotron" style="padding-top:15px; overflow:hidden; height:200px">
                    
                <h1 style="font-size:300%;">Contacte le Responsable des Stages</h1>
                <p style="margin-top:-20px">
                    <font size='3' >
                        <br>Vous rencontrez un problème avec l'utilisation de la solution logicielle ?<br>
                        <b>N'hésitez pas à nous contacter</b>
                    </font>
                </p>

             </div>
             <br>
             
            <div class="row">    
                <!-- Alignment -->
                <div class="col-sm-12">
                    <!-- Form itself -->
                    <form name="sentMessage"  id="contactForm"  novalidate>
                        <table class="col-sm-12">
                            <tr>
                                <td class="col-sm-6">
                                    <div class="control-group">
                                        <div class="controls"  style="height:85px;">
                                            <input type="text" class="form-control" placeholder="Votre Nom" id="name" required data-validation-required-message="Veuillez entrer votre nom" />
                                        </div>
                                    </div>
                                </td>
                                <td class="col-sm-6">
                                    <div class="control-group">
                                        <div class="controls" style="height:85px;">
                                            <input type="email" class="form-control" placeholder="Votre Email" id="email" require data-validation-required-message="Veuillez entrer votre email" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>  

                        <div class="control-group col-sm-12">
                            <div class="controls" style="height:250px;">
                                <textarea rows="10" cols="100" class="form-control" placeholder="Votre Message" id="message" required data-validation-required-message="Veuillez entrer votre message" minlength="5" data-validation-minlength-message="Min 5 characters" maxlength="999" style="resize:none"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">Send</button><br />
                        </div>        
                        <div id="success" class="col-sm-12" style="margin-top:25px;"> </div> <!-- For success/fail messages -->
                        
                    </form>
                </div>
            </div>
                
            
                 
            <?php
                include("../parts/footer.php");
            ?>
            
        </div>
        
        
         
    </body>

     <!-- JS FILES -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> 
 <script src="./js/bootstrap.min.js"></script>
 <script src="./js/jqBootstrapValidation.js"></script>
 <script src="./js/contact_me.js"></script>

</html>