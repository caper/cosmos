<?php

class IndexReg_Controller extends Mikron_Controller {

    private $salt = '9eeeab13-050a-4c27-8cf9-afe003be71c1';

    public function __construct() {
        $this->activeMenu = '/reg/';
    }

    public function index() {
        if(!isset($_GET['invite_id'])) {
            return $this->redirect('/');
        }
        $invite_id = $_GET['invite_id'];
        $this->layout = 'index';
        // $this->title = 'Главная';
        $this->invite_id = $invite_id;
        $this->domain_name = Model_User::getDomainsName();
        if($this->domain_name == 'cosmos') {
            // регистрация невозможна с главного доменного имени
            $this->redirect('/');
        } else {
            $this->domain_user = Model_User::getDomainsUser();
        }
    }

    public function regrus() {
        $this->disabledLayout();
    }

    public function regukr() {
        $this->disabledLayout();
    }

    function generateCode($length) {
       $chars = 'abdefhiknrstyz1234567890';
       $numChars = strlen($chars);
       $string = '';
       for ($i = 0; $i < $length; $i++) {
          $string .= substr($chars, rand(1, $numChars) - 1, 1);
       }
       return $string;
    }
    
    public function finish() {
    }
	
	public function rusCity() {
		$this->disabledLayout();
        $this->disabledView();
    }
	
	

    public function finished() {
        $this->disabledLayout();
        $this->disabledView();
        try {
            $code = $this->generateCode(8);
            $date = date("o-m-d");
            $form = (object)$_POST['form'];
            if (!preg_match('/^[a-z0-9]+$/', $form->subdomen)) {
                echo json_encode(array('status' => 'error', 'message' => 'Недопустимые символы в логине', 'target' => 'login'));
                return true;
            }
            if($form->password != $form->password2) {   
                echo json_encode(array('status' => 'error', 'message' => 'Не совпадают пароли', 'target' => 'password'));
                return true;
            }
            if($form->email != $form->email2) {   
                echo json_encode(array('status' => 'error', 'message' => 'Не совпадают электронные почты', 'target' => 'email'));
                return true;
            }
			//проверка на совпадение логина,почты или телефона
			$sql3="SELECT subdomen,telephone,email FROM `cosmos`.`users` WHERE `subdomen` LIKE '{$_POST['form']['subdomen']}' OR `telephone`= '{$_POST['form']['phone']}' OR `email` =  '{$_POST['form']['email']}';";
			$result3 = db1::query($sql3);
			if(mysql_num_rows($result3)!=0) $row3 = mysql_fetch_assoc($result3);
			else {
				$sql3="	SELECT subdomen,telephone,email FROM `cosmos`.`waiting_for_permit` WHERE `subdomen` LIKE '{$_POST['form']['subdomen']}' OR `telephone`= '{$_POST['form']['phone']}' OR `email` =  '{$_POST['form']['email']}';";
				$result3 = db1::query($sql3);
				if(mysql_num_rows($result3)!=0) $row3 = mysql_fetch_assoc($result3);
			}
			if(isset($row3['subdomen'])) {
				if ($row3['subdomen']==$_POST['form']['subdomen']) {
					echo json_encode(array('status' => 'error', 'message' => 'Данный логин уже существует', 'target' => 'login'));
					return true;
				}
				if ($row3['email']==$_POST['form']['email']) {
					echo json_encode(array('status' => 'error', 'message' => 'Пользователь с данной почтой уже существует', 'target' => 'email'));
					return true;
				}
				if ($row3['telephone']==$_POST['form']['phone']) {
					echo json_encode(array('status' => 'error', 'message' => 'Пользователь с данным телефоном уже существует', 'target' => 'phone'));
					return true;
				}
			}
			switch($form->country) {
			case 1: $country ='Россия'; break;
			case 2: $country ='Украина'; break;
			}
            $password = md5($this->salt.$form->password);
            // file_put_contents(dirname(__FILE__).'/123.txt', var_export($form, 1));
            $sql = "INSERT INTO `cosmos`.`waiting_for_permit` (`code`,`permit_email`,`subdomen`,`reg_date`, `city`, `index`, `surname`, `name`, `middle_name`, `birthday`, `inn`, `street`, `house`, `room`, `password`,  `telephone`, `email`, `number_passport`, `seria_passport`, `vidana`, `invite_id`, `webmoney`, `document_permit`, `country`, `card_type`, `card_invoice`, `card_nubmer`, `card_owner`, `card_bank`, `card_end_date`) 
                    VALUES ('{$code}', '0', '{$form->subdomen}', '{$date}', '{$form->city}', '{$form->index}', '{$form->lastname}', '{$form->firstname}', '{$form->middlename}', '{$form->birthday}', '{$form->inn}', '{$form->street}', '{$form->house_number}', '{$form->flat}' , '{$password}',  '{$form->phone}', '{$form->email}', '{$form->passport_number}', '{$form->passport_serial}', '{$form->vidana}', '{$form->invite_id}', '{$form->webmoney}', '0', '{$country}', '{$form->card_type}', '{$form->card_invoice}', '{$form->card_nubmer}', '{$form->card_owner}', '{$form->card_bank}', '{$form->card_end_date}');  ";
            $result = db1::query($sql);
            $id = db1::lastInsertId();
			$message="Пройдите по этой ссылке: http://{$form->subdomen}.cosmos.sc/reg/finish/?hash={$code} . Проигнорируйте это письмо, если это вас не касается и оно попало к вам случайно.";
			$subject = 'Подтверждение почтового ящика';
			//mail($_POST['form']['email'], $subject, $message);
			//загрузка фотографий документов
			foreach($_FILES["form"]["name"]["file"] as $key=>$value) {				
				if(is_uploaded_file($_FILES["form"]["tmp_name"]["file"][$key])) {
				// move_uploaded_file($_FILES["form"]["tmp_name"]["file"][$key], "/home/cosmos/data/www/cosmos/site/cosmos/public/uploads/document/".$key.$_POST['form']['subdomen'].".png");
			   } else {
				  echo json_encode(array('status' => 'error', 'message' => 'Файлы не загружены', 'target' => 'phone'));
				return true;
			   }
			}
	
            echo json_encode(array('status' => 'success', 'message' => 'Вы успешно зарегистрировались. Ожидайте подтверждения вашей регистрации рефералом', 'id' => $id));
            return true;
        } catch(Exception $ex) {
            echo json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
        }
        /*
        if (isset($_POST['subdomen'])) {
                $subdomen = explode(".", $_SERVER['HTTP_HOST']);
                $subdomen = $subdomen[0];
                $code = $this->generateCode(8);
                
                $message="http://{$subdomen}.cosmos.sc/reg/reg_finished.php?hash={$code}";
                
                $subject = 'Подтверждение почтового ящика';

                mail($_POST['email'], $subject, $message);
                
                $date=date("o-m-d");
                
                
                $subdomen = explode(".", $_SERVER['HTTP_HOST']);
                $subdomen=$subdomen[0];

                    $sql=<<<here
                INSERT INTO `cosmos`.`waiting_for_permit` (`code`,`permit_email`,`subdomen`,`reg_date`, `city`, `index`, `surname`, `name`, `middle_name`, `birthday`, `inn`, `street`, `house`, `room`, `password`,  `telephone`, `email`, `number_passport`, `seria_passport`, `vidana`, `invite_id`) 
                VALUES ('{$code}', '0', '{$_POST['subdomen']}', '{$date}', '{$_POST['city']}', '{$_POST['index']}', '{$_POST['surname']}', '{$_POST['name']}', '{$_POST['middle_name']}', '{$_POST['birthday']}', '{$_POST['inn']}', '{$_POST['street']}', '{$_POST['house']}', '{$_POST['room']}' , '{$_POST['password']}',  '{$_POST['telephone']}', '{$_POST['email']}', '{$_POST['number_passport']}', '{$_POST['seria_passport']}', '{$_POST['vidana']}', '{$_POST['invite_id']}');
here;

                $result=mysql_query($sql,$conn);
        } elseif (isset($_GET['hash'])) {
            $sql=<<<here
                SELECT `code` FROM `cosmos`.`waiting_for_permit` WHERE `code` LIKE '{$_GET['hash']}';
here;

            $result=mysql_query($sql,$conn);
            if(mysql_num_rows($result)!=0) {
                $sql=<<<here
                    UPDATE  `cosmos`.`waiting_for_permit` SET  `permit_email` =  '1' WHERE  `waiting_for_permit`.`code` LIKE '{$_GET['hash']}';
here;
                $result=mysql_query($sql, $conn);
                print "Вы успешно зарегистрировались. Ожидайте подтверждения вашей регистрации рефералом.";
            }
        }       */
    
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
