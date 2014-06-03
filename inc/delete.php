<?php
    // Page formulaire de modification du compte utilisateur: /myaccount.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/dbconnect.php';

    $login = $_SESSION["auth"]["login_utc"];

    // initialisation pour les var. globales.
    $rslt = true;
    $msg = true;

    $stmt = $connexion->prepare("DELETE FROM `stagestx`.`users` WHERE `users`.`casLogin` = :login");
    $stmt->bindParam(':login', $login);
    
    if($stmt->execute()){
        $rslt = 1;
        $msg = "Compte supprimé avec succès";
    }
    else{
        $rslt = 0;
        $msg = "Problème lors de la suppression";
    }

    $arr = array('success' => $rslt, 'msg' => $msg);
    echo json_encode($arr);

?>  