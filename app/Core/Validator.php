<?php

namespace App\Core;

/**
 * Class FormValidation
 */
class Validator
{
    /**
     * @var array
     */
    private $_rules = [];
    /**
     * @var array
     */
    private $_data = [];
    /**
     * @var array
     */
    private $_errors = [];

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }


    public function getApiErrors()
    {
        return ['success' => false, 'errors' => $this->_errors];
    }

    /**
     * @param $data
     */
    public function setData($data): void
    {
        $this->_data = $data;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setRules($data): self
    {
        $this->_rules = $data;
        return $this;
    }

    /**
     * @param $rule
     * @param $filed
     * @return bool
     */
    public function minMax($rule, $filed): bool
    {
        $length = strlen($this->_data[$filed]);
        $valid = true;

        if (isset($rule['min'])) {
            $valid = intval($rule['min']) <= $length;
        }

        if ($valid && isset($rule['max'])) {
            $valid = $length <= intval($rule['max']);
        }

        return $valid;
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        if (empty($this->_rules))
            return true;

        foreach ($this->_rules as $fields => $rule) {
            $fields = explode(',', $fields);
            foreach ($fields as $filed) {
                $filed = trim($filed);
                if (!$filed) continue;
                if (!isset($rule['message'])) $rule['message'] = '%s error.';

                if (in_array('required', array_values($rule))) {
                    if (!isset($this->_data[$filed]) || (isset($this->_data[$filed]) && !trim($this->_data[$filed]))) {
                        $this->_errors[] = sprintf($rule['message'], $filed);
                    }
                }

                if (in_array('exists', array_values($rule))) {
                    if (!isset($this->_data[$filed]) || (isset($this->_data[$filed]) && !trim($this->_data[$filed]))) {
                        $this->_errors[] = sprintf($rule['message'], $filed);
                    }
                }

                if (in_array('string', array_values($rule)) && isset($this->_data[$filed]) && trim($this->_data[$filed])) {
                    if (!$this->minMax($rule, $filed)) {
                        $this->_errors[] = sprintf($rule['message'], $filed);
                    }
                }
                if (in_array('email', array_values($rule)) && isset($this->_data[$filed]) && trim($this->_data[$filed])) {
                    if (!$this->minMax($rule, $filed) || !filter_var($this->_data[$filed], FILTER_VALIDATE_EMAIL)) {
                        $this->_errors[] = sprintf($rule['message'], $filed);
                    }
                }
                if (in_array('array', array_values($rule)) && isset($this->_data[$filed]) && trim($this->_data[$filed])) {
                    if (isset($_REQUEST[$filed]) && isset($rule['allowed'])) {
                        if (!in_array($_REQUEST[$filed], $rule['allowed'])) {
                            $this->_errors[] = sprintf($rule['message'], $filed);
                        }
                    }
                }
                if (in_array('file', array_values($rule)) && isset($_FILES[$filed])) {
                    if ($_FILES[$filed]['size'] > 0) {
                        $ferr = true;
                        if (isset($rule['types']) && !empty($rule['types'])) {
                            $ferr = !in_array($_FILES[$filed]['type'], $rule['types']);
                        }
                        if ($ferr) {
                            $this->_errors[] = sprintf($rule['message'], $filed);
                        }
                    }
                }
            }
        }
        $this->_errors = array_unique($this->_errors);
        return empty($this->_errors);
    }
}