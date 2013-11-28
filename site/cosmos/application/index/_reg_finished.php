 <?php header('Content-type: text/html; charset=utf-8'); ?>
<!doctype html>
<html>
<head>
</head>
<body>
<?php
// echo "<pre>"; print_r($_POST); echo "</pre>";
function generateCode($length)
{
   $chars = 'abdefhiknrstyz1234567890';
   $numChars = strlen($chars);
   $string = '';
   for ($i = 0; $i < $length; $i++)
   {
      $string .= substr($chars, rand(1, $numChars) - 1, 1);
   }
   return $string;
}
$conn = mysql_connect("localhost","user","4ddmvxc93");
mysql_select_db("cosmos",$conn);
mysql_query('SET NAMES "utf8"', $conn);

if (isset($_POST['subdomen'])) {

		$subdomen = explode(".", $_SERVER['HTTP_HOST']);
		$subdomen=$subdomen[0];
		$code=generateCode(8);
		print "На ваш почтовый ящик отправлено письмо с ссылкой для подтверждения почты.";
		$message="http://{$subdomen}.cosmos.sc/reg/reg_finished.php?hash={$code}";
		$subject = 'Подтверждение почтового ящика';
		mail($_POST['email'], $subject, $message);
		$subdomen = explode(".", $_SERVER['HTTP_HOST']);
	    $subdomen = $subdomen[0];
				$sql=<<<here
			INSERT INTO `cosmos`.`waiting_for_permit` (`code`,`permit_email`,`subdomen`, `city`, `index`, `surname`, `name`, `middle_name`, `birthday`, `inn`, `street`, `house`, `room`, `password`,  `telephone`, `email`, `number_passport`, `seria_passport`, `vidana`, `invite_id`) 
			VALUES ('{$code}', '0', '{$_POST['subdomen']}', '{$_POST['city']}', '{$_POST['index']}', '{$_POST['surname']}', '{$_POST['name']}', '{$_POST['middle_name']}', '{$_POST['birthday']}', '{$_POST['inn']}', '{$_POST['street']}', '{$_POST['house']}', '{$_POST['room']}' , '{$_POST['password']}',  '{$_POST['telephone']}', '{$_POST['email']}', '{$_POST['number_passport']}', '{$_POST['seria_passport']}', '{$_POST['vidana']}', '{$_POST['invite_id']}');
here;
		$result=mysql_query($sql,$conn);
} else if (isset($_GET['hash'])) {
	
	//загрузка документов
/*	$uploaddir = "/uploads/document/";
	$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
	
	 if($_FILES['userfile']['size'] != 0 and $_FILES['userfile']['size']<=10024000) 
	 { // Здесь мы проверяем размер если он более 1 МБ
		 if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
		 { // Здесь идет процесс загрузки изображения
		 $size = getimagesize($uploadfile); // с помощью этой функции мы можем получить размер пикселей изображения

		 if ($size[0] < 800 && $size[1]<800) 
		 { 
			 echo "Фотография загружена.";
		}
		else 
		{
		echo "Размер пикселей превышает допустимые нормы (ширина не более - 800 пикселей, высота не более 800)"; 
			 unlink($uploadfile); 
		}
	  
		 } 
		 else 
			{echo "Файл не загружен, попробуйте еще раз";
}
	 }
	 else 
		{ echo "Размер файла не должен превышать 1000Кб";}*/
	
	// конец загрузки документов
	
	$sql=<<<here
		SELECT `code` FROM `cosmos`.`waiting_for_permit` WHERE `code` LIKE '{$_GET['hash']}';
here;
	$result=mysql_query($sql,$conn);
	if(mysql_num_rows($result)!=0) {
		$sql=<<<here
		UPDATE  `cosmos`.`waiting_for_permit` SET  `permit_email` =  '1' WHERE  `waiting_for_permit`.`code` LIKE '{$_GET['hash']}';
here;
		$result=mysql_query($sql,$conn);
		print "Вы успешно зарегистрировались. Ожидайте подтверждения вашей регистрации рефералом.";
	}	
}
else header ('Location: /reg/');
?>

</body>
</html>