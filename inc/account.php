<?php
	// Page d'update du compte : /inc/account.php
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/dbconnect.php';

    // initialisation pour les var. globales.
    $rslt = true;
    $msg = true;
	
	if(!isset($_GET['login']) || $_GET['login'] == "" || !isset($_GET['email']) || $_GET['email'] == "" || !isset($_GET['lastname']) || $_GET['lastname'] == "" || !isset($_GET['firstname']) || $_GET['firstname'] == "" )
	{
		$rslt = 0;
		$msg = "Une ou plusieurs des variables ne sont pas correctement définies";
	}
	else {

		$email = strtolower($_GET['email']);

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    		$rslt = 0; 
    		$msg = "L'email n'est pas valide.";
		}
		else {

			$login = strtolower($_GET['login']);
			$lastname = strtoupper($_GET['lastname']);
			$firstname = ucfirst(strtolower($_GET['firstname'])); 

			$stmt = $connexion->prepare("REPLACE INTO  `stagestx`.`users` (`casLogin`, `firstName`, `lastName`, `email`) VALUES (:login, :firstname, :lastname, :email)");
			$stmt->bindParam(':login', $login);
			$stmt->bindParam(':firstname', $firstname);
			$stmt->bindParam(':lastname', $lastname);
			$stmt->bindParam(':email', $email);

			if($stmt->execute()){
				$rslt = 1;
				$msg = "Modification effectuée avec succès.";
			}
			else{
				$rslt = 0;
				$msg = "Problème lors de la mise à jour de la base de données.";
			}

			
		}
	}

	$arr = array('success' => $rslt, 'msg' => $msg);
	echo json_encode($arr);
?>