<?php

class IndexIndex_Controller extends Mikron_Controller {

    public function __construct() {
        $this->activeMenu = '/';
    }

    public function index() {
        $this->layout = 'index';
        $this->title = 'Главная';
        $this->domain_name = Model_User::getDomainsName();
        //try {
            $this->domain_user = Model_User::getDomainsUser();
        //} catch(Exception $ex) {
        //    $this->domain_user = null;
        //}
    }

    /*public function logout() {
        unset($_SESSION['profile']);
        $this->redirect('/');
    }

    public function login() {
        $this->disabledView();
        $this->disabledLayout();
        $login = $_POST['login'];
        $password = $_POST['password'];
        if($login == 'admin' && $password == '141024') {
            $user = (object)array('login' => $login, 'isadmin' => $login == 'admin');
            $_SESSION['profile'] = $user;
            echo json_encode(array('status' => 'success', 'message' => 'ok'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Неверные логин или пароль'));
        }        
    }*/

}