<?php

class validate {
	private $passed = false;
	private $errors = array();
	private $db;

	function __construct() {
		$this->db = database::init();
		language::load('validate');
	}

	public function check($dataSet, $items) {
		$this->passed = false;

		foreach ($items as $item => $rules) {
			$fieldLabel = $rules['label'];
			$fieldValue = (!empty($dataSet[$item])) ? $dataSet[$item] : NULL;
			unset($rules['label']);

			foreach ($rules as $rule => $ruleValue) {
				if ($rule == 'required' && $ruleValue == 'true') {
					if (empty($fieldValue)) {
						$this->errors[$item] = language::translate('validateRequired', $fieldLabel);
						break;
					}
					continue;
				}
				switch ($rule) {
					case 'min':
						if (strlen($fieldValue) < $ruleValue) {
							$this->errors[$item] = language::translate('validateMinLength', $fieldLabel, $ruleValue);
						}
					break;

					case 'max':
						if (strlen($fieldValue) > $ruleValue) {
							$this->errors[$item] = language::translate('validateMaxLength', $fieldLabel, $ruleValue);
						}
					break;

					case 'lengthRange':
						if (strlen($fieldValue) < $ruleValue[0] || strlen($fieldValue) > $ruleValue[1]) {
							$this->errors[$item] = language::translate('validateMaxLengthRange', $fieldLabel, $ruleValue[0], $ruleValue[1]);
						}
					break;

					case 'length':
						if (strlen($fieldValue) == $ruleValue) {
							$this->errors[$item] = language::translate('validatLength', $fieldLabel, $ruleValue);
						}
					break;

					case 'alpha':
						if (!ctype_alpha($fieldValue)) {
							$this->errors[$item] = language::translate('validateAlpha', $fieldLabel, $ruleValue);
						}
					break;

					case 'alnum':
						if (!ctype_alnum($fieldValue)) {
							$this->errors[$item] = language::translate('validateAlnum', $fieldLabel, $ruleValue);
						}
					break;

					case 'numeric':
						if (!ctype_digit($fieldValue)) {
							$this->errors[$item] = language::translate('validateNumeric', $fieldLabel, $ruleValue);
						}
					break;

					case 'alnumCustom':
						$fieldValue = str_replace(str_split($ruleValue), '', $fieldValue);

						if (!ctype_alnum($fieldValue)) {
							$this->errors[$item] = language::translate('validateAlnumCustom', $fieldLabel, $ruleValue);
						}
					break;

					case 'email':
						if (!preg_match("/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $fieldValue)) {
							$this->errors[$item] = language::translate('validateFormat', $fieldLabel, $ruleValue);
						}
					break;

					case 'match':
						if ($fieldValue != $dataSet[$ruleValue]) {
							$this->errors[$item] = language::translate('validateMatch', $fieldLabel);
						}
					break;

					case '!match':
						if ($fieldValue == $dataSet[$ruleValue]) {
							$this->errors[$item] = language::translate('validateNotMatch', $fieldLabel);
						}
					break;

					case 'match2':
						if (is_array($ruleValue)) {
							if (!in_array($fieldValue, $ruleValue)) {
								$ruleValue = implode(' '.language::translate('or').' ', $ruleValue);
								$this->errors[$item] = language::translate('validateMatch2', $fieldLabel, $ruleValue);
							}
						} else if ($fieldValue != $ruleValue) {
							$this->errors[$item] = language::translate('validateMatch2', $fieldLabel, $ruleValue);
						}
					break;

					case '!match2':
						if (is_array($ruleValue)) {
							if (in_array($fieldValue, $ruleValue)) {
								$ruleValue = implode(' '.language::translate('or').' ', $ruleValue);
								$this->errors[$item] = language::translate('validateNotMatch2', $fieldLabel, $ruleValue);
							}
						} else if ($fieldValue == $ruleValue) {
							$this->errors[$item] = language::translate('validateNotMatch2', $fieldLabel, $ruleValue);
						}
					break;

					case 'uniq':
						if (is_array($ruleValue)) {
							$tableName = $ruleValue['table'];
							$tableCollum = (isset($ruleValue['column'])) ? $ruleValue['column'] : $item;
						} else {
							$tableName = $ruleValue;
							$tableCollum = $item;
						}

						$where = [$tableCollum, '=', $fieldValue];

						if (isset($ruleValue['exclude'])) {
							foreach ($ruleValue['exclude'] as $col => $value) {
								$where += ['AND', $col, '!=', $value];
							}
						}
						$this->db->select($tableName, $tableCollum, $where, '', 1);

						if ($this->db->getNumRows() > 0) {
							$this->errors[$item] = language::translate('validateUniq', $fieldLabel);
						}
					break;
				}
			}
		}

		if (empty($this->errors)) {
			$this->passed = true;
		}
	}

	public function checkAge($d, $m, $y, $min = 18) {
		if ((!empty($d)) && (!empty($m) && (!empty($y)))) {
			if ((date('Y') - $y < $min) ||
			(date('Y') - $y == $min && date('m') < $m) ||
			(date('m') == $m && date('d') < $d)) {
				$this->errors['dobYear'] = language::translate('validateunderAge', $min);
				$this->passed = false;
			}
		}

	}

	public function passed() {
		return $this->passed;
	}

	public function getErrors() {
		return $this->errors;
	}
}