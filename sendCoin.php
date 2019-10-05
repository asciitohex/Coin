<?php
session_start();
          include_once ("./class/BlockChainClass.php");
	      include_once ("./class/MCryptClass.php");
	      include_once ("./class/WalletClass.php");
	      include_once ("./class/SessionClass.php");
	
	$sesion        = new SessionClass();
	$coin          = new BlockChain();
	$walletAcont   = new Wallet();
	$mCrypt        = new classMcrypt();
	
	 $walletAdrres  = $sesion->getSession("sessionWalletAdres");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
 <title>Wyslij Coina</title>
 
<meta charset="utf-8"/>
<meta content="pl"/>
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
       <h1>Wyslij Coina </h1>
	    <p><?php
	            // $coin  = new BlockChain();
          // echo "<br>Balans dla Coin to: <span style='color:#000000;'>".$coin->qubitCoinAll()." </span> Coin<br>";
		     echo "<br>Balans Acount to: <span style='color:#000000;'>".$walletAcont->balanceWallet($walletAdrres)." </span> Coin<br>";
	   ?></p>
   </div>
    <?php
     	 if(!empty($walletAdrres) && $walletAcont->isChainValid() === true){  
   ?>
   <div>
      <nav>
	    <ul id="menu">
		    <li><a href="balance.php">Balance</a></li>
		   <li><a href="answerCoin.php">Answer Odbierz Kwotę</a></li>
		   <li><a href="send-Img.php">Send Wyślij IMG</a></li>
		   <li><a href="answer-Img.php">Answer Odbierz IMG</a></li>
		   <li><a href="logoutQubit.php">Logout</a></li>
		</ul>
	  </nav>
  </div>
    <div id="content">
	<?php
	 $seed1 = $walletAcont->getSeedDB($walletAdrres);
	 // dekodujemy hało zapisane w bazie danych
	 $seed = $mCrypt->dekdowanie($seed1);
     // zrobic sprawdzenie ilości znaków 	 
	 
	 	if($walletAcont->isChainValid() === true){
	 if(!empty($_POST["kwota"]) && !empty($_POST["adresTo"])){  
	  if(strlen($_POST["adresTo"]) === 128){
	            $walletAdrresTo = strip_tags($_POST["adresTo"]);
	            $error = true;
	  }else{
	  echo "<br>Niepoprawny Adres !<br>";
	  }
	  if(is_numeric($_POST["kwota"]) && $_POST["kwota"] > 0){
	            $kwota  = (int) $_POST["kwota"];
				$error1 = true;
	  }else{
	  echo "<br>Niepoprawna Kwota !<br>";
	  }
	  // prywatny adres soll tworzony podczas Transakcji jest jednorazowy unikalny na razie nie
	  // sprawdzany w Klasie WalletClass w metodzie isSollWallet($soll)

	 if($error1 === true && $error === true){
	 
	 $soll = md5(md5(trim($seed)).trim($walletAdrres));
	  
	  // wysyłamy do koleiki w sieci
	  $coin->createTransaction(new Transaction($walletAdrres, $walletAdrresTo, $kwota, $soll));
	  $coin->minePendingTransaction("Adam-adres1", true);
	  $coin->minePendingTransaction("Adam-adres12", true);
	  $coin->clearTransactionAll();
	 }
	  }else{
	  echo "Podaj dane !";
	  }
	      // czy bloki sie zgadzaja
	      echo "<br> Is walid Blockchain ".$walletAcont->isChainValid()." <br>";
        }else{
		  echo 'BlockChain nie jest zgodny '.$walletAcont->isChainValid();
		}	
	?>	
	<form method="post" style="clear: both;">
	      <hr />
        <h3>Wyślij Coina</h3>
      <div><label>Adres do wysyłki</label><input type="text" name="adresTo" /></div>
      <div><label>Kwota </label><input type="text" name="kwota" /></div>
          <hr />
      <input type="submit" value="Wyślij" />
    </form>
    </div>
	<?php 
	}else{
	echo 'Zaloguj adres';
	}
	?>
  </div>
  <footer id="footer"> 
to jest footer i dół strony html 5 
  </footer>
</div>
</body>
</html>
