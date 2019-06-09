<?php
session_start();


$db = mysqli_connect('localhost', 'root', '', 'seferlerdb');
mysqli_set_charset($db, "utf8");

  
$iptalPNR = $_GET['para1'];
$iptalPNR = mysqli_real_escape_string($db, $iptalPNR);
$sql = "DELETE FROM `biletler` WHERE `biletler`.`PNR` = '$iptalPNR'";	
mysqli_query($db, $sql) or die("Bad query: $sql");

#echo $_SESSION['email'];

if(isset($_SESSION['email'])){
	$email = $_SESSION['email'];
	echo $iptalPNR;
	echo "<br>";
	$sql = "SELECT * FROM `kullanicilar` WHERE `email` LIKE '$email'";
	$result = mysqli_query($db, $sql) or die("Bad query: $sql");
	while($row = mysqli_fetch_assoc($result)){
		echo $row['biletler'];
		echo "<br>";
		echo strpos($row['biletler'], $iptalPNR);
		echo "<br>";
		echo strpos($row['biletler'], $iptalPNR.",");
		echo "<br>";
		if((strpos($row['biletler'], $iptalPNR."," )) !== false){
			echo "sondegil";
			$iptalPNR = $iptalPNR.",";	
		}
		else if(strpos($row['biletler'], $iptalPNR) !== false){
			echo "son";
		}
		
		$sql = "UPDATE `kullanicilar` SET `biletler` = REPLACE(`biletler`,'$iptalPNR','') WHERE `kullanicilar`.`email` = '$email'";	
		mysqli_query($db, $sql) or die("Bad query: $sql");
		
		//"DELETE FROM `biletler` WHERE `biletler`.`PNR` = \'4Y4T\'"
		
	}
	header("location: islemler.php");
}
header("location: basariliGiris.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>BiletIptal</title>
		
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
			
			.myButton{
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
			
			.textWrapper{
				width: 40%;
				margin-left:auto;
				margin-right:auto;
			}
			.error {
			  width: 80%; 
			  margin: 0px auto; 
			  padding: 10px; 
			  border: 1px solid #a94442; 
			  color: #a94442; 
			  background: #f2dede; 
			  border-radius: 5px; 
			  text-align: left;
			}
			
		</style>
	</head>
	
	<body>		
		
		
		
		
	</body>
</html>