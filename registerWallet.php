<?php
session_start();

	
	include_once ("./class/BlockChainClass.php");
	include_once ("./class/WalletClass.php");
	include_once ("./class/WalletAcontAdresClass.php");
	include_once ("./class/SessionClass.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Wallet Coin</title>
   <meta charset="utf-8"/>
   <meta content="en"/>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="description" content="To jest test html5" />
   <meta name="keywords" content="html, html5" />
   <link rel="icon" href="/favicon.ico" type="image/x-icon">
   <link rel="stylesheet" href="./w3css/styl.css">
</head>
<body>
<div class="container wrapper">
<div class="wrapper">
 <div id="top">
       <h1>Coin Wallet </h1>
	   <p>Start</p>
   </div>
   <div>
      <nav>
	    <ul id="menu">
		   <li><a href="loginQubit.php">LogIn</a></li>
		</ul>
	  </nav>
  </div>
    <div id="content">
	<?php

	
	 $coin   = new BlockChain();
	 $wallet = new Wallet();
	 // $sesion = SessionClass::getInstance();
	
	if(isset($_POST["seed"]) && !empty($_POST["seed"])){
	
		 $seed = strip_tags($_POST["seed"]); 
	// rejestracja konta w Blockchain dopiero potem konto będzie aktywne
	// tak to będzie miało status offline
	    $coin->createDataWalletAcont(new WalletAcont($seed));
	
	// Tworzenie nowego konta lokalnie zapis do lokalnej bazy danych
	// tworzenie soll itd.
	  $wallet->setCreateAdresWallet($seed);
	     // nowy adres utworzony po podaniu seeda
	  $walletAdrres = $wallet->getAdresWallet();
	
	if(!empty($walletAdrres)){

	echo "Konto zostało stworzone obecny adres to: <br>";
	echo $walletAdrres."<br><br>.";
	// Testowe każdy dostaje 100 coinow na start
	
	$walletCoin = "Adam-100000";
	$soll = md5(md5(trim($seed)).trim($walletCoin));
	
	   $coin->createTransaction(new Transaction($walletCoin, $walletAdrres, "100", $soll));
	// wynagrodzenie za znalezienie bloku drugi parametr to potwierdzenie z sieci po rozgłoszeniu
	// chęci przeprowadzenia transakcji
	   $coin->minePendingTransaction("Adam-adres", true);
	   $coin->minePendingTransaction("Adam-adres12", true);
	   $coin->clearTransactionAll();
	// soll do podpisywania transakcji
	// $soll = md5(md5($seed).$walletAdrres);
	}else{
	  echo "Konta nie utworzono, sprawdź ilość liter wymagana ilość to 10 !";
	  }
	}
	// czy bloki sie zgadzaja
	  	  echo "<br> Is walid Blockchain ".$coin->isChainValid()."<br>";
	?>
	
	<form method="post" style="clear: both;">
	      <hr />
        <h3>Register</h3>
      <div><label>enter the passwor to Wallet</label><input type="text" name="seed" /></div>
      <hr />
      <input type="submit" value="Zarejestruj" />
    </form>
    </div>
  </div>
  <footer id="footer"> 
 footer  html 5 
</footer>
</div>
</body>
</html>
