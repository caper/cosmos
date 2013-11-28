<?php  


//if($_GET['pwd']=="n32-97dsnbmas-0;df488u5z?<mxncdsf9**&$@)")
//{



				
		/*function line_result($invited,$line_number,$percent) {
			global $count;
			$line_percent=0;
			if($line_number<5) {
				$sql="select sum(week_total),id from users where invited = {$invited}";
				//echo $sql.'<br>';
				$result=db1::query($sql);
				
				while($user=mysql_fetch_assoc($result)) {
					$count++; echo $count.'<br>';
					$cur_percent=$user['week_total']*$percent[$line_number];
					//print " {$line_number}: {$user['week_total']}*{$percent[$line_number]} = {$cur_percent} <br>";
					$next_line=$line_number+1;
					$line_percent = $line_percent+$cur_percent+line_result($user['id'],$next_line,$percent);
					}
				
			}
			//echo '+';
			return $line_percent;			
		}	*/
		
		function line_result($sql,$line_number,$percent,$conn) {
			global $count;
			$line_percent=0;
			if($line_number<7) {
				$next_sql="select id,week_total from users where id=1000000";
				//echo $sql.'<br>';
				$result=mysql_query($sql,$conn);
				
				while($user=mysql_fetch_assoc($result)) {
					$count++; 
					//echo $count.'<br>';
					$cur_percent=$user['week_total']*$percent[$line_number];
					//print " {$line_number}: {$user['week_total']}*{$percent[$line_number]} = {$cur_percent} <br>";
					$next_sql.=" OR invited = {$user['id']}";
					$line_percent = $line_percent+$cur_percent;
					}
				$next_line=$line_number+1;
				return $line_percent+line_result($next_sql,$next_line,$percent,$conn);
			}
			echo '+';
			return $line_percent;			
		}
		
		
		$conn=mysql_connect("185.12.92.117","user","n6mBvRfk");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);
		
		ini_set('max_execution_time', 999);
		set_time_limit(0);
		
		$sql2=<<<here
						SELECT id 
		FROM  `users` 
here;
					
		$result2=mysql_query($sql2,$conn);
			$percent= array(0,0.005,0.005,0.03,0.05,0.05,0.05,0.05,0.07,0.07,0.07,0.07,0.07,0.08,0.08,0.08);
			//echo "<pre>"; print_r($percent); echo "</pre>";
			
		while($row2=mysql_fetch_assoc($result2))
		{	
			global $count;
			$count=0;
			$sql="select id,week_total from users where invited={$row2['id']}";
			$total=line_result($sql,1,$percent,$conn);
		//	echo $row['subdomen'].' result:'.$total;
			echo $row2['id'].' result:'.$total;
			echo " count: {$count}\n";
					
			
			if($total!=0)
			{
				$sql3=<<<here
				UPDATE  `cosmos`.`users` SET  `total` =  `users`.`total`+'{$total}' WHERE  `users`.`id` ={$row2['id']};
here;
				//echo $sql;
				$result3=mysql_query($sql3,$conn);
			}				
			//echo "<br>";
		}
		/*
		$sql=<<<here
				UPDATE  `cosmos`.`users` SET  `week_total` =  '0';
here;
				
		$result2=db1::query($sql);*/
		
		
//}



?>