<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="../js/jquery-2.0.2.js"></script>
<script src="../js/login.js"></script>



</head>
<body>
<?php 
session_start();
if(isset($_SESSION['id'])) //если пользователь авторизовался( у него появляется переменная id)
{?>
<div id="wrap">
	
	<div id="work_window"><!--центральное большое окно -->
	
	</div>
	<div id="left_top">
		<div id="id1"><div id="line1"></div><span>Учет</span></div>
		<div id="id2"><div id="subline2"></div><div id="line2"></div><span>Станции</span></div>
		<div id="id3"><div id="line3"></div><span>Конференции</span></div>
		<div id="id4"><div id="line4"></div><span>Моя структура</span></div>
		<div id="id5"><div id="line5"></div></div>
	</div>	
	
	
	<div id="left_bottom">
		<div id="id6"><div id="subline6"></div><div id="line6"></div><div id="subsubline6"></div><span>Функции</span></div>
		
		<div id="id8"><div id="line8"></div><span>Социальные сети</span></div>
		<div id="id7"><div id="line7"></div><span>Связь</span></div>
		<div id="id9"><div id="subline9"></div><div id="line9"></div><span>Настройка системы</span></div>

		<div id="id10"><div id="subline10"></div><div id="line10"></div><span>Мои идеи</span></div>
		<div id="id10_1"><div id="subline10_1"></div><div id="line10_1"></div><span>Блоги</span></div>
	
	</div>
	
	<div id="id11"><div id="line11"></div><span>Записная книжка</span></div>
	
	<div id="right_bottom">
		<div id="id12"><div id="line12"></div><span>Финансы</span></div>	
		<div id="id13"><div id="subline13"></div><div id="line13"></div><span>Сообщения</span></div>
	</div>
	
</div>
<?php }
else header ('Location: index.php');	//если пользователь не павторизован его перенапрявляет на страницу входа	
		?>
</body>
</html>