<?php
session_start();

// initializing variables
$isim = "";
$email    = "";
$kullaniciTuru    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'seferlerdb');
mysqli_set_charset($db, "utf8");
// REGISTER USER
if (isset($_POST['kayitOlButon'])) {
  // receive all input values from the form
  $isim = mysqli_real_escape_string($db, $_POST['isim']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $parola_1 = mysqli_real_escape_string($db, $_POST['parola_1']);
  $parola_2 = mysqli_real_escape_string($db, $_POST['parola_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($isim)) { array_push($errors, "Kullanıcı adı boş olamaz"); }
  if (empty($email)) { array_push($errors, "E-mail kısmı boş olamaz"); }
  if (empty($parola_1)) { array_push($errors, "Parola kısmı boş olama"); }
  if ($parola_1 != $parola_2) {
	array_push($errors, "Parolalar uyuşmuyor");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM kullanicilar WHERE email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] === $email) {
      array_push($errors, "Bu E-mail zaten kullaniliyor");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$parola = md5($parola_1);//encrypt the password before saving in the database
	
	//INSERT INTO `kullanicilar` (`kullaniciID`, `email`, `parola`, `isim`, `kullaniciTuru`, `biletler`) VALUES ('1', 'tunahan123@gmail.com', '123456', 'tunahan', '0', '');
	$id = 0;
  	$_SESSION['isim'] = $isim;
	$_SESSION['email'] = $email;
  	$_SESSION['success'] = "";
	$query = "INSERT INTO `kullanicilar` (`kullaniciID`, `email`, `parola`, `isim`, `kullaniciTuru`, `biletler`) VALUES ('$id', '$email', '$parola', '$isim', '0', '');";
	/*
	while(!mysqli_query($db,$query)){
		$id += 1;
		$query = "INSERT INTO 'kullanicilar' ('kullaniciID', 'email', 'parola', 'isim', 'kullaniciTuru', 'biletler) 
  			  VALUES('$id','$email', '$parola', '$isim', '0', '')";
		
	}
	*/
	while(!mysqli_query($db,$query)){
		$id +=1;
		$query = "INSERT INTO `kullanicilar` (`kullaniciID`, `email`, `parola`, `isim`, `kullaniciTuru`, `biletler`) VALUES ('$id', '$email', '$parola', '$isim', '0', '')";
	}
  	header('location: basariliGiris.php');
  }
}

// LOGIN USER
if (isset($_POST['girisYapButon'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $parola = mysqli_real_escape_string($db, $_POST['parola']);
  $kullaniciTuru = mysqli_real_escape_string($db, $_POST['kullaniciTuru']);

  if (empty($email)) {
  	array_push($errors, "E-Mail kısmı boş olamaz");
  }
  if (empty($parola)) {
  	array_push($errors, "Parola kısmı boş olamaz");
  }

  if (count($errors) == 0) {
  	$parola = md5($parola);
  	$query = "SELECT * FROM kullanicilar WHERE email='$email' AND parola='$parola'";
  	$results = mysqli_query($db, $query);
	
  	if (mysqli_num_rows($results) == 1) {
	  $user = mysqli_fetch_assoc($results);
	  #echo $user['isim'];
  	  $_SESSION['email'] = $email;
	  $_SESSION['isim'] = $user['isim'];
	  $_SESSION['kullaniciTuru'] = $user['kullaniciTuru'];
  	  $_SESSION['success'] = "Başarıyla giriş yaptınız";
  	  header('location: basariliGiris.php');
  	}else {
  		array_push($errors, "Yanlış E-Mail ya da parola");
  	}
  }
}

// Kullanici islemleri
if (isset($_POST['islemlerButon'])) {
  echo $_POST['isim'];
}

?>