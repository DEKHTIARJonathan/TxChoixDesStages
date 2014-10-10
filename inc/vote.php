<?php
	// Page de voeux : /voeux/index.php
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/dbconnect.php';
 
    if (!isset($_GET["stageID"]) || $_GET["stageID"] == "" || !isset($_GET["note"]) || $_GET["note"] == "" )
    {
    	echo "<h1>Problème avec le paramètre</h1>";
    	die();
    }
    else
    {
    	$stage = $_GET["stageID"];
    	$note = $_GET["note"];
    	$login = $_SESSION['login'];

        $rslt = true;

        if ($note > 0){
            $stmt = $connexion->prepare("INSERT INTO votes (login, stage, note, voteDate) VALUES (:login, :stage, :note , NOW()) ON DUPLICATE KEY UPDATE note = :note");
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':stage', $stage);
            $stmt->bindParam(':note', $note);

            $rslt = $stmt->execute();

        } else {
            $stmt = $connexion->prepare("DELETE FROM `votes` WHERE `votes`.`login` = :login AND `votes`.`stage` = :stage");
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':stage', $stage);

            $rslt = $stmt->execute();
        }

    	
		echo $rslt;

    }
?>