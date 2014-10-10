<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
 	session_start();
	if( !isset($_SESSION['login']) || $_SESSION['login'] == '' ) {
    	
    	$currentAddr = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    	if ($currentAddr != $_CONFIG['home'])
    		header('Location: '.$_CONFIG['home']);
    }
?>