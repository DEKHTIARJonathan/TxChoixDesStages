<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once $root.'/config.inc.php';
    
    session_start();
    session_destroy();
    session_start();
    $_SESSION['auth'] = Array("logged" => False, "login_utc" => "", "cas_url" => $_CONFIG['cas_url']);
    header('Location: https://cas.utc.fr/cas/logout?url='.$_CONFIG['home']);

?>
