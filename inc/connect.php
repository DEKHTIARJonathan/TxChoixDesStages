<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once $root.'/inc/Auth.php';

    session_start();
    register_login(); // Connection au CAS
    
    if (!isset($_SESSION['auth']['logged']) || !$_SESSION['auth']['logged'])
        header('Location: '.$_CONFIG['cas_url'].'login?service='.$_CONFIG['service']);
    else {
    	$_SESSION['login'] = $_SESSION['auth']["login_utc"];
    	header('Location: '.$_CONFIG['home']);
    }
    
?>