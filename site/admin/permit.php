<?php header('Content-type: text/html; charset=utf-8'); ?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/permit.css">
</head>
<body>
<?php
session_start();
// echo "<pre>"; print_r($_POST); echo "</pre>";
if(isset($_SESSION['id']))
{
		$conn=mysql_connect("localhost","user","4ddmvxc93");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);	
		
		$subdomen = explode(".", $_SERVER['HTTP_HOST']);
		$subdomen=$subdomen[0];

		$sql=<<<here
			SELECT id FROM `users`
			WHERE `parent`={$_SESSION['id']}
			
here;
		$result=mysql_query($sql,$conn);
		switch(mysql_num_rows($result))
		{
			case 0:$side='<option value="1">правая</option>';
				$ofis=' <option value="0" selected="selected">a</option>' ; 
				break;
			case 1:$side='<option value="0" selected="selected">левая</option>';
				$ofis=' <option value="0" selected="selected">a</option>' ; 
				break;
			case 2:$side='<option value="1">правая</option>';
				$ofis=' <option value="0" selected="selected">a</option>' ; 
				break;
			default: $side='<option value="0" selected="selected">левая</option><option value="1">правая</option>';
				$ofis=' <option value="0" selected="selected">a</option><option value="1">r</option><option value="2">l</option>' ; 
						break;
		}

		if(isset($_POST['side']))
		{
				
			$sql=<<<here
			SELECT id FROM `users`
			WHERE `id`={$_SESSION['id']}
here;
			$result2=mysql_query($sql,$conn);

			
			
		
			while(mysql_num_rows($result2)!=0)
			{
				$user=mysql_fetch_assoc($result2);
				$sql=<<<here
					SELECT id FROM `users`
					WHERE `side`={$_POST['side']} AND `parent`={$user['id']}
here;
				
				$result2=mysql_query($sql,$conn);
			
			
			}
		
	
		//echo $user['id'];
		
		$sql=<<<here
					INSERT INTO  `cosmos`.`users` (
`type` ,`subdomen` ,`parent` ,`side` ,`invited` ,`city` ,`index` ,
`surname` ,`name` ,`middle_name` ,`birthday` ,`inn` ,`street` ,`house` ,`room` ,
`password` ,`telephone` ,`email` ,`number_passport` ,`seria_passport` ,`vidana`
)
VALUES (
  '{$_POST['type']}',  '{$_POST['subdomen']}',  '{$user['id']}',  '{$_POST['side']}',  '{$_POST['invite_id']}',  
  '{$_POST['city']}',  '{$_POST['index']}',  '{$_POST['surname']}',  '{$_POST['name']}',  '{$_POST['middle_name']}',
  '{$_POST['birthday']}',  '{$_POST['inn']}',  '{$_POST['street']}',  '{$_POST['house']}',  '{$_POST['room']}',
  '{$_POST['password']}',  '{$_POST['telephone']}',  '{$_POST['email']}',  '{$_POST['number_passport']}',  '{$_POST['seria_passport']}', 
  '{$_POST['vidana']}'
)
here;
		//echo $sql.'<br>';
		$result3=mysql_query($sql,$conn);
		$sql=<<<here
			DELETE FROM `cosmos`.`waiting_for_permit` 
			WHERE `waiting_for_permit`.`subdomen` LIKE '{$_POST['subdomen']}'
here;
		//echo $sql.'<br>';
		$result3=mysql_query($sql,$conn);
		
		print "Вы подтвердили {$_POST['subdomen']}.";
		}


		//$result2=mysql_query($sql,$conn);
		
		?>


				


<a href="lar.php">Офис</a>
		<table><tr><td>Имя</td><td>Фамилия</td>
		<td>Бэк офис</td>
		<td>Сторона</td>
		</tr>
<?php
	$sql=<<<here
				SELECT * 
		FROM  `waiting_for_permit` 
		WHERE  `invite_id` ={$_SESSION['id']} AND
		`permit_email` = 1
here;
	$result=mysql_query($sql,$conn);
	while ($guest=mysql_fetch_assoc($result))
		{
			print<<<here
			<tr><form method="post">
			<td>{$guest['name']} </td>
			<td> {$guest['surname']} </td>
			
			<td>
			<select name="type" >
					 {$ofis}
			</select></td>
			<td>
			<select name="side">
					  {$side}
			</select></td>
			<td><input type="submit" value="Подтвердить"/></td>
here;
			foreach($guest as $key=>$value)
			{
				print "<input type='hidden' name='{$key}' value='{$value}'/>";
			}
			
			print "</form></tr>";

		}
	
	
?>
</table>
<?php
		}
else header ('Location: index.php');		
		?>
</body>
</html>