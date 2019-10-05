<?php
session_start();
    include_once ("./class/WalletClass.php");
	include_once ("./class/SessionClass.php");
	
	$wallet = new Wallet();

	$sesion  = new SessionClass();
	// adres pozyskujemy podczas sesji logowania
	 $walletAdrres  = $sesion->getSession("sessionWalletAdres");
?><!DOCTYPE html>
<html lang="en">
<head>
 <title>Answer Coin </title>
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
       <h1>Answer  Coin </h1>
	   <p><?php
	            // $coin  = new BlockChain();
          // echo "<br>Balans dla Coin to: <span style='color:#000000;'>".$coin->qubitCoinAll()." </span> Coin<br>";
		     echo "<br>Balans Acount to: <span style='color:#000000;'>".$wallet->balanceWallet($walletAdrres)." </span> Coin<br>";
	   ?></p>
   </div>
       <?php
     	 if(!empty($walletAdrres) && $wallet->isChainValid() === true){  
   ?>
   <div>
      <nav>
	    <ul id="menu">
		     <li><a href="balance.php">Balance</a></li>
		   <li><a href="sendCoin.php">Send Wyślij Kwotę</a></li>
		   <li><a href="send-Img.php">Send Wyślij IMG</a></li>
		   <li><a href="answer-Img.php">Answer Odbierz IMG</a></li>
		   <li><a href="logoutQubit.php">Logout</a></li>
		</ul>
	  </nav>
  </div>
    <div id="content">
	<style>
	p.box{

	background:#FFF;
	border:10px solid #FF00FF;
	border-radius:5px;
    border-width:2px 6px 6px 2px;
	
	-webkit-column-count:1;
	-moz-column-count:1;
	     column-count:1;
	
	-webkit-column-gap:1250px;
	-moz-column-gap:1250px;
	     column-gap:1250px;
		 width:1250px;
	}
	</style>
	<?php
	     // $html  ="<div style='width:150px; float:left;'>Adres Wallet: ";
	     // $html .="<p class='box'>".$walletAdrres."</p>";
	     // $html .="<p> QR Code </p> </div>";
	 // $adres = wordwrap($walletAdrres, 40, "<br>\n");
	?>
<div style="float:left;">Adres Wallet: 
	     <p class='box'><?php echo $walletAdrres; ?></p>
	     <p> QR Code </p> </div>
    </div>
		<?php 
	}else{
	echo 'Login  adres';
	}
	?>
  </div>
  <footer id="footer"> 
 footer html 5 
</footer>

</div>
</body>
</html>
