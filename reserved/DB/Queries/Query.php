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

        public function addParam($name, $type, $size = null, $constraint = null, $value = null, $required = true) {
            if($this->queryParams == null) $this->queryParams = array();
            if(is_array($this->queryParams)) {
                array_push($this->queryParams, QueryParam::fill($name, $type, $size, $constraint, $value, $required));
            }
        }
    }

    class QueryParam {
        public $name;
        public $type;
        public $size;
        public $constraint;
        public $value;
        public $required;

        public function __construct() {}

        public static function fill($name, $type, $size = null, $constraint = null, $value = null, $required = true) {
            $instance               = new self();
            $instance->name         = $name;
            $instance->type         = $type;
            $instance->size         = $size;
            $instance->constraint   = $constraint;
            $instance->value        = $value;
            $instance->required     = $required;
            return $instance;
        }
    }
?>