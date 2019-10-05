<?php
 session_start();

    include_once ("./class/BlockChainClass.php");
    include_once ("./class/WalletClass.php");
	include_once ("./class/SessionClass.php");
	
	$wallet = new Wallet();
	$session= new SessionClass();
	//$session->getInstance();
	// adres pozyskujemy podczas sesji logowania
	// $walletAdrres1 = "07243a32cd445721c37e1099dffbb0f30d6808e2879d8e8c06bea3dc382dee5b0502f12246fc3f120d44a6f2254b421d667dd3acf2d5abebafac7cc52138f020";
	
	 // $session->setSesion("sessionWalletAdres", $walletAdrres1);
	 $walletAdrres  = $session->getSession("sessionWalletAdres");

?><!DOCTYPE html>
<html lang="pl">
<head>
 <title>Balance Coin</title>
 
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
       <h1>Balance </h1>
	   <p><?php
	             $coin  = new BlockChain();
             echo "<br>Balans Coin All to: <span style='color:#000000;'>".$coin->qubitCoinAll()." </span> Coin<br>";
		     echo "<br>Balans Acount to: <span style='color:#000000;'>".$wallet->balanceWallet($walletAdrres)." </span> Coin<br>";
	   ?></p>
   </div>
       <?php
     	 if(!empty($walletAdrres) && $wallet->isChainValid() === true){  
   ?>
   <div>
      <nav>
	    <ul id="menu">
		   <li><a href="sendCoin.php">Send Wyślij Kwotę</a></li>
		   <li><a href="answerCoin.php">Answer Odbierz Kwotę</a></li>
		   <li><a href="send-Img.php">Send Wyślij IMG</a></li>
		   <li><a href="answer-Img.php">Answer Odbierz IMG</a></li>
		   <li><a href="logoutQubit.php">Logout</a></li>
		</ul>
	  </nav>
  </div>
    <div id="content">
	<?php

	if($wallet->isChainValid() === true){
	  echo "<br>Balans dla konta nr:".$walletAdrres."<br> Balance Acont:";
	  echo $wallet->balanceWallet($walletAdrres);
	  
	  // czy bloki sie zgadzaja
	   echo "<br> Is walid Blockchain ".$wallet->isChainValid()."<br>";
        }else{
		echo 'BlockChain nie jest zgodny';
		}
	?>
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
