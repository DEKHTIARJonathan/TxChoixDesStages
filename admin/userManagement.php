<?php
    // Page de voeux : /voeux/index.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkadmin.php';
    require_once $root.'/inc/dbconnect.php';

    $myLogin = $_SESSION["auth"]["login_utc"];
?>
<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Réalisation des voeux</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Jonathan Dekhtiar">

        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        
        <style type="text/css">
            
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            
            td
            {
                vertical-align: middle;
            }
            th
            {
                text-align:center;
            }

        </style>

                    
        
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

            <div class="jumbotron" style="padding-top:15px; overflow:hidden; height:120px">
                <h1 style="font-size:300%;">Management des utilisateurs</h1>
            </div>
            <div class="alert alert-danger">
                <b>Les différents droits d'accès :<br><br></b>
                    <ul>
                        <li><b>Utilisateur :</b> Droit par défaut. Il peut modifier son compte, n'a pas accès à toute la partie d'administration.</li>
                        <li><b>Assistant :</b> En plus des droits de l'utilisateur classique, possibilité de tout effectuer en administration sauf le management des droits d'accès</li>
                        <li><b>Administrateur :</b> Ce dernier a tous les droits, seul droit d'accès permettant de  modifier les droits d'accès.</li>
                    </ul>
                    <br>
                    Chacun des droits peut être donné à plusieurs personnes. (Exemple : il peut y avoir deux administrateurs).<br>
                    <hr>
                    <b><h5> Les modifications des droits d'accès sont automatiquement enregistrées.</h5></b>
            </div>
            
            <div align="center">
                <table class="table table-hover" style="text-align:center;">
                  <thead>
                    <tr>
                      <th>Login UTC</th>
                      <th>Nom</th>
                      <th>Email</th>
                      <th>Droit d'accès</th>
                    </tr>
                  </thead>
                  <tbody data-bind="foreach: MediaGroups">
                
              
                    <?php
    
                        $sth = $connexion->prepare('SELECT `casLogin` as login, CONCAT(`firstName`, " ", `lastName`) as nom, `email`, `userRight` as `right` FROM `users` ORDER BY CASE `right` WHEN "administrateur" THEN 1 WHEN "assistant" THEN 2 ELSE 3 END');
  
                        $users = array();
                        
                        $sth->execute();
                        
                        $i_max = 1;
                        while ($row = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

                            $login = $row['login'];
                            

                            if ($myLogin != $login){  // Prevent to modify yourself.

                                $name = $row['nom'];
                                $email = $row['email'];
                                $right = $row['right'];

                                echo 
                                    '<tr>
                                        <td style="vertical-align:middle;">'.$login.'</td>
                                        <td style="vertical-align:middle;">'.$name.'</td>
                                        <td style="vertical-align:middle;"><a href="mailto:'.$email.'">'.$email.'</a></td>
                                        <td style="vertical-align:middle;">
                                            <select id="select'.$i_max.'" class="form-control" data-login="'.$login.'">';
                                                if ($right == "administrateur"){
                                                    echo '
                                                            <option value="utilisateur">utilisateur</option>
                                                            <option value="assistant">assistant</option>
                                                            <option value="administrateur" selected="selected">administrateur</option>';
                                                }
                                                elseif ($right == "assistant"){
                                                    echo '
                                                            <option value="utilisateur">utilisateur</option>
                                                            <option value="assistant" selected="selected">assistant</option>
                                                            <option value="administrateur">administrateur</option>';
                                                } else {
                                                    echo '
                                                            <option value="utilisateur" selected="selected">utilisateur</option>
                                                            <option value="assistant">assistant</option>
                                                            <option value="administrateur">administrateur</option>';
                                                }
                                            echo '</select></td>
                                    </tr>';
                                $i_max++;
                            }

                            
                            
                        }
                        
                    ?>
                    </tbody>
                </table>
            </div>
            
            <?php
                include("../parts/footer.php");
            ?>

        <script src="../bootstrap/js/bootstrap.min.js"></script>

        <script type="text/javascript">

            <?php
                for ($i = 1; $i < $i_max; $i++){
                    echo '$("#select'.$i.'").change(function() {
                        $.ajax(
                        {
                            url : "../inc/changeAccessRight.php",
                            type : "GET",
                            data: {login: $(this).attr("data-login"), right: $("#select'.$i.' option:selected" ).val()},
                            dataType : "html",

                            success: function(data){
                                var output = jQuery.parseJSON(data);
                                    
                                if (output.success != 1) // Error
                                {
                                    alert(output.msg);
                                }
                            }
                            
                        });
                    });';
                }
            ?>

        </script>
    </body>
</html>
        
   