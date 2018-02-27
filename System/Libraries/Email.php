<?php
namespace Storemaker\System\Libraries;

class Email {
	private $from = '';
	private $to = '';
	private $subject = '';
	private $message = '';
	private $attachment = '';
	private $attachment_filename = '';

	public function setTo($value) {
		$this -> to = $value;
	}

	public function setFrom($value) {
		$this -> from = $value;
	}

	public function setSubject($value) {
		$this -> subject = $value;
	}

	public function setBody($title, $value) {
		include_once config::get('path/appViews').'tamplate/styles/'.config::get('site/style').'/mail.php';
		$this -> message = $body;
	}

	public function getBody() {
		return $this->message;
	}

	public function setAttachment($file, $fileName = NULL) {
		$this -> attachment = $file;

		if ($fileName !=NULL) {
			$this -> attachment_filename = $fileName;
		}
	}

	public function send() {
		if (!empty($this -> attachment)) {
			$filename = empty($this -> attachment_filename) ? basename($this -> attachment) : $this -> attachment_filename ;
			$mailto = $this -> to;
			$from_mail = $this -> from;
			$subject = $this -> subject;
			$message = $this -> message;

			$attFile = $this -> attachment;

			$content = file_get_contents($attFile);
			$content = chunk_split(base64_encode($content));
			$uid = md5(uniqid(time()));
			$name = basename($file);
			$header = "From: ".$from_mail."\r\n";
			$header .= "Reply-To: ".$replyto."\r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
			$header .= "This is a multi-part message in MIME format.\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
			$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$header .= $message."\r\n\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use diff. tyoes here
			$header .= "Content-Transfer-Encoding: base64\r\n";
			$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
			$header .= $content."\r\n\r\n";
			$header .= "--".$uid."--";

			return (mail($mailto, $subject, "", $header)) ? true : false;
		} else {
			$header = "From: ".$this -> from."\r\n";
			$header .= "Content-type:text/html; charset=iso-8859-1\r\n";

			return (mail($this -> to, $this -> subject, $this -> message, $header)) ? true : false;
		}
	}
}

?>