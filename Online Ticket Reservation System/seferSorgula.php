<?php
session_start();
$nereden = filter_input(INPUT_POST, 'nereden');
$nereye = filter_input(INPUT_POST, 'nereye');
$gidisTarihi = filter_input(INPUT_POST, 'gidisTarihi');
$gidisDonus = filter_input(INPUT_POST, 'gidisDonus');

if (isset($_SESSION['email'])){
	$email = $_SESSION['email'];
}else{
	$email = "bos";
}
#echo $email;


$user = 'root';
$pass = '';
$db = 'seferlerdb';

//Create connection
$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");
mysqli_set_charset($db, "utf8");
if(empty($nereden)){
	echo "Nereden bilgisi eksik...\n";
}else if(empty($nereye)){
	echo "Nereye bilgisi eksik...\n";
}else if (empty($gidisTarihi)){
	echo "Gidiş tarihi bilgisi eksik...\n";
}else{
	//echo "Sorgu:\n";
	
	
	
	$sql = "SELECT * FROM `seferler` WHERE `Nereden` LIKE '$nereden' AND `Nereye` LIKE '$nereye' AND `Tarih` = '$gidisTarihi'";
	
	//echo $sql;
	
	$result = mysqli_query($db, $sql) or die("Bad query: $sql");

	//echo"\n\n";
	//echo"<table border='1'>";
	//echo "<tr><td>Nereden</td><td>Nereye</td><td>Tarih</td><td>Saat</td><td>Firma</td></tr>\n";
	//while($row = mysqli_fetch_assoc($result)){
		//echo "<tr><td>{$row['Nereden']}</td><td>{$row['Nereye']}</td><td>{$row['Tarih']}</td><td>{$row['Saat']}</td><td>{$row['Firma']}</td></tr>\n";
	//}
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>

<!DOCTYPE html>
<html lang="tr" dir="ltr">
  <head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<meta charset="UTF-8"/>
	<script src = "/jquery/jquery-3.4.0.min.js"></script>
    <title>Seferler Sorgusu</title>
    <style>
		.seferKutusu{
			padding: 20px; 
			width: 750px;
			height: 50px; 
			position: relative;
			margin-left: auto;
			margin-right : auto;
			word-wrap: break-word;
			margin-bottom: 10px;
		}
		
		.seferKutusuBuyuk{
			padding: 20px; 
			width: 750px;
			height: 270px; 
			position: relative;
			margin-left: auto;
			margin-right : auto;
			word-wrap: break-word;
			margin-bottom: 10px;
		}
		
		.seferKutusu:hover{
			background-color: #F8F8F8;
		}
		
		.seferKutusu2{
			border-bottom: 3px solid lightgray;
			width: 750px;
			height: 10px; 
			position: relative;
			margin-left: auto;
			margin-right : auto;
			word-wrap: break-word;
			margin-bottom: 10px;
		}
		
		.koltuklar{
			
			margin-top:20px;
			width: 700px;
			height: 270px; 
			position: relative;
			margin-left: auto;
			margin-right : auto;
			word-wrap: break-word;
		}
		

		
		
		.seferYazisi{
			position: relative;
			margin-left: auto;
			margin-right : auto;
			
		}
		
		.firmaKutusu{
			padding-left:20px;
			margin-left:20px;
			width: 100px;
			position: relative;
			margin-left: auto;
			margin-right : auto;
			top: 50%;
			transform: translateY(-50%);
			display:inline-block;
		}
		
		.saatKutusu{
			padding-left:20px;
			width: 100px;
			position: relative;
			margin-left: auto;
			margin-right : auto;
			top: 50%;
			transform: translateY(-50%);
			display:inline-block;
		}
		
		.rotaKutusu{
			padding-left:30px;
			width: 200px;
			position: relative;
			margin-left: auto;
			margin-right : auto;
			top: 50%;
			transform: translateY(-50%);
			display:inline-block;
		}
		
		.ozellikKutusu{
			width: 100px;
			position: relative;
			margin-left: auto;
			margin-right : auto;
			top: 50%;
			transform: translateY(-50%);
			display:inline-block;
		}
		
		input[type=submit] {
			margin-left: 30px;
			width: 100px;
			position: relative;
			margin-right : auto;
			display:inline-block;
		}
		
		.baslangicCizgisi{
			border-bottom: double;
		}
		
		.centered {
			opacity: 0.8;
			position: absolute;
			top: 50%;
			left: 35%;
			transform: translate(-50%, -50%);
		}
		
		
		.koltukSVG {
			width: 47px;
			height: 35px;
			opacity: 0.9;
		}
		
		.koltukSVGSecili {
			width: 47px;
			height: 35px;
			opacity: 0.9;
		}
		
		
		.koltuk{
			position: relative;
			text-align: center;
			display:inline-block;
			width: 45px;
			height: 37px;
			word-wrap: break-word;
			padding-right: 2px;
		}
		
		
		.odemeYapButon {
			margin-top: 30px;
			float:right;
		}
		
    </style>
	
	
  </head>
  <body>
    <?php
		echo "</br>";
		echo "</br>";
		echo "</br>";
		echo "<div class='seferKutusu2'>";
			echo "<div class='seferYazisi'>";
				
				echo "<div class='firmaKutusu'>";
					echo "Firma";
				echo "</div>";
				
				echo "<div class='saatKutusu'>";
					echo "Saat";
				echo "</div>";
				
				echo "<div class='rotaKutusu'>";
					echo "Nereden > ";
					echo "Nereye";
				echo "</div>";
				
				echo "<div class='ozellikKutusu'>";
					echo "Özellikler";
				echo "</div>";
					
			echo "</div>";
		echo "</div>";
	
		$result = mysqli_query($db, $sql) or die("Bad query: $sql");
		$i=0;
		while($row = mysqli_fetch_assoc($result)){
		echo "<div class='seferKutusu' id='{$row['SeferID']}'>";
				echo "<div class='seferYazisi'>";
				
					echo "<div class='firmaKutusu'>";
						echo "{$row['Firma']}";
					echo "</div>";
					
					echo "<div class='saatKutusu'>";
						echo "{$row['Saat']}";
					echo "</div>";
					
					echo "<div class='rotaKutusu'>";
						echo "{$row['Nereden']} > ";
						echo "{$row['Nereye']}";
					echo "</div>";
					
					echo "<div class='ozellikKutusu'>";
						echo "2+1";
					echo "</div>";
					
					echo "<div class='ozellikKutusu'>";
						echo " <input type='button' class='koltukSecButonu' value='Koltuk Sec' id='buton {$row['SeferID']}'> ";
					echo "</div>";
					
					$koltukNo=0;
					$seferID1=$row['SeferID'];
					$sqlKoltuk="SELECT `KoltukNo` FROM `biletler`,`seferler` WHERE seferler.SeferID = biletler.SeferID AND seferler.seferID = '$seferID1'";
					$resultKoltuk = mysqli_query($db, $sqlKoltuk) or die("Bad query: $sql");
					$koltukDizisi = []; 
					while($row2 = mysqli_fetch_assoc($resultKoltuk)){
						array_push($koltukDizisi, $row2['KoltukNo']);
					}
					console_log($koltukDizisi);
					echo "<div class='koltuklar' style='display: none;'>";
						while($i<7){
							echo "<div class='koltuk'>";
							if (in_array("$koltukNo", $koltukDizisi)) {
								echo "<img src='koltukmavi.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}else{
								echo "<img src='koltukgri.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}
							$koltukNo= $koltukNo+3;
							echo "<div class='centered'>$koltukNo</div>";
							$i++;
							echo "</div>";
						}
						$i=0;
						
						echo "<div class='koltuk'>";
						echo "</div>";
						
						echo "<div class='koltuk'>";
						echo "</div>";
						
						
						while($i<5){
							echo "<div class='koltuk'>";
							if (in_array("$koltukNo", $koltukDizisi)) {
								echo "<img src='koltukmavi.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}else{
								echo "<img src='koltukgri.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}
							$koltukNo= $koltukNo+3;
							echo "<div class='centered'>$koltukNo</div>";
							$i++;
							echo "</div>";
						}
						$i=0;
						
						echo "</br>";
						
						$koltukNo=-1;
						while($i<7){
							echo "<div class='koltuk'>";
							if (in_array("$koltukNo", $koltukDizisi)) {
								echo "<img src='koltukmavi.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}else{
								echo "<img src='koltukgri.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}
							$koltukNo= $koltukNo+3;
							echo "<div class='centered'>$koltukNo</div>";
							$i++;
							echo "</div>";
						}
						$i=0;
						
						echo "<div class='koltuk'>";
						echo "</div>";
						
						echo "<div class='koltuk'>";
						echo "</div>";
						
						
						while($i<5){
							echo "<div class='koltuk'>";
							if (in_array("$koltukNo", $koltukDizisi)) {
								echo "<img src='koltukmavi.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}else{
								echo "<img src='koltukgri.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}
							$koltukNo= $koltukNo+3;
							echo "<div class='centered'>$koltukNo</div>";
							$i++;
							echo "</div>";
						}
						$i=0;
						
						echo "</br>";
						
						echo "<div class='koltuk'>";
						echo "</div>";
						
						echo "</br>";
						
						$koltukNo=-2;
						while($i<14){
							echo "<div class='koltuk'>";
							$koltukNo= $koltukNo+3;
							if($koltukNo == 40){
								$koltukNo = 38;
							}
							if (in_array("$koltukNo", $koltukDizisi)) {
								echo "<img src='koltukmavi.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}else{
								echo "<img src='koltukgri.svg' alt='koltuk' class='koltukSVG' id='sefer $seferID1 koltuk $koltukNo'>";
							}
							//echo "<object data='koltuk.svg' type='image/svg+xml' class='koltukSVG' style='fill=#B86E25'></object>";
							//echo "<svg class='koltukSVG' viewBox='0 0 32 32'> <use xlink:href='#koltukSVG' class='green'></use> </svg>";
							
							echo "<div class='centered'>$koltukNo</div>";
							$i++;
							echo "</div>";
							
						}
						$i=0;
						echo "</br>";
						echo "<button type='button' class='odemeYapButon'>Ödeme Yap</button>";
					
					
					echo "</div>";
					
				echo "</div>";
			echo "</br>";
			echo "</div>";
			echo "<div>";
			echo "<div class='seferKutusu2'>";		
			echo "</div>";
			
		echo "</div>";
		}
	?>
	
	<script type="text/javascript">
	var seciliKoltuk;
	var seciliSefer;
	var email = "<?php echo $email ?>"; 
	
	seciliKoltuk=0;
	seciliSefer=0;
	console.log("secili sefer" + seciliSefer);
	console.log("secili koltuk" + seciliKoltuk);
	$( document ).ready(function() {
		$('.koltuk').mouseenter(function (){
			//console.log($(this).find('img').attr('src'));
			console.log($(this).find('img').attr('alt'));
			if(($(this).find('img').attr('src') == 'koltukgri.svg')&&(($(this).find('img').attr('alt')) != "secili")){
				$(this).find('img').attr('src','koltukyesil.svg');
			}
			
			
		})
		$('.koltuk').mouseleave(function (){
			//console.log($(this).find('img').attr('src'));
			if(($(this).find('img').attr('src') == 'koltukyesil.svg')&&(($(this).find('img').attr('alt')) != "secili")){
				$(this).find('img').attr('src','koltukgri.svg');
			}
		})
		$( '.koltuk' ).click(function() {
			var koltukID = $(this).find('img').attr('id');
			console.log(koltukID);
			koltukIDsplit = koltukID.split(' ');
			if(koltukID){
				if(seciliSefer == 0){
					seciliSefer = parseInt(koltukIDsplit[1]);
					seciliKoltuk = parseInt(koltukIDsplit[3]);
					$(this).find('img').attr('alt','secili');
					$(this).find('img').attr('src','koltukyesil.svg');
				}else{
					var silinecekKoltuk = ("sefer " + seciliSefer + " koltuk " + seciliKoltuk);
					//console.log("silinecek");
					//console.log(silinecekKoltuk);
					document.getElementById(silinecekKoltuk).alt = "koltuk";
					document.getElementById(silinecekKoltuk).src = "koltukgri.svg";
					seciliSefer = parseInt(koltukIDsplit[1]);
					seciliKoltuk = parseInt(koltukIDsplit[3]);
					$(this).find('img').attr('alt','secili');
					$(this).find('img').attr('src','koltukyesil.svg');
				}
			}
			console.log("secili sefer" + seciliSefer);
			console.log("secili koltuk" + seciliKoltuk);
			
		})
		
		$( '.odemeYapButon' ).click(function() {
			var queryString = "?para1=" + seciliSefer + "&para2=" + seciliKoltuk + "&para3=" + email + "&para4=" + gidisDonus ;
			window.location.href = "odemeYap.php"+queryString;
		});
	});
	
	</script>
	
	
	
	
	<script>
	var buyukKutuID;
	var koltukDiv;
	buyukKutuID = 0;
	$('.koltukSecButonu').on('click', function(){
		var seferID =  $(this).attr('id').match(/\d+/)[0];
		console.log(seferID);
		//document.getElementById($(this).closest('ul').attr('id');).className = "seferKutusuBuyuk"
		if(buyukKutuID == 0){
			console.log(document.getElementById(seferID));
			document.getElementById(seferID).className = "seferKutusuBuyuk";
			buyukKutuID=seferID;
			console.log(document.getElementById(seferID));
			console.log(document.getElementById(seferID).childNodes[0]);
			var doc = document.getElementById(seferID).childNodes[0];
			for (var i = 0; i < doc.childNodes.length; i++) {
				console.log(doc.childNodes[i].className);
				if (doc.childNodes[i].className == "koltuklar") {
				  break;
				}        
			}
			koltukDiv= doc.childNodes[i];
			koltukDiv.style.display = 'block';
			//doc.childNodes[i].style.display = 'block';
		}else if(seferID == buyukKutuID){
			document.getElementById(buyukKutuID).className = "seferKutusu";
			koltukDiv.style.display = 'none';
			buyukKutuID = 0;
		}else{
			koltukDiv.style.display = 'none';
			document.getElementById(buyukKutuID).className = "seferKutusu";
			var doc = document.getElementById(seferID).childNodes[0];
			for (var i = 0; i < doc.childNodes.length; i++) {
				//console.log(doc.childNodes[i].className);
				if (doc.childNodes[i].className == "koltuklar") {
				  break;
				}        
			}
			koltukDiv= doc.childNodes[i];
			koltukDiv.style.display = 'block';
			document.getElementById(seferID).className = "seferKutusuBuyuk";
			buyukKutuID = seferID;
		}
	});
	</script>
	

  </body>
  
  
</html>