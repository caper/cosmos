

<?
function new_user($subdomain,$parent,$type,$side,$conn)
{
		$cv=450;
		$salt = '9eeeab13-050a-4c27-8cf9-afe003be71c1';
		$password = md5($salt.$subdomain);
		$sql = <<<here
		INSERT INTO `cosmos`.`users`(`type` ,`subdomen` ,`parent` ,`side`,
		`pv`, `cva0`, `cva1`, `cvr`, `cvl`,`invited`,
		`password`,`active`,`pack`)
		VALUES (
		'{$type}', '{$subdomain}',  '{$parent}',  '{$side}',
		'1470', '0','0','0','0','{$parent}',
		'{$password}','1', '3'
		)
here;
	$result=mysql_query($sql,$conn);
	//echo $sql.'<br>';
	$new_id=mysql_insert_id();
	echo $new_id."<br>";
//прибавление cv наверх
	$sql = "SELECT parent,side,type FROM  `users` WHERE  `id` ={$new_id}";
	//echo $sql.'<br>';
                    $result=mysql_query($sql,$conn);
                    $row = mysql_fetch_assoc($result);
                    $side = $row['side'];
					
                    $current_id = 1; //чтобы просто не равнялось нулю
                    while($current_id != 0) {
                        switch($row['type']) {
                            case 0: $type_sv = "cva"; $side = $row['side']; break;
                            case 1: $type_sv = "cvr";  $side = ""; break;
                            case 2: $type_sv = "cvl";  $side = ""; break;
                        }
						
                        $sql2 = "UPDATE  `cosmos`.`users` SET  `{$type_sv}{$side}` =  `users`.`{$type_sv}{$side}`+'{$cv}' WHERE  `users`.`id` = {$row['parent']} AND `users`.`active` = 1";
                        $result=mysql_query($sql2,$conn);
						//echo $sql2.'<br>';
                        $current_id = $row['parent'];
                        $sql = "SELECT parent,side,type FROM  `users` WHERE  `id` ={$current_id}";
                      //  echo $sql.'<br>';
						$result=mysql_query($sql,$conn);
                        $row = mysql_fetch_assoc($result);
					
                    }
		 $sql=<<<here
		 INSERT INTO  `cosmos`.`pay_statistic` (
		`id` ,
		`date` ,
		`count` ,
		`pack`,
		`buy_type`,
		`PAYER_PURSE`
		)
		VALUES (
		'{$new_id}', 
		CURRENT_TIMESTAMP ,  '1470',  '3', 'Покупка', 'тестовый кошелек'
		);
here;
		$result=mysql_query($sql,$conn);
		return $new_id;			
}
		
function line($id_parent_array,$i,$conn)
{
	
	//if($i<=15000) {
	if(count($id_parent_array)<=9000) {
		$id_array=$id_parent_array;
		//echo "<pre>"; print_r($id_array); echo "</pre>";
		unset($id_parent_array);
		foreach($id_array as $key=>$parent)
		{
		
			$id_parent_array[]=new_user($i,$parent,0,0,$conn);
				$i++;
			$id_parent_array[]=new_user($i,$parent,0,1,$conn);
				$i++;	
			//$id_parent_array[]=new_user($i,$parent,1,0,$conn);
			//	$i++;
			//$id_parent_array[]=new_user($i,$parent,2,1,$conn);
			//	$i++;

				
		}
		//echo  $i.'<br>';
		line($id_parent_array,$i,$conn);
	}

}

		$conn=mysql_connect("185.12.92.117","user","n6mBvRfk");
		mysql_select_db("cosmos",$conn);

		mysql_query('SET NAMES "utf8"', $conn);
		$array=array('1');
		line($array,0,$conn);
	
	
		?>


