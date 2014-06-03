<?php
    // Page formulaire de modification du compte utilisateur: /myaccount.php
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/dbconnect.php';

    $login = $_SESSION["auth"]["login_utc"];

    $stmt = $connexion->prepare("select count(*) as 'exist' from users where `casLogin` = :login");
    $stmt->bindParam(':login', $login);
    
?>
<!DOCTYPE html>
<html>
    <head>
    
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        
        <title>Suiveur TN09/TN10 GSM - Interface de choix des stages</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
              }
            .inline {
                display:inline;
            }
            .hide {
                display:none;
            }
            .control-group{
                height: 90px;
            }
            .control{
                height: 35px;  
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

                    <?php

                    $stmt-> execute();

                    $userInDB = $stmt -> fetch();

                    if($userInDB['exist'])
                    {
                        
                        $stmt = $connexion->prepare("select * from users where `casLogin` = :login");
                        $stmt->bindParam(':login', $login);
                        $stmt-> execute();
                        $result = $stmt -> fetch();

                        $firstname = $result['firstName'];
                        $lastname = $result['lastName'];
                        $email = $result['email'];


                        echo '<div id="infoSuccess" class="alert alert-success" style="width:780px; height:50px; display: table-cell; vertical-align: middle;">Vous pouvez apporter des modifications à votre compte si nécessaire.</div>';
                        echo '<div id="infoError" class="alert alert-danger hide" style="width:780px; height:50px; display: table-cell; vertical-align: middle;"></div>';
                        echo '<hr>';                        
                    }
                    else
                    {
                        echo '<div id="infoError" class="alert alert-danger" style="width:780px; height:50px; display: table-cell; vertical-align: middle;">Pour pouvoir réaliser des voeux, vous devez vous remplir les informations suivantes.</div>';
                        echo '<div id="infoSuccess" class="alert alert-success hide" style="width:780px; height:50px; display: table-cell; vertical-align: middle;"></div>';
                        echo '<hr>';
                    }

                    ?>
                    
                    <p id="content">

                        <div class="col-md-6">

                            <form data-toggle="validator" role="form" id="account">

                                <div class="control">
                                    <label class="control-label">Login : </label>
                                    <p class="form-control-static inline"><?php echo $login; ?></p>
                                    <input type="hidden"  id="login"  value=<?php echo '"'.$login.'"'; ?>>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="email">Email :</label>
                                    <div class="controls">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Votre Email" value=<?php @print('"'.$email.'"');?>>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="lastname" class="control-label">Nom : </label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Votre Nom" value=<?php @print('"'.$lastname.'"');?>>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="firstname" class="control-label">Prenom : </label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Votre Prenom" value=<?php @print('"'.$firstname.'"');?>>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success">Submit Message</button>
                                </div>
                            </form>

                        </div>

					</p>
                    <br /><br /><br />
                </div>
            </div>
            
            <?php
                include("../parts/footer.php");
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

        <script type="text/javascript">
            $(document).ready(function () {

                $('#account').validate({
                    rules: {
                        lastname: {
                            minlength: 2,
                            required: true
                        },
                        firstname: {
                            minlength: 2,
                            required: true
                        },
                        email: {
                            required: true,
                            email: true
                        }
                    },
                    highlight: function (element) {
                        $(element).closest('.control-group').removeClass('success').addClass('error');
                    },
                    success: function (element) {
                        element.text('OK!').addClass('valid')
                            .closest('.control-group').removeClass('error').addClass('success');
                    },
                    submitHandler: function (element) {
                        $.ajax(
                        {
                            url : "inc/account.php",
                            type : "GET",
                            data: {login: $("#login").val(), email: $("#email").val(),firstname: $("#firstname").val(), lastname: $("#lastname").val()},
                            dataType : "html",

                            success: function(data){

                                var output = jQuery.parseJSON(data);
                                
                                if (output.success == 1)
                                {
                                    $( "#infoError" ).addClass( "hide" );
                                    $( "#infoSuccess" ).removeClass( "hide" );
                                    $( "#infoSuccess" ).html(output.msg);
                                }
                                else
                                {
                                    $( "#infoSuccess" ).addClass( "hide" );
                                    $( "#infoError" ).removeClass( "hide" );
                                    $( "#infoError" ).html(output.msg);
                                }
                            }
                        });
                    }
                });

            });
        </script>

        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../scripts/jquery.validate.js"></script>
    </body>
</html>