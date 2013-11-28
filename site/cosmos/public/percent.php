<?php  header('Content-type: text/html; charset=utf-8'); 


if($_GET['pwd']=="n32-97dsnbmas-0;df488u5z?<mxncdsf9**&$@)")
{


		$conn=mysql_connect("localhost","user","n6mBvRfk");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);

				
		function line($id_child_array,$conn,$i,$percent,$id_main,$result_sv)
		{
			if(isset($id_child_array)&&i<=15)
			{
				
				$id_array=$id_child_array;
				//echo "<pre>"; print_r($id_array); echo "</pre>";
				unset($id_child_array);
				foreach($id_array as $key=>$value)
				{
					$sql=<<<here
						SELECT * 
						FROM  `users` 
						WHERE  `invited` = {$value}

here;
					
					$result=mysql_query($sql,$conn);
					if(mysql_num_rows($result)!=0)
					{
						print "<b>{$i} линия: </b> ";
						
						while($row=mysql_fetch_assoc($result))
						{
							$id_child_array[]=$row['id'];
							
							$result_line_sv=$result_line_sv+$row['week_total']*$percent[$i];
							echo $row['subdomen'].' , ';
							echo $row['week_total']*$percent[$i].' | ';
						}
						
						echo '<br>';
						$i++;
					}
					
				}
				
				return $result_line_sv+line($id_child_array,$conn,$i,$percent,$id_main,0);
			}

		}	
		
		$sql=<<<here
						SELECT * 
		FROM  `users` 
here;
					
		$result=mysql_query($sql,$conn);
			$percent= array(0,0.005,0.005,0.03,0.05,0.05,0.05,0.05,0.07,0.07,0.07,0.07,0.07,0.08,0.08,0.08);
			//echo "<pre>"; print_r($percent); echo "</pre>";
			
		while($row=mysql_fetch_assoc($result))
		{
			echo $row['subdomen'].":<br>";
			$array[0]=$row['id'];
			
			$result_sv=line($array,$conn,1,$percent,$row['id'],0);
			echo 'result:'.$result_sv;
			if($result_sv!=0)
			{
				$sql=<<<here
				UPDATE  `cosmos`.`users` SET  `total` =  `users`.`total`+'{$result_sv}' WHERE  `users`.`id` ={$row['id']};
here;
				echo $sql;
				$result2=mysql_query($sql,$conn);
			}				
			echo "<br><br>";
		}
		
		$sql=<<<here
				UPDATE  `cosmos`.`users` SET  `week_total` =  '0';
here;
				
				$result2=mysql_query($sql,$conn);
		
		
}



?>