<?php
$PNR = filter_input(INPUT_POST, 'PNR');
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src = "/jquery/jquery-3.4.0.min.js"></script>
		<title>PNR Sorgulama</title>
		<style type="text/css">
			#main{
				background: url(background.jpg);
				background-size: cover;
			}
			.hizlial{
				width: 800px;
				height: 500px;
				margin-left: auto;
				margin-right: auto;
			}
			
			body{
				margin: 0;
				padding: 0;
			}
			
			#bilgigir{
				border-radius: 25px;
				background: #FFFFFF;
				padding: 20px; 
				width: 750px;
				height: 300px; 
				position: relative;
				margin-top:70px;
				margin-left: auto;
				margin-right: auto;
				word-wrap: break-word;
			}
			
			
			
			.row{
				display: inline-block;
				width: 250px;
				height: 90px;
				margin-right: 50px;
			}
			
			.rowUst{

				width: 250px;
				height: 80px;
				margin-right: 5px;
			}
			
			.row2{
				display: inline-block;
				width: 250px;
				height: 90px;
			}
			
			label{
				display: inline-block;
				margin-bottom: 10px;
			}
			
			[type="date"] {
			  background: #FFFFFF
				url(https://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/calendar_2.png)
				97% 50% no-repeat;
				width: 240px;
				box-sizing: border-box;
				border: 1px solid gray;
			}
			[type="date"]::-webkit-inner-spin-button {
			  display: none;
			}
			[type="date"]::-webkit-calendar-picker-indicator {
			  opacity: 0;
			}
			
			
			
			#footer{
				margin: 0 auto;
				display: inline-block;
				padding-top: 80px;
			}
			
			.vectorimg{
				display: inline-block;
				padding-left: 100px;
				padding-right: 100px;
				text-align: center;
			}
			
			#vimg{
				text-align: center;
			}
			
			.submitButton{
				float: right;
				margin-right: 90px;
				background-color:#0abf04;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				display:inline-block;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:14px 41px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2f6627;
			}
			
			.submitButtonPNR{
				position:relative;
				float: right;
				margin-right: 100px;
				background-color:#0abf04;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				display:inline-block;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:14px 41px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2f6627;
				background-color:#50b51a;
			}
			
			
			.submitButton{
				background-color:#50b51a;
			}
			.submitButton{
				position:relative;
				top:1px;
			}
			
			.gidisDonusRadio{
				margin-left: 30px;
				display: inline-block;
				width: 120px;
			}
			
			
			/*the container must be positioned relative:*/
			.autocomplete {
			  position: relative;
			  display: inline-block;
			  margin-right: 80px;
			}

			input {
			  border: 1px solid grey;
			  background-color: #FFFFFF;
			  padding: 10px;
			  font-size: 16px;
			}

			input[type=text] {
			  background-color: #FFFFFF;
			  width: 100%;
			}

			input[type=submit] {
				
			}
			
			#seferAraButon{
				float: right;
				margin-right: 90px;
				background-color:#0abf04;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				display:inline-block;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:14px 41px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2f6627;
			}
			
			
			#PNRButon{
				margin-right: 10px;
				background-color:#0abf04;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				display:inline-block;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:10px 30px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2f6627;
			}
			

			.autocomplete-items {
			  position: absolute;
			  border: 1px solid #d4d4d4;
			  border-bottom: none;
			  border-top: none;
			  z-index: 99;
			  /*position the autocomplete items to be the same width as the container:*/
			  top: 100%;
			  left: 0;
			  right: 0;
			}

			.autocomplete-items div {
			  padding: 10px;
			  cursor: pointer;
			  background-color: #fff; 
			  border-bottom: 1px solid #d4d4d4; 
			}

			/*when hovering an item:*/
			.autocomplete-items div:hover {
			  background-color: #e9e9e9; 
			}

			/*when navigating through the items using the arrow keys:*/
			.autocomplete-active {
			  background-color: DodgerBlue !important; 
			  color: #ffffff; 
			}
			
			.pnrSorgu{
				text-align:right;
				width:574px;
			}
			.pnrWrapper{
				width: 574px;
				float:right;
				margin-top: 10px;
			}
			.ustBolum{
				display:inline-block;
				height: 70px;
				width: 750px;
			}
			.uyeIslemleriWrapper{
				display:inline-block
				margin-left: auto;
				margin-right: auto;
				width:400px;
				height:60px;
			}
			
			.uyeIslemleriWrapper{
				float:right;
				display:inline-block;
			}
			
			.uyeIslemBlock{
				margin-top:15px;
				display:inline-block;
				opacity: 1;
			}
			
			.uyeIslemBlockCizgi{
				margin-top:15px;
				display:inline-block;
				opacity: 0.0;
			}
			
			.uyeIslemBlock.hover{
				opacity: 1.0;
				display:inline-block;
			}
			
			.uyeIslemLabel{
				display:inline-block;
				width: 30px;
				height: 30px;
			}
			
			.labelDiv{
				vertical-align: top;
				margin-top:5px;
				display:inline-block;
				height:60px;
			}
			.uyeIslemButon{
				display:inline-block;
				margin-left:5px;
			}
			
			.logo{
				display:inline-block;
			}
			.ustBolumWrapper{
				width:750px;
				margin-left:auto;
				margin-right:auto;
			}
			
			.butonLink{
				display:inline-block;
				height: 100%;
			}
			.content{
				height:100%;
				display:inline-block;
				float:right;
				vertical-align: middle
			}
			
			.hosgeldinizText{
				display:inline-block;
			}
			
			.islemlerButon{
				display:inline-block;
				float:right;
				margin: 0;
				vertical-align: middle
			}
			
			.islemlerForm{
				margin-top: 20px;
			}
			
			.lefttext{
				margin-right: 30px;
				padding-top:30px;
				
				float:right;
			}
			
			.cikisYapLink{
				color: red;
				display:inline-block;
				float:right;
				margin-top: 30px;
			}
			
			.alinmisBiletler {
				width: 770px;
				margin: 50px auto 0px;
				color: white;
				background: #0abf04;
				text-align: center;
				border: 1px solid #B0C4DE;
				border-bottom: none;
				border-radius: 10px 10px 0px 0px;
				padding: 5px;
			}
			
			.biletListesi{
				width: 770px;
				margin: 0px auto;
				padding: 5px;
				border: 1px solid #0abf04;
				background: white;
				border-radius: 0px 0px 10px 10px;
			}
			
			
			.seferEklemeKutusu{
				border: 1px solid #0abf04;
				height:200px;
				margin-top:15px;
				margin-bottom:10px;
				border-radius: 2px;
			}
			
			.seferEklemeFormu{
				margin-top:15px;
				margin-bottom:10px;
				margin-left: 5px;
			}
			
			.biletPNRkutusu{
				margin-left:auto;
				margin-right:auto;
				border: 1px solid #0abf04;
				text-align: center;
				vertical-align:center;
				height:100px;
				margin-top:5px;
				margin-bottom:10px;
				border-radius:15px;
				width: 800px;
			}
			.biletPNRkutusuAciklama{
				border: 1px solid #0abf04;
				text-align: center;
				height: 30px;
				margin-top:5px;
				margin-bottom:10px;
				width: 800px;
				margin-left:auto;
				margin-right:auto;
			}
			
			.biletPNRyazi{
				line-height: 100px;
			}
			
			.biletIptalButon{
				margin-right: 10px;
				background-color:red;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				display:inline-block;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:10px 30px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2bcc3b;
				float:right;
				margin-top: 30px;
			}
			
			.biletIptalButonAciklama{
				visibility: hidden;
				margin-right: 10px;
				background-color:red;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				display:inline-block;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:10px 30px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2bcc3b;
				float:right;
			}
			
			.yaziKutusu{
				border: 1px solid #0abf04;
				margin-top:40px;
				margin-left:5px;
				width: 10%;
				display:inline-block;
				border-top:none;
				border-bottom:none;
				border-left:none;

			}
			
			.yaziKutusuBuyuk{
				border: 1px solid #0abf04;
				border-top:none;
				border-bottom:none;
				margin-left:5px;
				width: 20%;
				display:inline-block;
			}
			
			.yaziKutusuAciklama{
				border: 1px solid #0abf04;
				margin-left:5px;
				margin-top:5px;
				width: 10%;
				display:inline-block;
				border-top:none;
				border-bottom:none;
				border-left:none;

			}
			
			.yaziKutusuBuyukAciklama{
				border: 1px solid #0abf04;
				border-top:none;
				border-bottom:none;
				margin-left:5px;
				width: 20%;
				display:inline-block;
			}
			
			.seferEkleButon{
				display:inline-block;
				margin-right: 10px;
				background-color:#0abf04;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				display:inline-block;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:10px 30px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2f6627;
			}
			
		</style>
	</head>
	
	<body>		
		<script>
		</script> 
		
		<?php
			$PNR = mysqli_real_escape_string($db, $PNR);
			$sql = "SELECT Nereden, Nereye, Tarih, Saat, Firma, PNR, KoltukNo FROM seferler, biletler WHERE seferler.SeferID=biletler.SeferID AND biletler.PNR = '$PNR'";
			#echo "$sql";
			$result = mysqli_query($db, $sql) or die("Bad query: $sql");
			
			echo "<div class='biletPNRkutusuAciklama'>";
				echo "<div class='yaziKutusuBuyukAciklama'>";
				echo "Nereden -> Nereye";
				echo "</div>";
				#| Saat | Tarih | Firma | PNR | KoltukNo
				echo "<div class='yaziKutusuAciklama'>";
				echo "Saat";
				echo "</div>";
				
				echo "<div class='yaziKutusuAciklama'>";
				echo "Tarih";
				echo "</div>";
				
				echo "<div class='yaziKutusuAciklama'>";
				echo "Firma";
				echo "</div>";
				
				echo "<div class='yaziKutusuAciklama'>";
				echo "PNR";
				echo "</div>";
				
				echo "<div class='yaziKutusuAciklama'>";
				echo "Koltuk No";
				echo "</div>";
				echo "<button type='button' class='biletIptalButonAciklama' id='-1' >Bileti İptal Et</button>";
			echo "</div>";
			$i = 1;
			echo "<div class='biletPNRkutusu'>";
				while($row = mysqli_fetch_assoc($result)){
					
						
						echo "<div class='yaziKutusuBuyuk'>";
							echo $row['Nereden'] ," -> ", $row['Nereye'];;
						echo "</div>";
						
						echo "<div class='yaziKutusu'>";
							echo $row['Saat'];
						echo "</div>";
						
						echo "<div class='yaziKutusu'>";
							echo $row['Tarih'];
						echo "</div>";
						
						echo "<div class='yaziKutusu'>";
							echo $row['Firma'];
						echo "</div>";
					
					echo "<div class='yaziKutusu'>";
						$PNRtmp = $row['PNR'];
						echo $row['PNR'];
					echo "</div>";
					
					echo "<div class='yaziKutusu'>";
						echo $row['KoltukNo'];
					echo "</div>";
					echo "<button type='button' class='biletIptalButon' id='$PNRtmp' >Bileti İptal Et</button>";
					$i += 1;
				}
				if($i == 1){
					echo "</br>";
					echo "</br>";
					echo "Belirttiğiniz PNR koduna ait bilet bulunmamaktadır.";
				}
				
					
					#echo "<button type='button' class='biletIptalButon' id='$PNRtmp' >Bileti İptal Et</button>";
					#echo "</div>";
				
				echo "</div>";
			
			#echo"\n\n";
			#echo"<table border='1'>";
			#echo "<tr><td>Nereden</td><td>Nereye</td><td>Tarih</td><td>Saat</td><td>Firma</td><td>PNR</td></tr>\n";
			#while($row = mysqli_fetch_assoc($result)){
			#	echo "<tr><td>{$row['Nereden']}</td><td>{$row['Nereye']}</td><td>{$row['Tarih']}</td><td>{$row['Saat']}</td><td>{$row['Firma']}</td><td>{$row['PNR']}</td></tr>\n";
			#}
		?>
		
		<script type="text/javascript">
		$(document).ready(function () {
			var iptalPNR;
			$('.biletIptalButon').click(function() {
				var iptalPNR = $(this).attr('id');
				console.log(iptalPNR);
				var queryString = "?para1=" + iptalPNR;
				window.location.href = "biletIptal.php"+queryString;
			});
		});
		
		</script>
		
		
	</body>
</html>