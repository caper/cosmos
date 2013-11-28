<?php

    class Mikron_Criteria {

        private $class;

        public function __construct($class) {
            $this->class = $class;
        }

        public function doSelect() {
            return Mikron_Db::query("SELECT * FROM {$this->class}");
        }

    }
