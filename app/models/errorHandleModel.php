<?php

class errorHandleModel extends mainModel {
	private $errorsTable,
			$lastErrorID,
			$emailAddress = 'andreivalcu@gmail.com',
			$type,
			$place,
			$message,
			$sendEmail = false;

	function __construct() {
		parent::__construct();
		$this->errorsTable = config::get('database/prefix').'errors';
		// $this->add('Fatal', 'Feeds', 'Nu sa gasit campul...');
	}

	public function email($value) {
		$this->sendEmail = (bool)$value;
	}

	public function get() {
		$stx = $this->db->select($this->errorsTable, '*');
		return ($stx->getNumRows() > 0) ? (($stx->getNumRows() == 1) ? [$stx->results()] : $stx->results()) : false;
	}

	public function getByID($id) {
		$stx = $this->db->select($this->errorsTable, '*', $id);
		return ($stx->getNumRows() == 1) ? $stx->results() : false;
	}

	public function add($type, $place, $message) {
		if ($this->db->insert($this->errorsTable, ['type' => $type, 'place' => $place, 'message' => $message, 'setTime' => time()])) {
			$this->lastErrorID = $this->db->getLastInsertID();

			if ($this->sendEmail) {
				$this->type = $type;
				$this->place = $place;
				$this->message = $message;
				$this->sendEmail();
			}
		} else var_dump($this->db->getError());// return false;
	}

	private function sendEmail() {
		$title = 'Eroare '.$this->type.' la '.$this->place.'. ID: '.$this->lastErrorID;
		$body = '<p>'.$this->message.'</p><p>Da <a href="'.config::get('site/domain').'errorHandle/show/'.$this->lastErrorID.'">aici</a> pentru a vedea eroarea.</p>';
		$mail = new email();

		$mail->setTo($this->emailAddress);
		$mail->setFrom(config::get('site/name').' <no-reply@'.str_replace(array('/', 'http:'), '', config::get('site/domain')).'>');
		$mail->setSubject($title);
		$mail->setBody($title, $body);

		return $mail->send();
	}

	public function delete($id)	{
		return $this->db->delete($this->errorsTable, $id);
	}
}