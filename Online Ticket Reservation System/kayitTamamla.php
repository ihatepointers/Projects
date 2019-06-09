<?php
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
		<title>Giriş Yap</title>
		
		<style type="text/css">
			.girisYapWrapper{
				height:550px;
				width:470px;
				margin: 0;
				position: absolute;
				top: 50%;
				left: 50%;
				-ms-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
				border-width:1px;
				border-style:solid;
				border-color: #D3D3D3;
				border-radius:30px;
			}
			
			#seferAraButon{
				width:100%;
				height:100%;
				margin-left:auto;
				margin-right:auto;
				background-color:#0abf04;
				-moz-border-radius:15px;
				-webkit-border-radius:15px;
				border-radius:15px;
				border:1px solid #2bcc3b;
				cursor:pointer;
				color:#ffffff;
				font-family:Arial;
				font-size:17px;
				padding:14px 30px;
				text-decoration:none;
				text-shadow:0px 0px 4px #2f6627;
			}
			
			.logoWrapper{
				margin-top:15px;
				margin-bottom:15px;
				margin-left:auto;
				margin-right:auto;
				width:70%;
				height:45px;
			}
			
			.myInputClass{
				width:100%;
				height:100%;
				margin-left:auto;
				margin-right:auto;
			}
			
			.inputWrapper{
				margin-top:15px;
				margin-bottom:15px;
				height: 55px;
				width: 60%;
				margin-left:auto;
				margin-right:auto;
			}
			
			.buttonWrapper{
				margin-top:15px;
				margin-bottom:15px;
				height: 50px;
				width: 35%;
				margin-left:auto;
				margin-right:auto;
			}
			
		</style>
	</head>
	
	<body>		
		
		<div class='girisYapWrapper'>
			</br>
			<div class='logoWrapper'>
				<img src="logo.png" width='100%' height='100%'>
			</div>
			</br>
			
						
			<form name="pnrSorgusu" method="post" action="pnrSorgula.php" autocomplete="off">
				<div class='inputWrapper'>
					<input id="emailInput" class='myInputClass' type="text" name="email" placeholder="E-Mail"></br>
				</div>
				
				<div class='inputWrapper'>
					<input id="isimInput" class='myInputClass' type="text" name="isim" placeholder="İsim"></br>
				</div>
				
				<div class='inputWrapper'>
					<input id="parolaInput" class='myInputClass' type="text" name="parola" placeholder="Parola"></br>
				</div>
				
				<div class='inputWrapper'>
					<input id="parolaTekrarInput" class='myInputClass' type="text" name="parolaTekrar" placeholder="Tekrar parola"></br>
				</div>
				
				</br>
				<div class='buttonWrapper'>
					<input type="submit" value="Kayıt Ol" id="seferAraButon">
				</div>
			</form>
		</div>
		
		
		
	</body>
</html>