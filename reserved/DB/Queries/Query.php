<?php
    class Query {
        public $tableName;
        public $type;
        public $sqlCommand;
        public $queryParams;

        public function __construct() {}

        public static function fill($tableName, $type, $sqlCommand = null, $queryParams = null) {
            $instance               = new self();
            $instance->tableName    = $tableName;
            $instance->type         = $type;
            $instance->sqlCommand   = $sqlCommand;
            $instance->queryParams  = $queryParams;
            return $instance;
        }

        public function addParam($name, $type = null, $size = null, $constraintOrValue = null) {
            if ($this->queryParams == null) $this->queryParams = array();
            if (is_array($this->queryParams)) {
                if ($this->type == CREATE) array_push($this->queryParams, QueryParam::fill($name, $type, $size, $constraintOrValue));
                if ($this->type == INSERT) array_push($this->queryParams, QueryParam::fill($name, $type, $size, null, $constraintOrValue));
                if ($this->type == SELECT) array_push($this->queryParams, QueryParam::fill($name, null, null, null, $constraintOrValue));
            }
        }

        public function addValue($value) {
            $count = 0;
            if ($this->queryParams != null && sizeof($this->queryParams) > 0) {
                foreach ($this->queryParams as $val) {
                    $this->queryParams[$count]->value = $value[$count];
                    $count = $count + 1;
                }
                $count = 0;
            }
        }
    }

    class QueryParam {
        public $name;
        public $type;
        public $size;
        public $constraint;
        public $value;

        public function __construct() {}

        public static function fill($name, $type = null, $size = null, $constraint = null, $value = null) {
            $instance               = new self();
            $instance->name         = $name;
            $instance->type         = $type;
            $instance->size         = $size;
            $instance->constraint   = $constraint;
            $instance->value        = $value;
            return $instance;
        }
    }