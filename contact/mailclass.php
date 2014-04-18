<?php
/* ######################################################################################### */
/*    DECODE DES SUJETS DE MAILS (AVEC DES ?ISO=..                                           */
/* ######################################################################################### */


function encodeHeader($input) {
	return mb_encode_mimeheader($input, "UTF-8", "B");
}

function decodeHeader($header) {
	return mb_decode_mimeheader($header);
}

function decodeSubject($string) {

	$elements = imap_mime_header_decode($string);

	$out = '';
	for($i=0; $i<count($elements); $i++) {
		if ($elements[$i]->charset == 'default' || $elements[$i]->charset == 'UNKNOWN') {
			$out .= $elements[$i]->text;
		}
		else {
			$out .= iconv($elements[$i]->charset, 'UTF-8//TRANSLIT', $elements[$i]->text);
		}
	}

	return $out;
}

function decodeBodyMail ($body, $charset) {
	restore_error_handler();
	error_reporting(0);

	if ($charset == NULL) {
		if ($body != @iconv("ISO-8859-15", "UTF-8//TRANSLIT", $body)) $body = @iconv("ISO-8859-15", "UTF-8//IGNORE", $body);
		else $body = @iconv("ISO-8859-15", "UTF-8//TRANSLIT", $body);
	}
	else {
		if ($body != @iconv($charset, "UTF-8//TRANSLIT", $body)) $body = @iconv($charset, "UTF-8//IGNORE", $body);
		else $body = @iconv($charset, "UTF-8//TRANSLIT", $body);

	}

	$error_handler = set_error_handler("userErrorHandler");

	return $body;
}

/* ######################################################################################### */
/*    CLASSE QUI FORGE LES MAILS                                                             */
/* ######################################################################################### */

function clean_input ($string) {
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	$string = str_replace('"', '', $string);
	$string = str_replace("\x03", '', $string);

	return $string;
}

class send_mail {

	var $headers = '';
	var $importance = false;
	var $subject = null;

	var $from = '';
	var $Tfrom = array();
	var $reply_to = '';
	var $Treply_to = array();
	var $to = '';
	var $Tto = array();
	var $cc = '';
	var $Tcc = array();
	var $cci = '';
	var $Tcci = array();

	var $body = false;
	var $files = array();
	var $size = 0;
	var $error = array();

	function send_mail () {
		return true;
	}

	function addSubject ($sujet) {
		if (strlen($sujet)>255) {
			$this->error[] = 'Le sujet du mail fait plus que 255 caractères';
			return false;
		}

		$this->subject = $sujet;

		return true;
	}

	function addFrom ($from) {
		$from = clean_input($from);
		$temp_from = $from;

		// on fait passer l'adresse email dans le test de la mort
		$from = internet_address_parse_string($from);

		if (!$from || $from->diff > 0) {
			$this->error[] = 'L\'adresse du champ FROM est invalide';
			return false;
		}

		if (strlen($from->addrlist[0]['name']) == 0) {
			$this->from = $from->addrlist[0]['addr'];
		}
		else {
			$this->from = encodeHeader($from->addrlist[0]['name']).' <'.$from->addrlist[0]['addr'].'>';
		}

		$this->Tfrom[] = array('name' => str_replace('"', ' ', $from->addrlist[0]['name']), 'addr' => $from->addrlist[0]['addr']);

		return true;
	}

	function addReplyTo ($reply_to) {
		$reply_to = clean_input($reply_to);
		$temp_reply_to = $reply_to;

		// on fait passer l'adresse email dans le test de la mort
		$reply_to = internet_address_parse_string($reply_to);

		if (!$reply_to || $reply_to->diff > 0) {
			$this->error[] = 'L\'adresse du champ REPLYTO est invalide';
			return false;
		}

		if (strlen($reply_to->addrlist[0]['name']) == 0) {
			$this->reply_to = $reply_to->addrlist[0]['addr'];
		}
		else {
			$this->reply_to = encodeHeader($reply_to->addrlist[0]['name']).' <'.$reply_to->addrlist[0]['addr'].'>';
		}

		// on set un tableau qui va bien pour emdb
		$this->Treply_to[] = array('name' => $reply_to->addrlist[0]['name'], 'addr' => $reply_to->addrlist[0]['addr']);
		return true;
	}

	function addTo ($to, $qui = 'to') {
		$to = clean_input($to);
		$temp_to = $to;

		if (strlen($temp_to)>0) {
			$to = internet_address_parse_string($to);

			switch ($qui) {
				case 'to':
					if (!$to || $to->diff > 0) {
						$this->error[] = 'L\'adresse du champ TO est invalide';
						return false;
					}
					if (isset($to->addrlist) && is_array($to->addrlist)) {
						foreach ($to->addrlist AS $email) {
							if (strlen($email['name']) == 0) {
								$this->to = $this->to.$email['addr'].', ';
							}
							else {
								$this->to = $this->to.encodeHeader($email['name']).' <'.$email['addr'].'>, ';
							}

							$this->Tto[] = array('name' => $email['name'], 'addr' => $email['addr']);
						}
						$this->to = substr($this->to, 0, -2);
					}
					break;

				case 'cc':
					if (!$to || $to->diff > 0) {
						$this->error[] = 'L\'adresse du champ CC est invalide';
						return false;
					}
					if (isset($to->addrlist) && is_array($to->addrlist)) {
						foreach ($to->addrlist AS $email) {
							if (strlen($email['name']) == 0) {
								$this->cc = $this->cc.$email['addr'].', ';
							}
							else {
								$this->cc = $this->cc.encodeHeader($email['name']).' <'.$email['addr'].'>, ';
							}

							$this->Tcc[] = array('name' => $email['name'], 'addr' => $email['addr']);
						}
						$this->cc = substr($this->cc, 0, -2);
					}
					break;

				case 'cci':
					if (!$to || $to->diff > 0) {
						$this->error[] = 'L\'adresse du champ CCI est invalide';
						return false;
					}
					if (isset($to->addrlist) && is_array($to->addrlist)) {
						foreach ($to->addrlist AS $email) {
							if (strlen($email['name']) == 0) {
								$this->cci = $this->cci.$email['addr'].', ';
							}
							else {
								$this->cci = $this->cci.encodeHeader($email['name']).' <'.$email['addr'].'>, ';
							}

							$this->Tcci[] = array('name' => $email['name'], 'addr' => $email['addr']);
						}
						$this->cci = substr($this->cci, 0, -2);
					}
					break;
			}
		}

		return true;
	}

	function addContent($content, $type) {

			if ($type == 'text') {
				$this->text = $content;
			}
			elseif ($type == 'html') {
				$this->html = $content;
			}
			else {
				$this->error[] = 'Le contenu du mail n\'est ni du texte ni de l\'html';
				return false;
			}
			return true;

	}

	function addFile ($nom, $size, $type, $data, $cid = '') {
		$tmp = (object) NULL;
		$tmp->nom = encodeHeader($nom);
		$tmp->size = $size;
		$tmp->type = $type;
		$tmp->file = $data;
		$tmp->cid = $cid;

		if ($cid != '') $this->inline = true;
		else $this->attachments = true;

		$this->files[] = $tmp;
	}

	function boundary () {
		return '--------------'.md5(microtime()*mt_rand(0, 999));
	}

	function importance ($imp = 'Normal') {
		if (!( ($imp=='Normal') || ($imp=='High') || ($imp=='Low') )) $imp = 'Normal';
		if ($imp=="Normal") $this->importance = "X-Priority: 3 (Normal)\nImportance: " . $imp;
		if ($imp=="High") $this->importance = "X-Priority: 1 (Highest)\nImportance: " . $imp;
		if ($imp=="Low") $this->importance = "X-Priority: 5 (Lowest)\nImportance: " . $imp;

		return true;
	}

	function headers () {
		$this->headers .= "User-Agent: Le PHP Facile Mailer\n";

		return true;
	}

	function checkIntegrityMail() {
		if (count($this->error) == 0) {
			if (count($this->Tcci) == 0 && count($this->Tcc) == 0 && count($this->Tto) == 0) {
				$this->error[] = 'Le mail ne contient aucun destinataire';
				return false;
			}
		}
		return true;
	}

	function build_mail () {
		$ret = false;

		$this->importance();
		$this->headers();

		if ( ($this->importance != '') && (strlen($this->from)>0) ) {

			// headers
			$this->body .= $this->headers;
			// priorité
			$this->body .= $this->importance . "\n";

			$this->body .= "Date: ".date('r', time())."\n";

			$this->body .= "From: " . $this->from . "\n";

			// on ajoute les To, Cc, Cci:
			if (strlen($this->to)>0) {
				$this->body .= "To: ".$this->to."\n";
			}

			if (strlen($this->cc)>0) {
				$this->body .= "Cc: ".$this->cc."\n";
			}

			if (strlen($this->cci)>0) {
				$this->body .= "Bcc: ".$this->cci."\n";
			}

			if (strlen($this->reply_to) != 0) {
				$this->body .= "Reply-To: " . $this->reply_to . "\n";
			}

			if (strlen($this->subject)>0) $this->body .= 'Subject: '.encodeHeader($this->subject) ."\n";
			else $this->body .= "";

			// qu a t on en fait a envoyer ?
			$this->formatTexte = false;
			$this->formatHTML = false;

			if (isset($this->text)) {
				// que du texte
				$this->formatTexte = true;
				$this->text .= "\n\n";
			}
			else {
				// on avait une partie html : on genere la partie texte : dans ce cas, on a deux parties
				$this->text = str_replace( "</p>", "\n", $this->html);
				$this->text = strip_tags(br2nl($this->text));
				$this->text = html_entity_decode($this->text , ENT_QUOTES, 'UTF-8');

				$this->formatHTML = true;
			}

			if (isset($this->html)) {
				$this->formatHTML = $this->formatTexte = true;
			}

			$this->body .= "MIME-Version: 1.0\n";

			// on genere un boundary general
			$boundary_general = $this->boundary();

			$is_mail_complete = false;
			if (!$this->formatHTML && !isset($this->attachments)) {
				// que du texte : aucun multipart
				$this->body .= "Content-Type: text/plain; charset=\"utf-8\"\n";
				$this->body .= "Content-Transfer-Encoding: base64\n";
				$this->body .= "\n";
				$this->body .= chunk_split(base64_encode($this->text));

				$is_mail_complete = true;
			}
			elseif (!$this->formatHTML && isset($this->attachments) && $this->attachments) {
				// texte + attachement : mixed
				$this->body .= "Content-Type: multipart/mixed;\n";
			}
			elseif ($this->formatHTML && !isset($this->attachments)) {
				// texte + html : alternative
				$this->body .= "Content-Type: multipart/alternative;\n";
			}
			elseif ($this->formatHTML && isset($this->attachments) && $this->attachments) {
				// texte + html + attachement : mixed
				$this->body .= "Content-Type: multipart/mixed;\n";
			}

			if (!$is_mail_complete) {
				// on blute la fin de l'entete générale
				$this->body .= " boundary=\"".$boundary_general."\"\n";
				$this->body .= "\n";
				$this->body .= "This is a multi-part message in MIME format.\n\n";
				$this->body .= "--".$boundary_general."\n";

				// Si on a des pj, on annonce la partie texte et la partie html avec un multipart/alternative
				if (isset($this->attachments) && $this->attachments) {
					// on blute une partie alternative
					$boundary_alternative = $this->boundary();

					$this->body .= "Content-Type: multipart/alternative;\n";
					$this->body .= " boundary=\"".$boundary_alternative."\"\n";
					$this->body .= "\n";
					$this->body .= "\n";
					$this->body .= "--".$boundary_alternative."\n";
				}

				// on blute la partie texte
				$this->body .= "Content-Type: text/plain; charset=\"utf-8\"\n";
				$this->body .= "Content-Transfer-Encoding: base64\n";
				$this->body .= "\n";
				$this->body .= chunk_split(base64_encode($this->text));
				$this->body .= "\n";

				if (isset($boundary_alternative)) {
					$this->body .= "--".$boundary_alternative."\n";
				}
				else {
					$this->body .= "--".$boundary_general."\n";
				}

				// Si on a du inline, on blute du related
				if (isset($this->inline) && $this->inline) {
					// on se genere un boundary

					$boundary_related = $this->boundary();

					$this->body .= "Content-Type: multipart/related;\n";
					$this->body .= " boundary=\"".$boundary_related."\"\n";
					$this->body .= "\n";
					$this->body .= "--".$boundary_related."\n";
				}

				// on blute la partie html
				$this->body .= "Content-Type: text/html; charset=\"utf-8\"\n";
				$this->body .= "Content-Transfer-Encoding: base64\n";
				$this->body .= "\n";
				$this->body .= chunk_split(base64_encode($this->html))."\n";

				// Si on a du inline, on blute les images inline
				if (isset($this->inline) && $this->inline) {
					for ($a=0; $a<count($this->files); $a++) {
						if ($this->files[$a]->cid != '') {
							$this->body .= "--".$boundary_related."\n";
							$this->body .= "Content-Type: " . $this->files[$a]->type . "\n";
							$this->body .= ' name="' . $this->files[$a]->nom . '"' . "\n";
							$this->body .= "Content-Transfer-Encoding: base64\n";
							$this->body .= "Content-ID: <".$this->files[$a]->cid.">\n";
							$this->body .= "Content-Disposition: inline;\n";
							$this->body .= ' filename="' . $this->files[$a]->nom . '"' . "\n" ;
							$this->body .= "\n";
							$this->body .= chunk_split( base64_encode( $this->files[$a]->file));
							$this->body .= "\n";
						}
					}
					// On cloture la partie INLINE
					$this->body .= "--".$boundary_related."--\n\n";
				}

				if (isset($boundary_alternative)) {
					$this->body .= "--".$boundary_alternative."--\n";
				}
				elseif (!isset($this->attachments)) {
					$this->body .= "--".$boundary_general."--\n\n";
				}

				// pieces jointes
				if (isset($this->attachments) && $this->attachments) {
					for ($a=0; $a<count($this->files); $a++) {
						if ($this->files[$a]->cid == '') {
							$this->body .= "--".$boundary_general."\n";
							$this->body .= "Content-Type: " . $this->files[$a]->type . "\n";
							$this->body .= ' name="' . $this->files[$a]->nom . '"' . "\n";
							$this->body .= "Content-Transfer-Encoding: base64\n";
							$this->body .= "Content-Disposition: attachment;\n";
							$this->body .= ' filename="' . $this->files[$a]->nom . '"' . "\n" ;
							$this->body .= "\n";
							$this->body .= chunk_split( base64_encode( $this->files[$a]->file));
							$this->body .= "\n";
						}
					}

					// On cloture le mail
					$this->body .= "--".$boundary_general."--\n\n";
				}
			}

			// on efface tous les \r que le mec a pu balancer dans son mail
			$this->body = str_replace("\r", "", $this->body);

			// on calcule le nombre de \n
			$nb_backslash_n = substr_count($this->body, "\n");

			// on calcule la taille du mail en octets
			$this->size = strlen($this->body);
			$this->rfcsize = $this->size + $nb_backslash_n;

			$ret = true;
		}

		return $ret;
	}

	function send () {
		$ret = false;

		// et c'est parti !
		if ($handle = popen("/usr/sbin/sendmail -i -t ", "w")) {
			fputs($handle, $this->body);
			if (pclose($handle)==0) $ret = true;
		}

		return $ret;
	}
}
?>