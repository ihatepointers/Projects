<?php
$nereden = filter_input(INPUT_POST, 'nereden');
$nereye = filter_input(INPUT_POST, 'nereye');
$gidisTarihi = filter_input(INPUT_POST, 'gidisTarihi');
$seferSaati = filter_input(INPUT_POST, 'seferSaati');
$firma = filter_input(INPUT_POST, 'firma');
$user = 'root';
$pass = '';
$db = 'seferlerdb';

//Create connection
$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
mysqli_set_charset($db, "utf8");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>Acknowledgement</title>
	</head>
	
	<body>		
			<script>
			</script> 
			
			<?php
				#echo "$nereden";
				#echo "$nereye";
				$seferSaati = $seferSaati.':00';
				#echo "$gidisTarihi";
				#echo "$seferSaati";
				#echo "$firma";
				$result = mysqli_query($db, "SELECT COUNT(*) FROM seferler");
				$idArray = mysqli_fetch_assoc($result);
				$id = $idArray['COUNT(*)'];
				$id += 1;
				
				$sql = "INSERT INTO `seferler` (`Nereden`, `Nereye`, `Saat`, `Tarih`, `Firma`, `SeferID`) VALUES ('$nereden', '$nereye', '$seferSaati', '$gidisTarihi', '$firma', '$id')";
				if(mysqli_query($db, $sql)){
					echo "Sefer başarıyla eklendi. Anasayfaya dönmek için ";
					echo "<a href='basariliGiris.php' class='cikisYapLink'>Buraya</a>";
					echo " tıklayınız";
				}else{
					echo "Sefer eklenirken hata oluştu. Anasayfaya dönmek için ";
					echo "<a href='basariliGiris.php' class='cikisYapLink'>Buraya</a>";
					echo " tıklayınız";
				}
				
			?>
			
		
		
		
	</body>
</html>