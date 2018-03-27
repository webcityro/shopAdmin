<?php
namespace Storemaker\System\Libraries;

class Database {
	private $pdo;
	private $sql;
	private $param;
	private $fetchMode;
	public $query;
	private $result;
	private $numRows = 0;
	private $affectedRows = 0;
	private $error = false;
	private $pagination;
	private $paginationReturn;
	private static $instance;

	private function __construct() {
		try {
			$this->pdo = new \PDO(Config::get('database/driver').':host='.Config::get('database/host').';dbname='.Config::get('database/database'), Config::get('database/username'), Config::get('database/password'));
		} catch (\PDOException $e) {
			die('Nu se poate conecta la baza de date!<br>'.$e->getMessage());
		}
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
		$this->pagination = new Pagination();
	}

	public static function init() {
		if (empty(self::$instance)) {
			self::$instance = new Database();
		}
		return self::$instance;
	}

	public function query($sql, $param = array(), $fetchMode = \PDO::FETCH_OBJ) {
		// echo $sql;
		// echo '<pre>'.print_r($param, true).'<pre>';
		$this->error = false;
		$this->affectedRows = 0;
		$this->sql = $sql;
		$this->param = $param;
		$this->fetchMode = $fetchMode;
		$this->query = $this->pdo->prepare($sql);

		foreach ($param as $key => $value) {
			if (is_numeric($value)) {
				$this->query->bindValue(':'.$key, $value, \PDO::PARAM_INT);
			} else {
				$this->query->bindValue(':'.$key, $value);
			}
		}

		if ($this->query->execute()) {
			$this->numRows = $this->query->rowCount();
			if (substr($sql, 0, 6) == 'SELECT') {
				$this->result = $this->query->fetchAll($fetchMode);
			}
		} else {
			$this->error = true;
		}

		return $this;
	}

	public function action($action, $table, $where = '', $order = '', $limit = '', $fetchMode = \PDO::FETCH_OBJ) {
		$order = (!empty($order)) ? ' ORDER BY '.$order : NULL;
		$limitStr = NULL;
		$whereStr = NULL;
		$param = array();

		if (!empty($limit)) {
			$limitStr = ' LIMIT :limit';
			$param['limit'] = $limit;
		}

		if (!empty($where)) {
			$whereStr = ' WHERE ';
			if (is_array($where)) {
				if (count($where) == 2) {
					$whereStr .= $where[0].'=:where';
					$param['where'] = $where[1];
				} else {
					$i = 0;
					$triger = 0;

					foreach ($where as $w) {
						if ($triger == 3 || $i == 2) {
							$whereStr .= ':w'.$i.' ';
							$param['w'.$i] = $w;
							$triger = 0;
						} else {
							$whereStr .= $w.' ';
							$triger++;
						}
						$i++;
					}
				}
			} else {
				$whereStr = ' WHERE id=:where';
				$param['where'] = $where;
			}
		}

		$sql = $action." FROM ".$table.$whereStr.$order.$limitStr;

		if (!$this->query($sql, $param, $fetchMode)->error()) {
			return $this;
		}
		return false;
	}

	public function select($table, $fields, $where = '', $order = '', $limit = '', $fetchMode = \PDO::FETCH_OBJ) {
		return $this->action("SELECT ".$fields, $table, $where, $order, $limit, $fetchMode);
	}

	public function count($table, $where = '')	{
		$stx = $this->select($table, 'COUNT(*)', $where, '', '', \PDO::FETCH_COLUMN);
		return $stx->results();
	}

	public function insert($table, $data) {
		$fieldNames = implode(', ', array_keys($data));
		$fieldValues = ':' . implode(', :', array_keys($data));

		return $this->query("INSERT INTO ".$table." (".$fieldNames.") VALUES(".$fieldValues.")", $data);
	}

	public function update($table, $data, $where = '', $limit = NULL) {
		$fieldSets = NULL;
		$limitStr = NULL;
		$param = array();

		if (!empty($limit)) {
			$limitStr = ' LIMIT :limit';
			$param['limit'] = $limit;
		}

		if (!empty($where)) {
			$whereStr = ' WHERE ';
			if (is_array($where)) {
				if (count($where) == 2) {
					$whereStr .= $where[0].'=:where';
					$param['where'] = $where[1];
				} else {
					$i = 0;
					$triger = 0;

					foreach ($where as $w) {
						if ($triger == 3 || $i == 2) {
							$whereStr .= ':w'.$i.' ';
							$param['w'.$i] = $w;
							$triger = 0;
						} else {
							$whereStr .= $w.' ';
							$triger++;
						}
						$i++;
					}
				}
			} else {
				$whereStr = ' WHERE id=:where';
				$param['where'] = $where;
			}
		}

		foreach($data as $key => $value) {
			$fieldSets .= "$key=:$key, ";
			$param[$key] = $value;
		}
		$fieldSets = rtrim($fieldSets, ', ');

		return (!$this->query("UPDATE ".$table." SET ".$fieldSets.$whereStr.$limitStr, $param)->error()) ? true : false;
	}

	public function delete($table, $where, $limit = '') {
		return $this->action("DELETE ", $table, $where, '', $limit);
	}

	public function paginate($itemsPerPage)	{
		$this->pagination->setItemsPerPage($itemsPerPage);
		$this->pagination->setTotalCount($this->getNumRows());

		$limits = explode(', ', $this->pagination->getLimit());
		$this->sql .= ' LIMIT :limit0, :limit1';
		$this->param['limit0'] = (int)$limits[0];
		$this->param['limit1'] = (int)$limits[1];

		$this->paginationReturn = ['numOfPages' => $this->pagination->getNumOfPages(),
									'currentPage' => $this->pagination->getCurrentPage(),
									'totalItems' => $this->pagination->getTotalItems(),
									'links' => $this->pagination->renderPages()];

		return $this->query($this->sql, $this->param, $this->fetchMode);
	}

	public function results() {
		return (count($this->result) > 0) ? $this->result : false;
	}

	public function getPagination()	{
		return $this->paginationReturn;
	}

	public function getNumRows() {
		return $this->numRows;
	}

	public function getLastInsertId() {
		return $this->pdo->lastInsertID();
	}

	public function error() {
		return $this->error;
	}

	public function getError() {
		return $this->pdo->errorInfo();
	}
}