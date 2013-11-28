<?php

class Mikron_Db_Result {

    private $engine = null;
    private $result = null;

    /**
    * @param Mikron_Db_Engine $engine
    */
    public function __construct($engine, $result) {
        $this->engine = $engine;
        $this->result = $result;
    }

    public function numRows() {
        return $this->engine->numRows($this->result);
    }

}    