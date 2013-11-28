<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" type="text/css" href="../css/lar.css">

<script src="../js/jquery-2.0.2.js"></script>


<script>
$( document ).ready(function() {
	$( "#a" ).on( "click", function(){

		$("#a_show").css({'display':'block'});
		$("#r_show").css({'display':'none'});
		$("#l_show").css({'display':'none'});
	});
	$( "#r" ).on( "click", function(){

		$("#a_show").css({'display':'none'});
		$("#r_show").css({'display':'block'});
		$("#l_show").css({'display':'none'});
	});
	$( "#l" ).on( "click", function(){

		$("#a_show").css({'display':'none'});
		$("#r_show").css({'display':'none'});
		$("#l_show").css({'display':'block'});
	});
});
</script>

</head>
<body>
<div id="all">
<?php
session_start();
//echo $_SERVER['HTTP_HOST'];
$conn=mysql_connect("localhost","user","4ddmvxc93");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);	
		

		
function show_lar($id,$name,$id_div)
{
if (!empty($id))
		{
	print<<<here
	<div class="a{$id_div}"><form method="post">
	<input type="submit" value="{$name}">
	<input type="hidden" value="{$id}" name="id">
	</form></div>
	
	<div class="line{$id_div}"></div>
here;
	}
}


function right_wing($parent,$side,$r,$l,$conn,$id_div,$type)
{
	
	$sql=<<<here
	SELECT * from users where parent={$parent} and side={$side} and type={$type};
here;
	$result=mysql_query($sql,$conn);
	$id_div++;
	if (!empty($result))
		{	
		$user=mysql_fetch_assoc($result);
		}
	
	show_lar($user['id'],$user['subdomen'],$id_div);
	
	if($r<=2)
	{
		$r++;
		
		right_wing($user['id'],1,$r,$l,$conn,&$id_div,$type);
	}
	
	if($l<=1)
	{
		$l++;
		
		right_wing($user['id'],0,4,$l,$conn,&$id_div,$type);
	}
}

function left_wing($parent,$side,$r,$l,$conn,$id_div,$type)
{
	
	$sql=<<<here
	SELECT * from users where parent={$parent} and side={$side} and type={$type};
here;
	$result=mysql_query($sql,$conn);
	$id_div++;
		if (!empty($result))
		{
			$user=mysql_fetch_assoc($result);
			
		}
	show_lar($user['id'],$user['subdomen'],$id_div);
	
	if($l<=2)
	{
		$l++;
		
		left_wing($user['id'],0,$r,$l,$conn,&$id_div,$type);
	}
	
	if($r<=1)
	{
		$r++;
		
		left_wing($user['id'],1,$r,4,$conn,&$id_div,$type);
	}
}

if(isset($_SESSION['id']))
{
	$subdomen = explode(".", $_SERVER['HTTP_HOST']);
	$subdomen=$subdomen[0];

				$sql=<<<here
			SELECT * from users where subdomen='{$subdomen}';
here;

		$result=mysql_query($sql,$conn);
			
		$user2=mysql_fetch_assoc($result);
		
		print<<<here
		Здравствуйте, {$_SESSION['subdomen']}. 
		<a href='permit.php'>Подтвердить нового пользователя</a> |
		<a href='lar.php'>Свой офис</a>
		
		<div id="arl">
			<button id="a">a</button>
			<button id="r">r</button>
			<button id="l">l</button>
		
		</div>
here;
		
		
		if(!isset($_POST['id'])) $_POST['id']=$_SESSION['id'];	
			
			$sql=<<<here
			SELECT * from users where id={$_POST['id']};
here;

		$result=mysql_query($sql,$conn);
			
		$user=mysql_fetch_assoc($result);
		
		show_lar($_POST['id'],$user['subdomen'],0);
			print "<div id='a_show' >";
		right_wing($_POST['id'],1,1,0,$conn,0,0);
		
		left_wing($_POST['id'],0,0,1,$conn,9,0);
			print "</div>";
			
			print "<div id='r_show' style='display:none'>";
		right_wing($_POST['id'],1,1,0,$conn,0,0);
		
		left_wing($_POST['id'],0,0,1,$conn,9,1);
			print "</div>";
			
			print "<div id='l_show' style='display:none'>";
		right_wing($_POST['id'],1,1,0,$conn,0,2);
		
		left_wing($_POST['id'],0,0,1,$conn,9,0);
			print "</div>";
}
else header ('Location: index.php');		
		?>


	
</div>
</body>
</html>