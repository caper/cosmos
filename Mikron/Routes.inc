<?php

class Mikron_Routes {

    public $items = array();

    public function get($uri, $function) {
        $get = new Mikron_Route_Application($uri, $function);
        $this->items[] = $get;
        return $get;
    }

    public function redirect($uri, $params = array()) {
        print_r($params);
    }

}


