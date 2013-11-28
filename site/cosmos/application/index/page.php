<?php

class IndexPage_Controller extends Mikron_Controller {

    public function __construct() {
        $this->activeMenu = '/';
    }

    public function open() {
        $this->page = Model_Page::getById($this->id);
        if(!$this->page) {
            $this->redirect('/');
        }
    }

}