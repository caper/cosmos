<?php

class IndexCabinet_Controller extends Mikron_Controller {

    public function __construct($application, $controller_name, $action) {
        $this->activeMenu = '/cabinet/';
        if(!Model_User::isLogged() && !in_array($action, array('login', 'logout', 'enter'))) {
            $this->redirect('/cabinet/login/');
        }
		/**/
        $this->user = Model_User::getUser();
		//if (!isset($this->user->id)) $this->redirect('/cabinet/login/');
		
		
    }

    public function index() {
		$sql = "SELECT * from users where id = '{$this->user->id}'";
		$user = db1::fetch(db1::query($sql));
		$user = (object)$user;
		$_SESSION['user_profile'] = $user;
		//$this->user->active=$_SESSION['user_profile']->active;
		 $this->user = Model_User::getUser();
		
		
						
        if(isset($_GET['dislay'])) {
            $this->disabledLayout();
            $this->disabledView();
            exit;
        }
		
        $this->layout = 'index';
        $this->title = 'Главная';
        $this->domain_name = Model_User::getDomainsName();
		
        try {
            $this->domain_user = Model_User::getDomainsUser();
        } catch(Exception $ex) {
            $this->domain_user = null;
        }
    }

    function login() {
        if(Model_User::getDomainsName() == 'cosmos') {
            $this->redirect('/');
        }
    }

    public function logout() {
        $this->disabledView();
        $this->disabledLayout();
        Model_User::logout();
        $this->redirect('/');
    }

    /**
    * @ajax
    */
    function enter() {
        $this->disabledView();
        $this->disabledLayout();
        $login = Model_User::getDomainsName(); // $_POST['userName'];
        $password = $_POST['password'];
        try {
            $resp = Model_User::login($login, $password);
            echo json_encode(array('status' => 'success', 'message' => 'Произведён успешный вход в систему', 'domain' => $login));
        } catch(Exception $ex) {
            echo json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
        }
    }

    /**
    * Страница "Моя структура"
    */
    function lar() {
		if ($this->user->active==0) {
		$this->redirect('/cabinet/webmoney_pay');
		}
        $this->disabledLayout();
        $this->user = Model_User::getDomainsUser();
		
    }

    /**
    * Страница "Купить бизнес-пакет"
    */
    function webmoney_pay() {
        $this->disabledLayout();
        $this->user = Model_User::getDomainsUser();
        $sql = "INSERT INTO  `cosmos`.`payment` (`id`, `status`) VALUES(NULL, '0')";
        $result = db1::query($sql);
        $this->new_id = db1::lastInsertId();
    }

    /**
    * Страница "Upgrade бизнес-пакета"
    */
    function webmoney_upgrade() {
        $this->disabledLayout();
        $this->user = Model_User::getDomainsUser();
    }

    /**
    * Страница "Учёт"
    */
    function uchet() {
		
		Model_User::updateSession('active',$this->user->subdomen);
		Model_User::updateSession('total',$this->user->subdomen);
		Model_User::updateSession('cash',$this->user->subdomen);
		$this->user = Model_User::getUser();
		if ($this->user->active==0) {
		$this->redirect('/cabinet/webmoney_pay');
		}

		
		
        $this->disabledLayout();
        $this->user = Model_User::getDomainsUser();
		
    }

    /**
    * Страница "Линейная структура"
    */
    function linear() {
        $this->disabledLayout();
        $this->user = Model_User::getDomainsUser();
    }

    /**
    * @ajax
    */
    function getpack() {
        $this->disabledView();
        $this->disabledLayout();
        $form = $_POST['form'];
        $vaucher_code = $form['code'];
        $sql = "SELECT * from vouchers where number = '{$vaucher_code}'";
        $result = db1::query($sql);
        if($voucher=mysql_fetch_assoc($result)) {
           
            switch($voucher['pack']) {
                case 1: $pv = 190; $cv = 10; break;
                case 2: $pv = 760; $cv = 200; break;
                case 3: $pv = 1470; $cv = 450; break;
            }
            $date = date('o-m-d');
            $sql1 = "UPDATE  `cosmos`.`users` SET  `pv` =  `users`.`pv` + '{$pv}', `pack` = '{$voucher['pack']}' , `last_buy` = '{$date}', `active` = '1' WHERE  `users`.`id` = {$_POST['id']}";
            $result = db1::query($sql1);
            $sql = "SELECT parent, side, type FROM `users` WHERE  `id` = {$this->user->id}";
            $result = db1::query($sql);
             $row = mysql_fetch_assoc($result);
            $side = $row['side'];
            $current_id = 1; // чтобы просто не равнялось нулю
            while($current_id != 0) {
                switch($row['type']) {
                    case 0: $type_sv="cva"; $side = $row['side']; break;
                    case 1: $type_sv="cvr";  $side = ''; break;
                    case 2: $type_sv="cvl";  $side = ''; break;
                }
                $sql2 = "UPDATE  `cosmos`.`users` SET  `{$type_sv}{$side}` =  `users`.`{$type_sv}{$side}`+'{$cv}' WHERE  `users`.`id` = {$row['parent']} AND `users`.`active` = 1";
                $result = db1::query($sql2);
                $current_id = $row['parent'];
                $sql = "SELECT parent,side,type FROM  `users` WHERE  `id` ={$current_id}";
                $result = db1::query($sql);
                $row = mysql_fetch_assoc($result);
            }
            $sql = "DELETE FROM `cosmos`.`vouchers` WHERE `vouchers`.`number` = '{$vaucher_code}';";
            $result = db1::query($sql);
            echo json_encode(array('status' => 'success', 'message' => 'Вы приобрели пакет.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Извините, но такого кода не найдено.'));
        }   
    }

    /**
    * Обмен на ваучер
    * @ajax
    */
    function obmenvaucher() {
        $this->disabledView();
        $this->disabledLayout();
        $form = $_POST['form'];
        $pack = $form['pack'];
        $total = $this->user->total;
        switch($pack) {
            case 1: $packname = "Member Pack"; $cost = 190; break;
            case 2: $packname = "Start Pack"; $cost = 760; break;
            case 3: $packname = "Business Pack"; $cost = 1470; break;
        }
        if ($cost <= $total) {
            $number = generateCode(12);
            $date = date('o-m-d', time());
            $sql = "INSERT INTO  `cosmos`.`vouchers` ( `number` , `pack`, `who_buy_id`, `buy_date`) VALUES ('{$number}',  '{$pack}', '{$this->user->id}', '{$date}')";
            $result = db1::query($sql);
            $sql = "UPDATE  `cosmos`.`users` SET  `total` =  `users`.`total`-{$cost} WHERE `users`.`id` = 1";
            $result = db1::query($sql);
            $message = "Код ваучера для {$packname} : {$number}";
            $subject = 'Код ваучера';
            mail($_POST['email'], $subject, $message);
            echo json_encode(array('status' => 'success', 'message' => "Вы купили ваучер для {$packname}.<br>Код был выслан вам на почту."));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'У вас недостаточно средств'));
        }
    }

    /**
    * Вывод средств
    * @ajax
    */
    function export() {
        $this->disabledView();
        $this->disabledLayout();
        $form = $_POST['form'];
        $total = $this->user->total;
        $export_type = $form['export_type'];
        if($export_type == 'webmoney') {
            $sql = "UPDATE  `cosmos`.`users` SET  `total` =  '0', `cash` =  '{$total}' WHERE  `users`.`id` = {$this->user->id}";
           
			$result = db1::query($sql);
            echo json_encode(array('status' => 'success', 'message' => 'Денежный перевод вам на счет будет совершен в ближайшее время.'));
        }
    }

    function permit() {
		if ($this->user->active==0) {
		$this->redirect('/cabinet/webmoney_pay');
		}
        $this->disabledLayout();
    }

    /**
    * @ajax
    */
    function permitapply() {
        $this->disabledView();
        $this->disabledLayout();
        $form = $_POST['form'];
        $guest_subdomen = $form['subdomen'];
        $sql = "SELECT id FROM `users` WHERE `id` = {$this->user->id}";
        $result2 = db1::query($sql);
        switch($_POST['type']) {
            case 'a0': $side = "0"; $type = "0"; break;
            case 'a1':  $side = "1";  $type = "0"; break;
            case 'r':  $side = "0";  $type = "1"; break;
            case 'l':  $side = "1";  $type = "2"; break;
        }
        while(mysql_num_rows($result2) != 0) {
            $user = db1::fetch($result2);
            $user = (array)$user;
            $sql = "SELECT id FROM `users` WHERE `side`={$side} AND `parent` = {$user['id']} AND `type` = {$type}";
            $result2 = db1::query($sql);
        }
        $sql = <<<here
INSERT INTO `cosmos`.`users`(`type` ,`subdomen` ,`parent` ,`side` ,`invited` ,`city` ,`index`, `surname` ,`name` ,`middle_name` ,`birthday` ,`inn` ,`street` ,`house` ,`room`, `password` ,`telephone` ,`email` ,`number_passport` ,`seria_passport` ,`vidana`,
 `webmoney`, `country`, `card_type`, `card_invoice`, `card_nubmer`, `card_owner`, `card_bank`, `card_end_date`)
VALUES (
'{$type}', '{$_POST['subdomen']}',  '{$user['id']}',  '{$side}', '{$_POST['invite_id']}',  
'{$_POST['city']}',  '{$_POST['index']}', '{$_POST['surname']}', '{$_POST['name']}', '{$_POST['middle_name']}',
'{$_POST['birthday']}', '{$_POST['inn']}', '{$_POST['street']}', '{$_POST['house']}', '{$_POST['room']}',
'{$_POST['password']}', '{$_POST['telephone']}', '{$_POST['email']}',  '{$_POST['number_passport']}',  '{$_POST['seria_passport']}', 
'{$_POST['vidana']}', '{$_POST['webmoney']}', 
'{$_POST['country']}', '{$_POST['card_type']}', '{$_POST['card_invoice']}', '{$_POST['card_nubmer']}',
 '{$_POST['card_owner']}', '{$_POST['card_bank']}', '{$_POST['card_end_date']}'
)
here;
        $subdomen = $_POST['subdomen'];
        $result3 = db1::query($sql);
        $sql = "DELETE FROM `cosmos`.`waiting_for_permit` WHERE `waiting_for_permit`.`subdomen` LIKE '{$subdomen}'";
        $result3 = db1::query($sql);
		$message="Здравствуйте. Вас подтвердили на сайте cosmos.sc. Вы можете зайти в свой личный кабинет под своим логином и паролем по адресу: http://{$subdomen}.cosmos.sc";
		$subject = 'Вас подтвердили';
		mail($_POST['email'], $subject, $message);
        echo json_encode(array('status' => 'success', 'message' => "Вы подтвердили {$subdomen}.", 'guest_subdomen' => $guest_subdomen));
    }
	
	function changeProfile() {
        $this->disabledLayout();
        $this->user = Model_User::getDomainsUser();
		
		if(isset($_POST['email'])) {
            $sql = "UPDATE  `cosmos`.`users` SET  `email` =  '{$_POST['email']}',`telephone` =  '{$_POST['telephone']}', `webmoney` =  '{$_POST['webmoney']}'  where `id` = {$this->user->id}";
            $result = db1::query($sql);	
			$this->redirect('/cabinet/changeProfile/');
        }
		
		
    }
	
	

}