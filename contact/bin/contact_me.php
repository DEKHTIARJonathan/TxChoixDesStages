<?php
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/dbconnect.php';

    $stmt = $connexion->prepare('SELECT `email` FROM `users` WHERE `userRight` = "administrateur"');

    $stmt-> execute();
	$recipients = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

	$email_to = implode(', ', $recipients);

// check if fields passed are empty
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$message = $_POST['message'];
	
// create email body and send it	

$email_subject = "Contact form submitted by:  $name";
$email_body = "Vous avez reçu un message d'un suiveur depuis la plateforme de choix des stages.\n\n".
				"Here are the details:\n\n".
				"Name: $name \n ".
				"Email: $email_address\n\n".
				"Message: \n$message";
$headers = "From: noreply@utc.fr\n";
$headers .= "Reply-To: $email_address";	
mail($email_to,$email_subject,$email_body,$headers);
return true;			
?>