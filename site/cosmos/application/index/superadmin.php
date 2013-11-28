<?php

class IndexSuperadmin_Controller extends Mikron_Controller {

    public function __construct($application, $controller_name, $action) {
        $this->activeMenu = '/cabinet/';
        if(!Model_User::isLogged() && !in_array($action, array('login', 'logout', 'enter'))) {
            $this->redirect('/cabinet/login/');
        }
        $this->user = Model_User::getUser();
        if($this->user->subdomen != 'first') {
            $this->redirect('/');
        }
    }

    public function index() {
        Plugin_Menu::setActive('main-menu', 'sa_payd');
        if(isset($_POST['payd'])) {    
            $sql = "UPDATE  `cosmos`.`users` SET  `pay_by_firm` =  '1', `last_pay_by_firm`= NOW() where `id` = 0";
            foreach($_POST['payd'] as $key => $value) {
                $sql .= " OR `id` = {$_POST['payd'][$key]}";
            }
            $result = db1::query($sql);
        }
    }

    function accountant() {
        Plugin_Menu::setActive('main-menu', 'sa_wm');
        $sql = "SELECT subdomen,cash,webmoney,id FROM  `users` where `cash` != '0'";            
        $this->user_list = db1::query($sql);
        if(isset($_POST['payd'])) {
            $sql = "UPDATE  `cosmos`.`users` SET  `cash` =  '0' where `id` = 0";
            foreach($_POST['payd'] as $key => $value) {        
                $sql .= " OR `id` = {$_POST['payd'][$key]}";
            }
            $result = db1::query($sql);
            $this->redirect('/superadmin/accountant/');
        }
    } 
	
	function permitDocument() {
        Plugin_Menu::setActive('main-menu', 'permit_document');
        $sql = "SELECT * FROM  `waiting_for_permit` where `document_permit` = '0' AND `permit_email` = '1'";            
        $this->user_list = db1::query($sql);
        if(isset($_POST['permit'])) {
            $sql = "UPDATE  `cosmos`.`waiting_for_permit` SET  `document_permit` =  '1' where `code` = 3";
            foreach($_POST['permit'] as $key => $value) {        
                $sql .= " OR `subdomen` LIKE '{$_POST['permit'][$key]}'";
            }
            $result = db1::query($sql);
            $this->redirect('/superadmin/permitDocument/');
        }
    }
	
	function showUsers() {
        Plugin_Menu::setActive('main-menu', 'permit_document');
        $sql = "SELECT * FROM  `users` ORDER BY `id`";            
        $this->user_list = db1::query($sql);
    }
	
	function buyStat() {
        Plugin_Menu::setActive('main-menu', 'permit_document');
        $sql = "SELECT * FROM  `pay_statistic` ORDER BY date DESC";            
        $this->user_list = db1::query($sql);
    }
	
	function cv() {
        
    }
	
	function percent() {
        
    }

}