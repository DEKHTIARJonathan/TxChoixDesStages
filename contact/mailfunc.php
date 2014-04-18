<?php

function decode_lwsp (&$in, $return_decalage = false) {
	$inptr = $in;

	$i = 0;
	while (strlen($inptr) > 0 && ($inptr{0} == '(' || is_lwsp($inptr{0}))) {
		while (strlen($inptr) > 0 && is_lwsp($inptr{0})) {
			$inptr = substr($inptr, 1);
			$i++;
		}

		if (strlen($inptr) > 0 && $inptr{0} == '(') {
			$depth = 1;

			$inptr = substr($inptr, 1);
			$i++;

			while (strlen($inptr) > 0 && $depth != 0) {
			if ($inptr{0} == '\\' && strlen($inptr) > 1) {
				$inptr = substr($inptr, 1);
				$i++;
			}
			else if ($inptr{0} == '(')
				$depth++;
			else if ($inptr{0} == ')')
				$depth--;

			$inptr = substr($inptr, 1);
			$i++;
			}
		}
	}

	$in = $inptr;

	if ($return_decalage) return $i;
}

function is_lwsp($char) {
	$string = " \t\n\r";
	if (strstr($string, $char) != false) {
		return true;
	}
	return false;
}

function decode_quoted_string(&$in) {
	$inptr = $in;
	$out = false;

	decode_lwsp ($inptr);

	if (strlen($inptr)>0 && $inptr{0} == '"') {
		$out = $inptr;

		$inptr = substr($inptr, 1);

		$i = 1;
		while (strlen($inptr)>0 && $inptr{0} != '"') {
			if (strlen($inptr)>0 && $inptr{0} == '\\') {
				$inptr = substr($inptr, 1);
				$i++;
			}

			if (strlen($inptr)>0) {
				$inptr = substr($inptr, 1);
				$i++;
			}
		}

		if (strlen($inptr)>0 && $inptr{0} == '"') {
			$inptr = substr($inptr, 1);
		$i++;
		}

		$out = substr($out, 0, $i);
	}

	$in = $inptr;

	return $out;
}


function decode_atom(&$in) {
	$inptr = $in;

	decode_lwsp($inptr);

	$start = $inptr;

	$i = 0;
	while (strlen($inptr) > 0 && is_atom($inptr{0})) {
		$inptr = substr($inptr, 1);
		$i++;
	}
	$in = $inptr;
	if ($i > 0) {
		return substr($start, 0, $i);
	}
	else {
		return false;
	}
}

function is_atom ($char) {
	$string = "()<>@,;:\\\".[] ";

	if (strstr($string, $char) != false) {
		return false;
	}

	if (ord($char) < 32 || ord($char) == 127) {
		return false;
	}

	return true;
}


function decode_word(&$in) {
	$inptr = $in;

	decode_lwsp($inptr);

	if (strlen($inptr) > 0 && $inptr{0} == '"') {
		$in = $inptr;
		return decode_quoted_string($in);
	}
	else {
		$in = $inptr;
		return decode_atom($in);
	}
}


function decode_subliteral (&$in, &$domain) {
	$inptr = $in;
	$got = false;

	while (strlen($inptr)>0 && $inptr{0} != '.' && $inptr{0} != ']') {
		if (is_dtext($inptr{0})) {
			$domain .= $inptr{0};
			$inptr = substr($inptr, 1);
			$got = true;
		}
		elseif (is_lwsp ($inptr{0})) {
			decode_lwsp ($inptr);
		}
		else break;
	}

	$in = $inptr;

	return $got;
}

function is_dtext($char) {
	$string = "[]\\\r \t";

	$return = false;
	if (strstr($string, $char)) {
		$return = true;
	}

	return $return;
}


function decode_domain_literal (&$in, &$domain) {
	$inptr = $in;

	decode_lwsp ($inptr);
	while (strlen($inptr)>0 && $inptr{0} != ']') {
		if (decode_subliteral ($inptr, $domain) && $inptr{0} == '.') {
			$domain .= $inptr{0};
			$inptr = substr($inptr, 1);
		}
		elseif (strlen($inptr)>0 && $inptr{0} != ']') {
			$inptr = substr($inptr, 1);
		}
	}

	$in = $inptr;
}

function decode_domain (&$in) {

	$domain = "";
	$atom = "";
	$inptr = $in;

	while ($inptr != false && strlen($inptr)>0) {
		decode_lwsp ($inptr);
		if ($inptr{0} == '[') {
			$domain .= '[';
			$inptr = substr($inptr, 1);

			decode_domain_literal ($inptr, $domain);

			if ($inptr{0} == ']') {
				$domain .= ']';
				$inptr = substr($inptr, 1);
			}
			else {
				// warning !!!
			}
		}
		else {
			if (!($atom = decode_atom ($inptr))) {
				// warning !!!
				if ($domain{strlen($domain)-1} == '.')
					$domain = substr($domain, 0, -1);
				break;
			}

			$domain .= $atom;
			$atom = false;
		}

		$save = $inptr;
		decode_lwsp ($inptr);
		if ($inptr{0} != '.') {
			$inptr = $save;
			break;
		}

		$domain .= '.';

		$inptr = substr($inptr, 1);
	}

	if (strlen($domain)>0)
		$dom = $domain;
	else
		$dom = false;

	//g_string_free (domain, dom ? FALSE : TRUE);

	$in = $inptr;

	return $dom;
}



function decode_addrspec(&$in) {
	$domain = "";
	$word = "";
	$str = false;
	$addrspec = "";

	decode_lwsp ($in);
	$inptr = $in;

	if (!$word = decode_word($inptr)) {
		// warning !!!
		return false;
	}

	$addrspec = $word;
	decode_lwsp ($inptr);

	while ($inptr{0} == '.') {
		$addrspec = $inptr{0};
		$inptr = substr($inptr, 1);

		$word = decode_word ($inptr);

		if ($word) {
			$addrspec .= $word;
			decode_lwsp ($inptr);
		}
		else {
			// warning goto exception
			return false;
		}
	}

	if ($inptr{0} != '@') {
		$inptr = substr($inptr, 1);
		// warning goto exception
		return false;
	}
	else {
		$inptr = substr($inptr, 1);
	}

	if (!($domain = decode_domain ($inptr))) {
		// warning goto exception
		return false;
	}

	$addrspec .= '@';
	$addrspec .= $domain;

	$str = $addrspec;

	$in = $inptr;

	return $str;
}


function decode_mailbox(&$in) {


	$mailbox = false;
	$inptr = "";
	$bracket = false;
	$name = false;
	$addr = "";

	$pre = "";

	decode_lwsp ($in);

	$inptr = $in;

	$pre = decode_word($inptr);
	$k = decode_lwsp ($inptr, true);

	if (strlen($inptr)>0 && !strchr (",.@", $inptr{0})) {

		$retried = false;
		$name = "";

		do {
			while ($pre != false || $retried) {
				if (!$retried) {
					$retried = FALSE;
					$name .= $pre;
					$pre = false;
				}

				$retried = false;
				$pre = decode_word($inptr);

				if ($pre != false) $name .= ' ';
			}

			decode_lwsp ($inptr);

			if (strlen($inptr) > 0 && $inptr{0} == '<') {
				$inptr = substr($inptr, 1);
				$bracket = true;
				$pre = decode_word ($inptr);
			}
			else if (!$retried && strlen($inptr)>0) {
				$name .= $inptr{0};
				$inptr = substr($inptr, 1);
				$retried = TRUE;
			}
			else {
				$in = $inptr;
				$name = false;
				$addr = false;
				return false;
			}
		}
		while ($retried);
	}

	if ($pre != false) {
		$addr .= $pre;
	}
	else {
		if ($name != false) {
			$name = false;
			$addr = false;
			$in = substr($inptr, 1);
		}
		return false;
	}

	decode_lwsp ($inptr);
	while (strlen($inptr) > 0 && $inptr{0} == '.' && $pre != false) {
		$inptr = substr($inptr, 1);
		$pre = decode_word ($inptr);
		if ($pre != false) {
			$addr .= ".$pre";
		}
		decode_lwsp ($inptr);
	}
	$pre = false;

	if (strlen($inptr) > 0 && $inptr{0} == '@') {
		$inptr = substr($inptr, 1);

		$domain = decode_domain ($inptr);
		if ($domain != false) {
			$addr .= "@$domain";
		}
		$domain = false;
	}

	if ($bracket) {
		decode_lwsp ($inptr);
		if (strlen($inptr) > 0 && $inptr{0} == '>') {
			$inptr = substr($inptr, 1);
		}
	}

	if ($name == false || strlen($name)==0) {

		if ($name != false) {
			$name = false;
		}

		$comment = $inptr;
		$offset = decode_lwsp ($inptr, true);

		if ($offset > 0) {
			$offset2 = strpos($comment, '(', substr($comment, 0, $offset));

			if ($offset2 > 0) {
				$comment = substr($comment, $offset2, $offset - $offset2);
				$name = "PADBOL";
				if (preg_match("/([^) \t\n\r]*[) \t\n\r]*/", $comment, $ret)) {
					$name = $ret[1];
				}
				$comment = false;
			}

		}
	}

	$in = $inptr;

	$address = array("name" => ($name ? $name : ""), "addr" => ($addr ? $addr : ""));

	return $address;
}


function decode_address (&$in) {

	$addr = false;
	$pre = $start = $name = $inptr = "";

	decode_lwsp ($in);

	$start = $inptr = $in;

	$pre = decode_word ($inptr);

	while ($pre != false) {
		$name .= $pre;
		$pre = false;

		$pre = decode_word ($inptr);
		if ($pre != false)
			$name .= ' ';
	}

	decode_lwsp ($inptr);

	$addr = decode_mailbox ($in);

	return $addr;
}


function mb_formEmail($email) {
		if(eregi("(^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$)", $email))
			return 1;
		else
			return 0;
}

function internet_address_parse_string ($string) {

	$inptr = $string;

	$nb_mail_in = 0;
	$nb_mail_out = 0;

	while ($inptr != false && strlen($inptr)>0) {
		$start = $inptr;

		$nb_mail_in++;
		$addr = decode_address ($inptr);

		if ($addr != false) {
			if (mb_formEmail($addr['addr'])) {
				$nb_mail_out++;
				$addrlist[] = $addr;
			}
		}

		decode_lwsp ($inptr);
		if (strlen($inptr)>0 && ($inptr{0} == ',' || $inptr{0} == ';')) {
			$inptr = substr($inptr, 1);

		}
		else if (strlen($inptr)>0) {
			$inptr = strchr ($inptr, ',');

			if ($inptr != false) {
				$inptr = substr($inptr, 1);
			}
		}
	}

	if ($nb_mail_out>0) {
		$retour = (object) NULL;
		$retour->diff = $nb_mail_in - $nb_mail_out;
		$retour->addrlist = $addrlist;
		return $retour;
	}
	else return false;
}

function br2nl($text) {    
    return  preg_replace('/<br\\s*?\/??>/i', '<br />', $text);
}

?>