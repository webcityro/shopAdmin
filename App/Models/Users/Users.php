<?php
namespace Storemaker\App\Models\Users;
use Storemaker\System\Libraries\Model;
use Storemaker\App\Libraries\User;

class Users extends Model {
	private $userCode,
			$userID;

	function __construct() {
		parent::__construct();
	}

	public function get($id) {
		$stx = $this->db->select(User::getUsersTable(), '*', $id, '', 1);
		return ($stx->getNumRows() == 1) ? $stx->results() : false;
	}

	public function getAll() {
		$stx = $this->db->select(User::getUsersTable(), '*')->paginate(10);
		return ($stx->getNumRows() > 0) ? $stx : false;
	}

	public function checkExists($user) {
		if (ctype_digit($user)) {
			$field = 'id';
		} else {
			$field = (filter_var($user, FILTER_VALIDATE_EMAIL)) ? 'email' : 'userName';
		}

		$userQuery = $this->db->select(User::getUsersTable(), 'id', [$field, $user], '', 1);
		return ($userQuery->getNumRows() == 1) ? true : false;
	}

	public function insertUser() {
		do {
			$this->userCode = $this->generateUserCode();

			$query = $this->db->select(User::getUsersTable(), 'id', ['code', $this->userCode], '', 1);
			$codeCount = $query->getNumRows();
		} while ($codeCount == 1);

		$salt = hash::salt();

		$insertData = [
			'fName'	  => input::post('fName'),
			'lName'	  => input::post('lName'),
			'userName' => input::post('userName'),
			'password' => hash::make(input::post('password'), $salt),
			'salt'  	  => $salt,
			'email' 	  => input::post('email'),
			'sex'  	  => input::post('sex'),
			'code' 	  => $this->userCode,
			'groupID'  => input::post('groupID'),
			'active'   => input::post('status')
		];

		$insertUser = $this->db->insert(User::getUsersTable(), $insertData);

		if (!$insertUser->error()) {
			$id = $this->db->getLastInsertID();
			return $id;
		} else {
			// die(print_r($this->db->getError()));
			return false;
		}
	}

	public function createUserDir($userID) {
		return mkdir(config::get('path/users').$userID, 0777);
	}
	private function generateUserCode() {
		$charset = 'abcdefghijklmnopqrstuvwyxzABCDEFGHIJKLMNOPQRSTUVWYXZ0123456789`-=\\[];\',./~!@#$%&*()_+|{}:"<>?';
		return md5(str_shuffle($charset).microtime());
	}

	public function insertDummyUsers() {
		$faker = Faker\Factory::create();
		$faker->addProvider(new Faker\Provider\ro_RO\Person($faker));
		$sex = ['m', 'f'];

		for ($x=0; $x < 100; $x++) {
			$salt = hash::salt();
			$insertData = [
				'fName' 		 => $faker->firstName,
				'lName' 		 => $faker->lastName,
				'userName' 	 => $faker->userName,
				'password' 	 => hash::make('alexandra', $salt),
				'salt'  		 => $salt,
				'email' 		 => $faker->email,
				'sex' 		 => $sex[array_rand($sex)],
				'singUpDate' => date('Y-m-d H:i:s'),
				'code' 		 => md5(uniqid(rand(0, 99999))),
				'active' 	 => '1',
			];

			$this->db->insert(User::getUsersTable(), $insertData);
		}
	}
}