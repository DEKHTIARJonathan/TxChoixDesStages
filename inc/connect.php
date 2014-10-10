<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/inc/Auth.php'; 
    
    session_start();
    register_login(); // Connection au CAS
    
    if (!$_SESSION['auth']['logged'])
        header('Location: '.$_CONFIG['cas_url'].'login?service='.$_CONFIG['service']);
    else {
    	$_SESSION['login'] = $_SESSION['auth']["login_utc"];
    	header('Location: '.$_CONFIG['home']);
    }
    

?>