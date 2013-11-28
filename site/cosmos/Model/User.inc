<?php

/**
* Настройки сайта
*/
class Model_User extends Mikron_Model {

    private static $salt = '9eeeab13-050a-4c27-8cf9-afe003be71c1';
    private static $settings = array();

    /**
    * Имя текущего поддомена
    */
    public static function getDomainsName() {
        $subDomain = explode('.', $_SERVER['HTTP_HOST']);
        $subDomain = $subDomain[0];
        return $subDomain;
    }

    /**
    * Владелец текущего поддомена
    */
    public static function getDomainsUser() {
        $subDomain = explode('.', $_SERVER['HTTP_HOST']);
        $subDomain = $subDomain[0];
        $subDomain = db1::prepareString($subDomain);
        $sql = "SELECT * from users where subdomen = '{$subDomain}'";
        $user = db1::fetch(db1::query($sql));
        if (!$user) {
            return null; // throw new Exception('Пользователь не найден', 404);
        }
        return (object)$user;
    }

    public static function isLogged() {
        return isset($_SESSION['user_profile']);
    }

    public static function getUser() {
        return isset($_SESSION['user_profile']) ? $_SESSION['user_profile'] : null;
    }

    public static function login($login, $password) {
        $login = db1::prepareString($login);
        $sql = "SELECT * from users where subdomen = '{$login}'";
        $user = db1::fetch(db1::query($sql));
        if (!$user) {
            throw new Exception('Пользователь не найден', 404);
        }
        $p = md5(self::$salt.$password);
        if($user->password != $p) {
            throw new Exception('Неверный логин или пароль', 404);
        }
        $user = (object)$user;
        $_SESSION['user_profile'] = $user;
        return $user;
    }

    public static function logout() {
        if(self::isLogged()) {
            unset($_SESSION['user_profile']);
			session_destroy();
            return true;
        } else {
            return false;
        }
    }
	
	public static function getUserBySubdomen($subDomain) {
        $sql = "SELECT * from users where subdomen = '{$subDomain}'";
        $user = db1::fetch(db1::query($sql));
        if (!$user) {
            return null; // throw new Exception('Пользователь не найден', 404);
        }
        return (object)$user;
    }
	
	public static function updateSession($key,$subDomain) {
			$sql = "SELECT {$key} from users where subdomen = '{$subDomain}'";
			$user = db1::fetch(db1::query($sql));
			$user =  (object)$user;
			$_SESSION['user_profile']->$key = $user->$key;
		}

}
