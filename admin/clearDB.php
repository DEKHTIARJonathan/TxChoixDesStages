<?php
	
	header("Content-Type: application/json; charset=UTF-8", true);
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
	require_once $root.'/inc/dbconnect.php';

	$msg = "";
	$error = "";

	try
	{
		//$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$connexion->beginTransaction(); 

		$connexion->exec('DELETE FROM `stagestx`.`stages`');
		if($connexion->errorCode() != 0) {
    		throw new Exception('Erreur dans le nettoyage des stages');
    	}
		$connexion->exec('ALTER TABLE `stagestx`.`stages` AUTO_INCREMENT = 1');
		if($connexion->errorCode() != 0) {
    		throw new Exception('Erreur lors de la réinitialisation du compteur de la table "stages"');
    	}
		$connexion->exec('ALTER TABLE `stagestx`.`votes` AUTO_INCREMENT = 1');
		if($connexion->errorCode() != 0) {
    		throw new Exception('Erreur lors de la réinitialisation du compteur de la table "votes"');
    	}
		$connexion->commit();

		$msg = "La base de données a été correctement nettoyée";
		
		
	}
	catch(Exception $e) //en cas d'erreur
	{
	    //on annule la transation
	    $connexion->rollback();

	    $msg ="";

	    //on affiche un message d'erreur ainsi que les erreurs
	    $error = 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
	    $error .= 'Erreur : '.$e->getMessage().'<br />';
	    //$error .= 'N° : '.$e->getCode();


	}

	$arr = array('error' => utf8_encode($error), 'msg' => utf8_encode($msg));
	echo json_encode($arr);

?>