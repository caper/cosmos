<?php  header('Content-type: text/html; charset=utf-8'); 


if($_GET['pwd']=="n32-97dsnbmas-0;df488u5z?<mxncdsf9**&$@)")
{

		$conn=mysql_connect("localhost","user","n6mBvRfk");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);

				
		$sql=<<<here
			SELECT * 
FROM  `users` 

here;

		$result=mysql_query($sql,$conn);
			
		while($user=mysql_fetch_assoc($result))
		{
			$week_total=0;
		//	print "cva0|cva1|cvr|cvl|week_total<br>";
		//	print "{$user['cva0']}|{$user['cva1']}|{$user['cvr']}|{$user['cvl']}|{$user['week_total']}<br>";
			if($user['cva0']>$user['cva1']) 
			{
				$cva0=$user['cva0']-$user['cva1'];
				$week_total=$week_total+$user['cva1'];
				$cva1=0;
				
			}	
			else  
			{
				$cva1=$user['cva1']-$user['cva0'];
				$week_total=$week_total+$user['cva0'];
				$cva0=0;
			}
		//	print "{$cva0}|{$cva1}|{$user['cvr']}|{$user['cvl']}|{$week_total}<br>";
			
			
			if($cva1>$user['cvr']) 
			{
				$cva1=$cva1-$user['cvr'];
				$week_total=$week_total+$user['cvr'];
				$cvr=0;
				
			}	
			else  
			{
				$cvr=$user['cvr']-$cva1;
				$week_total=$week_total+$cva1;
				$cva1=0;
			}
		//	print "{$cva0}|{$cva1}|{$cvr}|{$user['cvl']}|{$week_total}<br>";
			
			
			
			if($cva0>$user['cvl']) 
			{
				$cva0=$cva0-$user['cvl'];
				$week_total=$week_total+$user['cvl'];
				$cvl=0;
				
			}	
			else  
			{
				$cvl=$user['cvl']-$cva0;
				$week_total=$week_total+$cva0;
				$cva0=0;
			}
			
			switch($user['pack'])
			{
				case 1: $week_total=$week_total*0.2; break;
				case 2: $week_total=$week_total*0.35; break;
				case 3: $week_total=$week_total*0.5; break;
			}
			
		//	print "{$cva0}|{$cva1}|{$cvr}|{$cvl}|{$week_total}<br>";
			$sql=<<<here
				UPDATE  `cosmos`.`users` SET  `cva0` =  '{$cva0}', `cva1` =  '{$cva1}',
				`cvr` =  '{$cvr}', `cvl` =  '{$cvl}', `week_total` =  '{$week_total}',
				`total` =  `users`.`total`+'{$week_total}'
				WHERE  `users`.`id` ={$user['id']};
here;
				//echo $sql;
				$result2=mysql_query($sql,$conn);
			
		}
}



?>