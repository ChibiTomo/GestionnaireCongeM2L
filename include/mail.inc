<?php
  	function mail_conge_create($conge) {
		global $g_mail;

		$emp = $conge->getEmployee();
		$superieur = $emp->getSuperieur();

		$g_mail['conge'] = $conge;
		$g_mail['employee'] = $emp;
		$g_mail['superieur'] = $superieur;

		$subject = format_mail_subject("Creation d'une demande de congé");

		// to employee
		$msg_html = mail_get_html(ROOT_DIR . "/mail/conge_create.php");
		$msg = mail_build($msg_html, $emp->getValue('email'));
		mail($emp->getValue('email'), $subject, $msg["content"], $msg["header"]);

		// to superior
		if ($superieur) {
			$msg_html = mail_get_html(ROOT_DIR . "/mail/conge_create_sup.php");
			$msg = mail_build($msg_html, $superieur->getValue('email'));
			mail($superieur->getValue('email'), $subject, $msg["content"], $msg["header"]);
		}
  	}

  	function mail_conge_update($conge) {
		global $g_mail;

		$emp = $conge->getEmployee();
		$superieur = $emp->getSuperieur();

		$g_mail['conge'] = $conge;
		$g_mail['employee'] = $emp;
		$g_mail['superieur'] = $superieur;

		$subject = format_mail_subject("Mise à jour d'une demande de congé");

		$msg_html = mail_get_html(ROOT_DIR . "/mail/conge_update.php");
		$msg = mail_build($msg_html, $email);
		mail($emp->getValue('email'), $subject, $msg["content"], $msg["header"]);

		// to superior
		if ($superieur) {
			$msg_html = mail_get_html(ROOT_DIR . "/mail/conge_update_sup.php");
			$msg = mail_build($msg_html, $superieur->getValue('email'));
			mail($superieur->getValue('email'), $subject, $msg["content"], $msg["header"]);
		}
  	}

  	function mail_conge_delete($conge) {
		global $g_mail;

		$emp = $conge->getEmployee();
		$email = $emp->getValue('email');

		$g_mail['conge'] = $conge;
		$g_mail['employee'] = $emp;

		$msg_html = mail_get_html(ROOT_DIR . "/mail/conge_delete.php");
		$msg = mail_build($msg_html, $email);
		$subject = format_mail_subject("Suppression d'une demande de congé");
		mail($email, $subject, $msg["content"], $msg["header"]);
  	}

  	function mail_employee_update($emp) {
		global $g_mail;

		$email = $emp->getValue('email');

		$g_mail['employee'] = $emp;

		$msg_html = mail_get_html(ROOT_DIR . "/mail/employee_update.php");
		$msg = mail_build($msg_html, $email);
		$subject = format_mail_subject("Mise à jour de vos informations personelles");
		mail($email, $subject, $msg["content"], $msg["header"]);
  	}

	function mail_get_html($filename) {
		global $g_mail;
		ob_start();
		include(ROOT_DIR . "/mail/header.php");
		include($filename);
		include(ROOT_DIR . "/mail/footer.php");
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

  	function mail_build($msg_html, $mail = "") {
  		debug($mail);
  		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)){ //Mise aux normes
			$passage_ligne = "\r\n";
		} else {
			$passage_ligne = "\n";
		}

		$msg_html = str_replace("<br/>", "<br/>".$passage_ligne, $msg_html);

  		$msg_txt = html_entity_decode(strip_tags($msg_html));
		$boundary = '----='.md5(rand());
		$boundary_alt = "-----=".md5(rand());

		/*CONTENU DU MAIL*/
		$content = $passage_ligne.'--'.$boundary.$passage_ligne;
		$content.= 'Content-Type: multipart/alternative;'.$passage_ligne.' boundary="'.$boundary_alt.'"'.$passage_ligne;
		$content.= $passage_ligne.'--'.$boundary_alt.$passage_ligne;

		//Message texte
		$content.= 'Content-Type: text/plain; charset="utf-8"'.$passage_ligne;
		$content.= 'Content-Transfer-Encoding: 8bit'.$passage_ligne;
		$content.= $passage_ligne.$msg_txt.$passage_ligne;

		//-----------------------------------------------------------
		$content.= $passage_ligne.'--'.$boundary_alt.$passage_ligne;

		//Message html
		$content.= 'Content-Type: text/html; charset=utf-8'.$passage_ligne;
		$content.= 'Content-Transfer-Encoding: 8bit'.$passage_ligne;
		$content.= $passage_ligne.$msg_html.$passage_ligne;

		//-----------------------------------------------------------
		$content.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
		$content.= $passage_ligne."--".$boundary."--".$passage_ligne;

		/*FORMATION DU HEADER*/
		$header = 'From: "'.CONTACT_MAIL.'" <'.CONTACT_MAIL.'>'.$passage_ligne;
		$header.= 'Bcc: "'.CONTACT_MAIL.'" <'.CONTACT_MAIL.'>'.$passage_ligne;
		$header.= 'MIME-Version: 1.0'.$passage_ligne;
		$header.= 'Content-Type: multipart/mixed;'.$passage_ligne.' boundary="'.$boundary.'"'.$passage_ligne;

		$msg = array(
			"header" => $header,
			"content" => $content,
		);

		return $msg;
  	}
?>