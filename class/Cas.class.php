<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once $root.'/class/xmlToArrayParser.class.php';
require_once $root.'/config.inc.php';

class Cas {

	public static function authenticate($ticket,$service) {
		global $_CONFIG;
		$url_validate = $_CONFIG['cas_url']."serviceValidate?service=".$service."&ticket=".$ticket;
		$data = file_get_contents($url_validate);
		if(empty($data)) return -1;

		$parsed = new xmlToArrayParser($data);
		if(isset($parsed->array['cas:serviceResponse']['cas:authenticationSuccess']['cas:user'])) 
			return $parsed->array['cas:serviceResponse']['cas:authenticationSuccess']['cas:user']; 
		else
			return -1;
	}

	public static function getURl() {
		global $_CONFIG;
		return $_CONFIG['cas_url'];
	}

}
?>