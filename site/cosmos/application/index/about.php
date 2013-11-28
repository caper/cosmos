<?php

class IndexAbout_Controller extends Mikron_Controller {

    public function __construct() {
        $this->activeMenu = '/about/';
    }

    public function index() {
    }

    public function why() {
        Plugin_Menu::setActive('main-menu', 'why');
    }

    public function products() {
        Plugin_Menu::setActive('main-menu', 'products');
    }

    public function cosmoslive() {
        Plugin_Menu::setActive('main-menu', 'cosmoslive');
    }

}
