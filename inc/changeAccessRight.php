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

    $msg = true;

    // initialisation pour les var. globales.

    $stmt = $connexion->prepare("UPDATE `stagestx`.`users` SET `userRight` = :right WHERE `users`.`casLogin` = :login");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':right', $right);

    if($stmt->execute())
        echo true;
    else
        echo false;

?>  
