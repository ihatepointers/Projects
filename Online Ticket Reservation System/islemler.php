<?php header('Content-type: text/html; charset=utf-8');
session_start();

$db = mysqli_connect('localhost', 'root', '', 'seferlerdb');
mysqli_set_charset($db, "utf8");
if(isset($_SESSION['kullaniciTuru'])){
	$kullaniciTuru = $_SESSION['kullaniciTuru'];
}else{
	$kullaniciTuru = 0;
}
$email = $_SESSION['email'];
$isim = $_SESSION['isim'];

if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: index.html");
  }

$sql = "SELECT * FROM `kullanicilar` WHERE `email` LIKE '$email'";
$result = mysqli_query($db, $sql) or die("Bad query: $sql");

?>

<!DOCTYPE html>
<html lang="tr" dir="ltr">
	<head>
		<meta charset="UTF-8"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src = "/jquery/jquery-3.4.0.min.js"></script>
		<title>Üye İşlemleri</title>
		
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
				border: 1px solid #0abf04;
				text-align: center;
				vertical-align:center;
				height:100px;
				margin-top:5px;
				margin-bottom:10px;
				border-radius:15px;
			}
			.biletPNRkutusuAciklama{
				border: 1px solid #0abf04;
				text-align: center;
				height: 30px;
				margin-top:5px;
				margin-bottom:10px;
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
		<div class='ustBolumWrapper'>
			<div class='ustBolum'>
				<div class='logo'>
					<img src="logo.png" height='60'>
				</div>
				
					<div class="content">
						<a href="islemler.php?logout='1'" class="cikisYapLink">Çıkış Yap</a>
						<a class="lefttext">
							Hoşgeldiniz
							<b>
							<?php echo $_SESSION['isim']?>
							</b>
						</a>
					

					</div>
					
				</div>
			</div>
		</div>
		<?php
			if($kullaniciTuru == 0){
				echo"<div class='altBolumWrapper'>";
					echo"<div class='alinmisBiletler'>";
						echo"<h2>Biletleriniz</h2>";
					echo"</div>";
					echo"<div class='biletListesi'>";
					
						while($row = mysqli_fetch_assoc($result)){
							$myString = $row['biletler'];
						}
						$myArray = explode(',', $myString);
						#echo ($myString);
						
						
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
						$i = 0;
						if(!empty($myString)){
							
							while($i<sizeof($myArray)){
								$PNRtmp = $myArray[$i];
								$sql2 = "SELECT * FROM `biletler` WHERE `PNR` LIKE '$PNRtmp'";
								$result2 = mysqli_query($db, $sql2) or die("Bad query: $sql");
								if(!empty($PNRtmp)){
									echo "<div class='biletPNRkutusu'>";
									while($row = mysqli_fetch_assoc($result2)){
										$seferIDtmp = $row['SeferID'];
										$sql3 = "SELECT * FROM `seferler` WHERE `SeferID` = '$seferIDtmp'";
										$result3 = mysqli_query($db, $sql3) or die("Bad query: $sql");
										#echo "<div class='biletPNRyazi'>";
										while($row2 = mysqli_fetch_assoc($result3)){
											
											echo "<div class='yaziKutusuBuyuk'>";
												echo $row2['Nereden'] ," -> ", $row2['Nereye'];;
											echo "</div>";
											
											echo "<div class='yaziKutusu'>";
												echo $row2['Saat'];
											echo "</div>";
											
											echo "<div class='yaziKutusu'>";
												echo $row2['Tarih'];
											echo "</div>";
											
											echo "<div class='yaziKutusu'>";
												echo $row2['Firma'];
											echo "</div>";
										}
										echo "<div class='yaziKutusu'>";
											echo $row['PNR'];
										echo "</div>";
										
										echo "<div class='yaziKutusu'>";
											echo $row['KoltukNo'];
										echo "</div>";
										
										echo "<button type='button' class='biletIptalButon' id='$PNRtmp' >Bileti İptal Et</button>";
										#echo "</div>";
									}
									echo "</div>";
									
									
								}
								$i += 1;
							}
						}else{
							echo "<div class='yaziKutusuBuyuk' style='width:98%; text-align:center;'>";
								echo " Kayıtlı biletiniz bulunmamaktadir...";
							echo "</div>";
						}
			}else{
				#echo "firma olarak giris yaptiniz.";
					echo"<div class='altBolumWrapper'>";
					echo"<div class='alinmisBiletler'>";
						echo"<h2>Sefer Ekleme Arayüzü</h2>";
					echo"</div>";
					echo"<div class='biletListesi'>";
					echo "<div class='biletPNRkutusuAciklama'>";
					echo "<button type='button' class='biletIptalButonAciklama' id='-1' >Bileti İptal Et</button>";
				echo "</div>";
				
				echo "<div class='seferEklemeKutusu'>";
					echo "<form name='seferSorgusu' class='seferEklemeFormu' method='post' action='seferEkle.php' autocomplete='off'>";

						echo "<div class='autocomplete'>";
							echo "<input id='myInput' type='text' name='nereden' placeholder='Nereden'required>";
						echo "</div>";

						echo "<div class='autocomplete'>";
							echo "<input id='myInput2' type='text' name='nereye' placeholder='Nereye'required>";
						echo "</div>";

						echo "</br>";
						echo "</br>";

						echo "<div class='row'>";
							echo "<label for='tarih'>Gidiş Tarihi</label> <br/>";
							echo "<input type='date' name='gidisTarihi' id='gidisTarihi'required>";
						echo "</div>";

						echo "<div class='row'>";
							echo "<label for='tarih'>Gidiş Saati</label> <br/>";
							echo "<input type='time' id='appt' name='seferSaati' min='00:00' max='23:59' required>";
						echo "</div>";
						
						echo "<input type='hidden' name='firma' value='$_SESSION[isim]'/>";
						echo "<button type='submit' name='islemlerButon' class='seferEkleButon' >Sefer Ekle</button>";
					echo "</form>";
				echo "</div>";
			}
				
				
				
				
				
				?>		
	
		<script>
		function autocomplete(inp, arr) {
		  /*the autocomplete function takes two arguments,
		  the text field element and an array of possible autocompleted values:*/
		  var currentFocus;
		  /*execute a function when someone writes in the text field:*/
		  inp.addEventListener("input", function(e) {
			  var a, b, i, val = this.value;
			  /*close any already open lists of autocompleted values*/
			  closeAllLists();
			  if (!val) { return false;}
			  currentFocus = -1;
			  /*create a DIV element that will contain the items (values):*/
			  a = document.createElement("DIV");
			  a.setAttribute("id", this.id + "autocomplete-list");
			  a.setAttribute("class", "autocomplete-items");
			  /*append the DIV element as a child of the autocomplete container:*/
			  this.parentNode.appendChild(a);
			  /*for each item in the array...*/
			  for (i = 0; i < arr.length; i++) {
				/*check if the item starts with the same letters as the text field value:*/
				if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
				  /*create a DIV element for each matching element:*/
				  b = document.createElement("DIV");
				  /*make the matching letters bold:*/
				  b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
				  b.innerHTML += arr[i].substr(val.length);
				  /*insert a input field that will hold the current array item's value:*/
				  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
				  /*execute a function when someone clicks on the item value (DIV element):*/
				  b.addEventListener("click", function(e) {
					  /*insert the value for the autocomplete text field:*/
					  inp.value = this.getElementsByTagName("input")[0].value;
					  /*close the list of autocompleted values,
					  (or any other open lists of autocompleted values:*/
					  closeAllLists();
				  });
				  a.appendChild(b);
				}
			  }
		  });
		  /*execute a function presses a key on the keyboard:*/
		  inp.addEventListener("keydown", function(e) {
			  var x = document.getElementById(this.id + "autocomplete-list");
			  if (x) x = x.getElementsByTagName("div");
			  if (e.keyCode == 40) {
				/*If the arrow DOWN key is pressed,
				increase the currentFocus variable:*/
				currentFocus++;
				/*and and make the current item more visible:*/
				addActive(x);
			  } else if (e.keyCode == 38) { //up
				/*If the arrow UP key is pressed,
				decrease the currentFocus variable:*/
				currentFocus--;
				/*and and make the current item more visible:*/
				addActive(x);
			  } else if (e.keyCode == 13) {
				/*If the ENTER key is pressed, prevent the form from being submitted,*/
				e.preventDefault();
				if (currentFocus > -1) {
				  /*and simulate a click on the "active" item:*/
				  if (x) x[currentFocus].click();
				}
			  }
		  });
		  function addActive(x) {
			/*a function to classify an item as "active":*/
			if (!x) return false;
			/*start by removing the "active" class on all items:*/
			removeActive(x);
			if (currentFocus >= x.length) currentFocus = 0;
			if (currentFocus < 0) currentFocus = (x.length - 1);
			/*add class "autocomplete-active":*/
			x[currentFocus].classList.add("autocomplete-active");
		  }
		  function removeActive(x) {
			/*a function to remove the "active" class from all autocomplete items:*/
			for (var i = 0; i < x.length; i++) {
			  x[i].classList.remove("autocomplete-active");
			}
		  }
		  function closeAllLists(elmnt) {
			/*close all autocomplete lists in the document,
			except the one passed as an argument:*/
			var x = document.getElementsByClassName("autocomplete-items");
			for (var i = 0; i < x.length; i++) {
			  if (elmnt != x[i] && elmnt != inp) {
				x[i].parentNode.removeChild(x[i]);
			  }
			}
		  }
		  /*execute a function when someone clicks in the document:*/
		  document.addEventListener("click", function (e) {
			  closeAllLists(e.target);
		  });
		}

		/*An array containing all the country names in the world:*/
		var countries = ["Adana", "Adıyaman", "Afyonkarahisar", "Ağrı", "Aksaray", "Amasya", "Ankara", "Antalya", "Ardahan", "Artvin", "Aydın", "Balıkesir", "Bartın", "Batman", "Bayburt", "Bilecik", "Bingöl", "Bitlis", "Bolu", "Burdur", "Bursa", "Çanakkale", "Çankırı", "Çorum", "Denizli", "Diyarbakır", "Düzce", "Edirne", "Elazığ", "Erzincan", "Erzurum", "Eskişehir", "Gaziantep", "Giresun", "Gümüşhane", "Hakkâri", "Hatay", "Iğdır", "Isparta", "İstanbul", "İzmir", "Kahramanmaraş", "Karabük", "Karaman", "Kars", "Kastamonu", "Kayseri", "Kilis", "Kırıkkale", "Kırklareli", "Kırşehir", "Kocaeli", "Konya", "Kütahya", "Malatya", "Manisa", "Mardin", "Mersin", "Muğla", "Muş", "Nevşehir", "Niğde", "Ordu", "Osmaniye", "Rize", "Sakarya", "Samsun", "Şanlıurfa", "Siirt", "Sinop", "Sivas", "Şırnak", "Tekirdağ", "Tokat", "Trabzon", "Tunceli", "Uşak", "Van", "Yalova", "Yozgat", "Zonguldak"];

		/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
		autocomplete(document.getElementById("myInput"), countries);
		autocomplete(document.getElementById("myInput2"), countries);
		</script>
		
		<script type="text/javascript">
		var email;
		var iptalPNR;
		$( '.biletIptalButon' ).click(function() {
			var iptalPNR = $(this).attr('id');
			console.log(iptalPNR);
			var queryString = "?para1=" + iptalPNR;
			window.location.href = "biletIptal.php"+queryString;
		});
		</script>
	
	</body>