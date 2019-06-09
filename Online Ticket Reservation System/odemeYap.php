<?php

$user = 'root';
$pass = '';
$db = 'seferlerdb';

//Create connection
$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Acknowledgement</title>
	</head>
	
	<body>
		<h4>Ödemeniz tamamlandı. Aşağıda bilgileri bulunan bileti aldınız..</h4>
		
		<script>
		 
		var queryString = decodeURIComponent(window.location.search);
		queryString = queryString.substring(1);
		var queries = queryString.split("&");
		//for (var i = 0; i < queries.length; i++)
		//{
		  //document.write(queries[i] + "<br>");
		  
		//}
		
		var seferNo = queries[0].replace('para1=','');
		var koltukNo = queries[1].replace('para2=','');
		var email = queries[2].replace('para2=','');
		</script> 
		
		<?php
			$seferNo = $_GET['para1'];
			$koltukNo = $_GET['para2'];
			$email = $_GET['para3'];
			#echo "$seferNo ", "$koltukNo ";
			$PNR = generateRandomString(4);
			//echo " $seferNo  $koltukNo $PNR";
			$sql = "INSERT INTO `biletler` (`PNR`, `SeferID`, `KoltukNo`) VALUES ('$PNR', '$seferNo', '$koltukNo')";
			while(!mysqli_query($db,$sql)){
				$PNR = generateRandomString(4);
				$sql = "INSERT INTO `biletler` (`PNR`, `SeferID`, `KoltukNo`) VALUES ('$PNR', '$seferNo', '$koltukNo')";
				
			}
			
			if($email == 'bos'){
				echo "Üye girişi yapmadan bilet aldiniz. PNR numaranızı unutmayın.";
			}else{
				echo "$email";
			}
			
			$sql = "SELECT * FROM `kullanicilar` WHERE `email` LIKE '$email'";
			$result = mysqli_query($db, $sql) or die("Bad query: $sql");
			
			while($row = mysqli_fetch_assoc($result)){
				$myString = $row['biletler'];
			}
			if(empty($myString)){
				$myString = $PNR;
			}else{
				$myString = $myString.",";
				$myString = $myString.$PNR;
			}
			$sql = "UPDATE `kullanicilar` SET `biletler` = '$myString' WHERE `kullanicilar`.`email` = '$email'";
			mysqli_query($db,$sql);
			
			echo "</br>";
			echo"Ödeme Tamamlandı. PNR Numaranız: $PNR";
			
			function generateRandomString($length = 4) {
				$characters = '0123456789ABCDEFGHJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				return $randomString;
			}
		?>
		
		
		
		
	</body>
</html>