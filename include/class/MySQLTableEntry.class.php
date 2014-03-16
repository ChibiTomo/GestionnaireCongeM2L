<?php
/**
 *
 * @author Yannis
 *
 */
class MySQLTableEntry {
	private $pdo;
	private $tableName;
	private $values = array();

	/**
	 *
	 * @param PDO $pdo
	 * @param string $tableName
	 * @param mixed $arg
	 * @throws Exception
	 */
	public function __construct(PDO $pdo, $tableName, $arg = array(), $load = false) {
		$this->pdo = $pdo;
		if (!is_string($tableName)) {
			throw new Exception('tableName have to be a string.');
		}
		$this->tableName = $tableName;

		if (is_array($arg)) {
			$this->hydrate($arg);
			if ($load) {
				$this->load($arg);
			}
		} else if (is_int($arg)) {
			$this->load($arg);
		} else if (!is_null($arg)) {
			throw new Exception('arg have to be an int, an array or null, ' . gettype($arg) . ' given: ' . $arg);
		}
	}

	/**
	 *
	 */
	public function getPDO() {
		return $this->pdo;
	}

	/**
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	protected function setValue($key, $value) {
		$this->values[$key] = $value;
	}

	/**
	 *
	 * @param string $key
	 * @return mixed or NULL if $key does not exists
	 */
	public function getValue($key) {
		if (is_null_or_empty($this->values[$key])) {
			return null;
		}
		return $this->values[$key];
	}

	public function getIntValue($key) {
		if (is_null_or_empty($this->values[$key])) {
			return null;
		}
		return intval($this->values[$key]);
	}

	/**
	 *
	 */
	public function getValues() {
		return $this->values;
	}


	protected function load($values) {
		if (is_int($values)) {
			$values = array('id' => $values);
		} else if (!is_array($values)) {
			throw new Exception('values have to be an int or an array, ' . gettype($arg) . ' given.');
		}

		$where = array(
			'where' => ' WHERE id=:id',
			'values' => $values,
		);

		if (is_array($values)) {
			$where = $this->buildWhere($values);
		}
		$request = 'SELECT * FROM ' . $this->tableName . $where['where'];
		$pst = $this->pdo->prepare($request);
		debug_request($request, $where['values']);
		$pst->execute($where['values']);
		$vals = $pst->fetch(PDO::FETCH_ASSOC);
		$this->values = $vals;
	}

	/**
	 * Send an INSERT request.
	 */
	public function store() {
		$values = $this->joinValues();
		$request = 'INSERT INTO ' . $this->tableName . ' SET ' . $values;
//		debug_request($request, $this->values);
		$pst = $this->pdo->prepare($request);
		$pst->execute($this->values);
	}

	/**
	 * Send an SELECT * request.
	 */
	protected function selectAll($where = '', $other = '') {
		if ($where != '') {
			$where = ' WHERE ' . $where;
		}
		$request = 'SELECT * FROM ' . $this->tableName . $where . $other;
		debug_request($request, $this->values);
		$pst = $this->pdo->prepare($request);
		$pst->execute($this->values);
		return $pst->fetchAll(PDO::FETCH_ASSOC);
	}

	protected static function getAll($pdo) {
		if (func_num_args() < 2) {
			throw new Exception('No table name given.');
		}

		$other = ' ';
		if (func_num_args() > 2) {
			$other .= func_get_arg(2);
		}
		$tableName = func_get_arg(1);
		$request = 'SELECT * FROM ' . $tableName . $other;
		debug('Select all: ' . $request);
		$pst = $pdo->prepare($request);
		$pst->execute();
		return $pst->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Send an UPDATE request.
	 */
	public function update() {
		$request = 'UPDATE ' . $this->tableName . ' SET ' . $this->joinValues();
		$pst = $this->pdo->prepare($request);
		$pst->execute($this->values);
	}

	public function delete() {
		$values = $this->joinValues();
		$request = 'DELETE FROM ' . $this->tableName . ' SET ' . $values;
		$pst = $this->pdo->prepare($request);
		$pst->execute($this->values);
	}

	/**
	 *
	 * @param array $values
	 * @return int
	 */
	protected function count(array $values = array()) {
		$request = 'SELECT COUNT(*) FROM ' . $this->tableName;
		$new_values = array();

		if ($values != array()) {
			$where = $this->buildWhere($values);

			$request .= $where['where'];
			$new_values = $where['values'];
		}

		$pst = $this->pdo->prepare($request);
		$pst->execute($new_values);
		$result = $pst->fetch(PDO::FETCH_NUM);
		if (is_null($result)) {

		}
		return intval($result[0]);
	}

	private function buildWhere(array $values) {
		$new_values = array();
		$array = array();
		foreach ($values as $col => $comparision) {
			$comparator = '=';
			$value = $comparision;

			if (preg_match('#^([^|]*)|([^|]*)$#', $comparision)) {
				$a = explode('|', $comparision, 2);
				if (count($a) == 2) {
					$comparator = $a[0];
					$value = $a[1];
				} else {
					$value = $a[0];
				}
			}

			$array[] = $col . $comparator . ':' . $col;
			$new_values[':' . $col] = $value;
		}
		$where = ' WHERE ' . join(' AND ', $array);

		return array(
			'where' => $where,
			'values' => $new_values,
		);
	}

	private function hydrate($array) {
		foreach ($array as $key => $value) {
			$this->values[$key] = $value;
		}
	}

	private function joinValues($glue = '=') {
		$array = array();
		foreach ($this->values as $key => $value) {
			$array[] = $key . $glue . ':' . $key;
		}
		return join(', ', $array);
	}
}
?>