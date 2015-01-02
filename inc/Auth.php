<?php
    /*
        Auth.php
    */
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/class/Cas.class.php';
    require_once $root.'/config.inc.php';

    if(!isset($_SESSION['auth']))
    {
        $_SESSION['auth'] = Array("logged" => False, "login_utc" => "", "cas_url" => Cas::getUrl());
    }


    function register_login() {        
        global $_CONFIG; // Déclaration de la variable étant globale
        
        session_destroy();
        session_start();
        
        if (!isset($_GET["ticket"])){
        	header('Location: '.$_CONFIG['cas_url'].'login?service='.$_CONFIG['service']);
        }
        else{
			$ticket = $_GET["ticket"];
			$service = $_CONFIG['service'];
			$login = Cas::authenticate($ticket, $service);
		
			if($login == -1) {
				$_SESSION['auth'] = Array("logged" => False, "login_utc" => "", "cas_url" => Cas::getUrl());
				echo $_CONFIG['cas_url'].'login?service='.$_CONFIG['service'];
			} else {
				$_SESSION['auth'] = Array("logged" => True, "login_utc" => $login, "cas_url" => Cas::getUrl());
			}
		}
    }
?>