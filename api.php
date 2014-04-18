<?php
    /*
        API.PHP
    */
    require_once 'config.inc.php';
    require_once 'inc/Auth.php';

    function payer()
    {
        $mode = $_GET["mode"];
        if($mode == "cash") {
            return Array(
                        "success"=> Array(
                            "title" => "Achat réussi", 
                            "content" => "Les places ont été enregistrés, l'utilisateur va recevoir un mail de confirmation."
                        ));
        } else if ($mode == "payutc") {
            return Array(
                        "error"=> Array(
                            "title" => "Achat impossible", 
                            "content" => "Le mode de paiement \"payutc\" n'est pas encore disponible..."
                        ));
        } else {
            return Array(
                        "error"=> Array(
                            "title" => "Achat impossible", 
                            "content" => "Le mode de paiement \"$mode\" n'est pas reconnu..."
                        ));
        }
    }

    function info_badge()
    {
        $badge_id = $_GET["badge_id"];
        $place_cotisant = Array( 
                                "tarif_id" => 1,
                                "event_name" => "Estu de rentrée P13",
                                "tarif_name" => "Place cotisant",
                                "price" => 7.00,
                                "nb_max" => 1,
                                "nb_buyed" => 0 );
        $place_exterieur = Array( 
                                "tarif_id" => 2,
                                "event_name" => "Estu de rentrée P13",
                                "tarif_name" => "Place exterieur",
                                "price" => 9.00,
                                "nb_max" => 3,
                                "nb_buyed" => 1 );
        return Array( "places" => Array ($place_cotisant, $place_exterieur) );
    }

    $function = $_GET["function"];
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
        /* BILLETERIE FUNCTIONS */
        case "info_badge":
            $output = info_badge();
            break;
        case "payer":
            $output = payer();
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



