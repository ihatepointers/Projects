<?php 
  session_start(); 

  if (!isset($_SESSION['isim'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: index.html');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: index.html");
  }
?>
<!DOCTYPE html>
<html>
<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<meta charset="UTF-8"/>

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
			width: 650px;
			height: 200px; 
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
			margin-right: 150px;
			margin-top: 23px;
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

		
		</style>
	</head>
<body>

		<div class='ustBolumWrapper'>
			<div class='ustBolum'>
				<div class='logo'>
					<img src="logo.png" height='60'>
				</div>
				
					<div class="content">
						<form action="islemler.php" method="post" class="islemlerForm">
							<input type='hidden' name='kullaniciTuru' value='<?php echo "$_SESSION[kullaniciTuru]";?>'/>
							<input type="submit" value="Üye İşlemleri" name="islemlerButon" class="islemlerButon"></button>
						</form>
						
						<!--
						<a href="islemler.php" class="butonLink" type="submit" name="islemlerButon">
							<div class='uyeIslemBlock'>
								<div class='labelDiv'>
									<label for="uyeIslemLabel">Üye İşlemleri</label>
								</div>
								<img src="islemler.svg" class="uyeIslemButon" width="30" height="30">
							</div>
						</a>
						-->

					</div>
					<a class="lefttext">
						Hoşgeldiniz
						<b>
						<?php echo $_SESSION['isim']?>
						</b>
					</a>
				</div>
			</div>
		</div>
	
		<div id="main">
			<div class="hizlial">
				<div class="pnrWrapper">
					<div class="pnrSorgu">
						<form name="pnrSorgusu" method="post" action="pnrSorgula.php" autocomplete="off">
							<div class="row2">
								<input id="PNR" type="text" name="PNR" placeholder="" style="display:inline-block;">
							</div>
							<input type='hidden' name='kullaniciTuru' value='<?php echo "$_SESSION[kullaniciTuru]";?>'/>
							<input type="submit" id="PNRButon" value="PNR Sorgula" style="display:inline-block;">
						</form>
					</div>
				</div>
				</br>
				<div id="bilgigir">
					</br>
					<form name="seferSorgusu" method="post" action="seferSorgula.php" autocomplete="off">
						
						  <div class="autocomplete">
							<input id="myInput" type="text" name="nereden" placeholder="Nereden">
						  </div>
						  
						  <div class="autocomplete">
							<input id="myInput2" type="text" name="nereye" placeholder="Nereye">
						  </div>
						
						</br>
						</br>
						
						<div class="row">
							<label for="tarih">Gidiş Tarihi</label> <br/>
							<input type="date" name="gidisTarihi" id="gidisTarihi">
						</div>
						<!--
						<div class="row2">
							<label for="tarih">Dönüş Tarihi</label> <br/>
							<input type="date" name="donusTarihi" id="donusTarihi" style="background-color:lightgray;" disabled >
						</div>
						
						<div class="gidisDonusRadio">
						  <input type="radio" name="gidisDonus" value="tekYon" checked="checked" onclick="disableDonus()"> Tek Yön <br>
						  <input type="radio" name="gidisDonus" value="gidisDonus" onclick="enableDonus()"> Gidiş - Dönüş<br>
						</div>
						<input type='hidden' name='email' value='<?php echo "$_SESSION[email]";?>'/>
						
						-->
						<input type="submit" value="Sefer Ara" id="seferAraButon">
					</form>
					
					
					
				</div>
			</div>
		</div>
		
		<div id="footerwrapper" align="center">
			<div id="footer">
				<div class="vectorimg">
					<img src="hizli.png"> <br/>
					<label for="vimg">Hızlı</label>
				</div>
				
				<div class="vectorimg">
					<img src="guvenli.png"> <br/>
					<label for="vimg">Güvenli</label>
				</div>
				
				<div class="vectorimg">
					<img src="ucretsiz.png"> <br/>
					<label for="vimg">Ücretsiz</label>
				</div>
		
			</div>
		</div>

		
		
		<script>
		function disableDonus(){
			document.getElementById("donusTarihi").disabled = true;
			document.getElementById("donusTarihi").style.backgroundColor = "lightgray"
		}
			
		function enableDonus(){
			document.getElementById("donusTarihi").disabled = false;
			document.getElementById("donusTarihi").style.backgroundColor = "white"
		}
		
		
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
		
		<script>
		$('.koltukSecButonu').on('click', function(){
			alert($(this).attr('id'));
		});
		</script>
		

	</body>
		
</html>