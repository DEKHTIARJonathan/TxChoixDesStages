<?php 


    // Page formulaire de modification du compte utilisateur: /myaccount.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkadmin.php';
    require_once $root.'/inc/dbconnect.php';

    $login = $_GET['login'];
    $right = $_GET['right'];

    $myLogin = $_SESSION["auth"]["login_utc"];

    // initialisation pour les var. globales.
    $rslt = true;
    $msg = true;

    if($myLogin == $login){
        $rslt = 0;
        $msg = "Il n'est pas possible de modifier votre propre compte";
    }
    else{

        $stmt = $connexion->prepare("UPDATE `users` SET `userRight` = :right WHERE `users`.`casLogin` = :login");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':right', $right);

        if($stmt->execute()){
            $rslt = 1;
            $msg = "Compte modifié avec succès";
        }
        else{
            $rslt = 0;
            $msg = "Problème lors de la modification, veuillez réessayer";
        }
    }

    $arr = array('success' => $rslt, 'msg' => $msg);
    echo json_encode($arr);

    function isAdmin($connexion, $login){
        $stmt = $connexion->prepare('SELECT `userRight` FROM `users` WHERE `casLogin` = :login');
        $stmt->bindParam(':login', $login);
        $stmt-> execute();
        $rslt = $stmt -> fetch();

        return $rslt['userRight'] == "administrateur";
    }

    function isLastAdmin($connexion){
        
        $stmt = $connexion->prepare('SELECT count(*) as nbrAdmin FROM `users` WHERE `userRight` = "administrateur"');
        $stmt-> execute();
        $rslt = $stmt -> fetch();

        return $rslt['nbrAdmin'] <= 1;
    }

?>  
