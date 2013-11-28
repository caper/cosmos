<?php

/**
* Обработчик покупки пакета от Webmoney
*/
class IndexWebmoney_Controller extends Mikron_Controller {

    public function __construct() {
		
		 //$this->user = Model_User::getUser();
		//$du = Model_User::getDomainsUser();
		//$this->user = Model_User::getUserBySubdomen($du->subdomain);
	}

    public function paysuccess() {
        $this->disabledView();
        $this->disabledLayout();
        /*
        if(isset($_POST['LMI_PAYMENT_NO'])) {
            $sql = "SELECT * FROM  `payment` WHERE  `id` ={$_POST['LMI_PAYMENT_NO']} AND  `status` = 1";
            $result = db1::query($sql);
            $rows = mysql_num_rows($result);
            if($rows == 1) {
                $sql = "UPDATE  `cosmos`.`payment` SET  `status` =  '2' WHERE  `payment`.`id` = {$_POST['LMI_PAYMENT_NO']}";
                $result = db1::query($sql);
                $sql = "UPDATE  `cosmos`.`users` SET  `pv` =  '{$_POST['PV']}' WHERE  `users`.`id` = {$_POST['id']}";
                $result = db1::query($sql);
            } else {
                echo 'покупка не оплачена';
            }
        }*/
        ?>
        <!doctype html>
        <!-- success.html --> 
        <html> 
        <head> 
        <title>Success</title> 
        </head> 
        <body> 
        <p>Платеж был выполнен.</p>
		<p><a href="/cabinet/">Обратно в личный кабинет</a></p>
        </body> 
        </html>
        <?
    }

    public function paypermit() {
        $this->disabledView();
        $this->disabledLayout(); 

		
        if(isset($_POST['LMI_PAYMENT_NO'])) {
            $LMI_SECRET_KEY = "=d45%2/sd,^430=";
            $chkstring = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].
                $_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].
                $LMI_SECRET_KEY.$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
            $md5sum = strtoupper(md5($chkstring));
            if($_POST['LMI_HASH'] == $md5sum) {
                $sql = "SELECT * FROM  `payment` WHERE  `id` ={$_POST['LMI_PAYMENT_NO']} AND `status` = 0";
                $result = db1::query($sql);
                $rows = mysql_num_rows($result);
                if($rows == 1) {
                    $sql = "UPDATE  `cosmos`.`payment` SET  `status` =  '1' WHERE  `payment`.`id` = {$_POST['LMI_PAYMENT_NO']}";
                    $result = db1::query($sql);
                    switch($_POST['LMI_PAYMENT_AMOUNT']) {
                        case '190.00': $pack = 1; $cv = 10; $buy_type="Покупка"; break;
                        case '760.00': $pack = 2; $cv = 200; $buy_type="Покупка"; break;
                        case '1470.00': $pack = 3; $cv = 450; $buy_type="Покупка"; break;
                        case '570.00': $pack = 2; $cv = 190; $buy_type="Апгрейд"; break;
                        case '1280.00': $pack = 3; $cv = 440; $buy_type="Апгрейд"; break;
                        case '710.00': $pack = 3; $cv = 250; $buy_type="Апгрейд"; break;
                    }
					
                    $date = date('o-m-d');
                    $sql1 = "UPDATE  `cosmos`.`users` SET  `pv` =  `users`.`pv` + '{$_POST['LMI_PAYMENT_AMOUNT']}', `pack` = '{$pack}' , `last_buy` = '{$date}', `active` = '1' WHERE  `users`.`id` = {$_POST['id']}";
                    $result = db1::query($sql1);
				
                    $sql = "SELECT parent,side,type FROM  `users` WHERE  `id` ={$_POST['id']}";
                    $result = db1::query($sql);
                    $row = mysql_fetch_assoc($result);
                    $side = $row['side'];
					
                    $current_id = 1; //чтобы просто не равнялось нулю
                    while($current_id != 0) {
                        switch($row['type']) {
                            case 0: $type_sv = "cva"; $side = $row['side']; break;
                            case 1: $type_sv = "cvr";  $side = ""; break;
                            case 2: $type_sv = "cvl";  $side = ""; break;
                        }
						
                        $sql2 = "UPDATE  `cosmos`.`users` SET  `{$type_sv}{$side}` =  `users`.`{$type_sv}{$side}`+'{$cv}' WHERE  `users`.`id` = {$row['parent']} AND `users`.`active` = 1";
                        $result = db1::query($sql2);
						
                        $current_id = $row['parent'];
                        $sql = "SELECT parent,side,type FROM  `users` WHERE  `id` ={$current_id}";
                        $result = db1::query($sql);
                        $row = mysql_fetch_assoc($result);
					
                    }
                    $message = <<<here
Прошел платеж     
LMI_PAYMENT_AMOUNT: {$_POST['LMI_PAYMENT_AMOUNT']}

here;
                    $subject = 'Оповещение платежа';
                    mail('m.rustem18@gmail.com', $subject, $message);
					
					 $sql=<<<here
		 INSERT INTO  `cosmos`.`pay_statistic` (
		`id` ,
		`date` ,
		`count` ,
		`pack`,
		`buy_type`,
		`PAYER_PURSE`
		)
		VALUES (
		'{$_POST['id']}', 
		CURRENT_TIMESTAMP ,  '{$_POST['LMI_PAYMENT_AMOUNT']}',  '{$pack}', '{$buy_type}', '{$_POST['LMI_PAYER_PURSE']}'
		);

here;
					$result = db1::query($sql);
			
                }
            } else {
                $message = "проверка провалилась {$md5sum}";
                $subject = 'Оповещение платежа';
                mail('m.rustem18@gmail.com', $subject, $message);
            }
        }
        ?>
        <!doctype html>
        <!-- pay.html -->
        <html>
        <head>
        <title>Pay permit</title>
        </head>
        <body>
        </body>
        </html>
        <?
    }

}
