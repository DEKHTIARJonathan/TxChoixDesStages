<?php
    /*
        API.PHP
    */
    require_once 'config.inc.php';
    require_once 'inc/Auth.php';


    $fnction = $_GET["function"];
    switch ($function)
    {
        /* AUTH FUNCTIONS */
        case "is_logged":
            $output = is_logged();
            break;
        case "register_login":
            $output = register_login();
            break;
        case "logout":
            $output = logout();
            break;

        /* DEFAULT */
        default:
            $output = Array(
                        "error"=> Array(
                            "title" => "Oups une erreur à eu lieu.", 
                            "content" => "Cette fonction n'existe pas..."
                        ));
    }

    // Set header for serving json
    header('Cache-Control: no-cache, must-revalidate');
    header("Pragma: no-cache");
    header('Content-type: application/json');
    // and serve the output
    echo json_encode($output);
?>



