<?php
    require('../config.inc.php');
    
    $mysqli = new mysqli($_CONFIG['sql_host'], $_CONFIG['sql_user'], $_CONFIG['sql_pass'], $_CONFIG['sql_db']);
    
    /* Vérification de la connexion */
    if (mysqli_connect_errno()) {
        printf("Echec de la connexion : %s\n", mysqli_connect_error());
        exit();
    }
    
    $requete = file_get_contents("structure.sql");

    if ($mysqli->multi_query($requete)) 
    {
        $web = "<html>";
        $web .= "<head>";
        $web .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
        $web .= "</head>";
        $web .= "<body>";
        $web .= "<h1>La base a bien été installée</h1>";
        $web .= "</body>";
        $web .= "</html>";
        
        echo $web;

    }
    
    else
    
    {
        $web = "<html>";
        $web .= "<head>";
        $web .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
        $web .= "</head>";
        $web .= "<body>";
        $web .= "<h1>La base n'a pas été installée correctement</h1>";
        $web .= "</body>";
        $web .= "</html>";
        
        echo $web;

    }

    
    $mysqli->close();
?>