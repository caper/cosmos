<?php

class Mikron_Route_Application {

    public $uri = array();
    public $function = null;
    public $conditions = array();

    public function __construct($uri, $function) {
        $this->uri = explode('/', $uri);
        $this->function = $function;
        return $this;
    }

    public function where($conditions) {
        $this->conditions = $conditions;
        return $this;
    }

}