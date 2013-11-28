<?php header('Content-type: text/html; charset=utf-8'); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="css/style.css">
	<script src="js/jquery-2.0.2.js"></script>
	<!--script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script-->
<style>
#cube { cursor:pointer;}
</style>

	<script src="js/cube2.js"></script>
	<script src="js/menu.js"></script>
</head>
<body>

<?php
        $conn=mysql_connect("127.0.0.1","user","4ddmvxc93");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);

		$sql=<<<here
		SELECT html from menu where id={$_GET['id']};
here;
		$result=mysql_query($sql,$conn);

		$row=mysql_fetch_assoc($result);
		echo $row['html'];
?>

</body>
</html>