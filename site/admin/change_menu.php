<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
<head>
<style>
li { list-style-type:none;}
 .text {width:200px;}
 form {display: inline-block;}
</style>
</head>
<body>
<?php
$conn=mysql_connect("127.0.0.1","root","510311");
		mysql_select_db("worldnrg",$conn);

		mysql_query('SET NAMES "utf8"', $conn);	
		
		//обработка событий
if (!empty($_POST['id']))
{
	$sql=<<<here
		UPDATE  `worldnrg`.`menu` SET  `text` =  '{$_POST['text']}' WHERE  `menu`.`id` ={$_POST['id']};
here;
	$result=mysql_query($sql,$conn);
}

//echo $_POST['html'];

if (!empty($_POST['change_page_id']))
{
	$sql=<<<here
		UPDATE  `worldnrg`.`menu` SET  `html` =  '{$_POST['html']}' WHERE  `menu`.`id` ={$_POST['change_page_id']};
here;
	$result=mysql_query($sql,$conn);

}
if (isset($_POST['parent_id']))
{
	$sql=<<<here
		INSERT INTO  `worldnrg`.`menu` (
		`id` ,
		`place` ,
		`parent` ,
		`text` ,
		`html`
		)
		VALUES (
		NULL ,  '{$_POST['place']}',  '{$_POST['parent_id']}',  '{$_POST['create_menu_text']}',  ''
		);
here;
	$result=mysql_query($sql,$conn);
}
if (isset($_POST['delete_page_id']))
{
	$sql=<<<here
	DELETE FROM `worldnrg`.`menu` WHERE `menu`.`id` = {$_POST['delete_page_id']};
here;
	$result=mysql_query($sql,$conn);

	$sql=<<<here
	DELETE FROM `worldnrg`.`menu` WHERE `menu`.`parent` = {$_POST['delete_page_id']};
here;
	$result=mysql_query($sql,$conn);
}

		//конец обработки событий



		
	function change_menu($menu_number,$conn)
	{	
		mysql_query('SET NAMES "utf8"', $conn);	
		$sql=<<<here
		SELECT text,id,html,place from menu where place={$menu_number} AND parent = 0;
here;
		$menu_number2=$menu_number+1;
		print "<h3>Пункт меню {$menu_number2}</h3>";

		$result=mysql_query($sql,$conn);
		
		while ($menu=mysql_fetch_assoc($result))
		{ 
			print<<<here
			<form action="change_menu.php" method="post">
				<input name="text" class="text" type='text' value='{$menu['text']}'>
				<input name="id" type="hidden" value="{$menu['id']}">
				<input  type="submit" value="изменить">
			</form>	
			<form action="change_menu.php" method="post">
					<input name="delete_page_id" type="hidden" value="{$menu['id']}">
					<input  type="submit" value="Удалить">
			</form>
here;
			if ($menu_number!=0) print<<<here
			 <form action="change_page.php" method="post">
						<input name="html"  type='hidden' value='{$menu['html']}'>
					<input name="change_page_id" type="hidden" value="{$menu['id']}">
					<input  type="submit" value="Редактировать страницу">
			</form>
			
here;

			print<<<here
			<ul  class="subsubmenu">
here;

			$sql2=<<<here
			SELECT text,id,html from menu where parent={$menu['id']};
here;
			$result2=mysql_query($sql2,$conn);
			while ($submenu=mysql_fetch_assoc($result2))
			{
				print<<<here
				<li>
				<form action="change_menu.php" method="post">
					<input name="text" class="text" type='text' value='{$submenu['text']}'>
					<input name="id" type="hidden" value="{$submenu['id']}">
					
					<input  type="submit" value="изменить">
					
				</form>
				<form action="change_menu.php" method="post">
					<input name="delete_page_id" type="hidden" value="{$submenu['id']}">
					<input  type="submit" value="Удалить ">
				</form>
				<form action="change_page.php" method="post">
						<input name="html"  type='hidden' value='{$submenu['html']}'>
					<input name="change_page_id" type="hidden" value="{$submenu['id']}">
					<input  type="submit" value="Редактировать страницу">
				</form>
				

				
here;
				
			}
			if ($menu_number==0)
			{	
				print<<<here
				<br>
				<form action="change_menu.php" method="post">
						
						<input name="parent_id" type="hidden" value="{$menu['id']}">
						<input name="place" type="hidden" value="{$menu_number}">
						<input name="create_menu_text" type="text" >
						<input  type="submit" value="Создать подпункт меню">
				</form>
				
here;
			}	
			print "</ul>";		
		}
	//	if ($menu_number!=0)
	//	{
				print<<<here
				
				<form action="change_menu.php" method="post">
						
						<input name="parent_id" type="hidden" value="0">
						<input name="place" type="hidden" value="{$menu_number}">
						<input name="create_menu_text" type="text" >
						<input  type="submit" value="Создать пункт меню">
				</form>			
here;
		//}
	}

	change_menu(0,$conn);

	change_menu(1,$conn);
	
	change_menu(2,$conn);
	
	
	
?>


 
</body>
</html>