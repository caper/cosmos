<?php

  session_start();

  $conn=mysql_connect("localhost","user","4ddmvxc93");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);	
		
		$subdomen = explode(".", $_SERVER['HTTP_HOST']);
$subdomen=$subdomen[0];


		$sql=<<<here
		SELECT * from users where subdomen='{$subdomen}';
here;

	$result=mysql_query($sql,$conn);
	
	if (mysql_num_rows($result)==0) print "Извините,но такого пользователя не найдено.";
	else
	{
	if (isset($_GET['out'])&&isset($_SESSION['id']))
{
	
		
	//echo $sql;
	// освобождаем все переменные сессии
	$_SESSION = array();


	// стираем cookie идентификатора сессии
	if (isset($_COOKIE[session_name()])) 
	{
   		setcookie(session_name(), '', time()-42000, '/');
	}

	
	
	// уничтожаем сессию
	session_destroy();
  } 
  
	 if ( isset($_GET['userName']))
	  {


		$sql=<<<here
		SELECT * 
FROM  `users` 
WHERE  `subdomen` LIKE  '{$subdomen}'
AND  `password` LIKE '{$_GET['password']}'
here;
		$result=mysql_query($sql,$conn);
		
			if (mysql_num_rows($result)!=0&&$_GET['userName']==$subdomen)
			{ 
				$row=mysql_fetch_assoc($result);
				print "Вы успешно авторизовались. <br>";		
				$_SESSION['id'] = $row["id"];	
				$_SESSION['subdomen'] = $row["subdomen"];	
				
				header ('Location: login.php');  // перенаправление на нужную страницу
				exit();
			}
			else  print "Неправильный логин или пароль. <br>";
	}


if (!isset($_SESSION['id']))
{?>
<form method="get" action="#">

<input type="text" name="userName" value="" placeholder="Логин"><br>
<input type="password" name="password" value="" placeholder="Пароль">
<br>
<input type="submit" value="Войти">
</form>


<?php 
}
else {?>

<form method="get">
<input type="hidden" name="out" value="1"> 
<input type="submit" value="Выход">
</form>
<?php
 }
 }
 
?>

</body>
</html>