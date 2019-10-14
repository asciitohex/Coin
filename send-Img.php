<?php
//*** License ***//
//*** Attribution-NonCommercial 3.0 Poland (CC BY-NC 3.0 PL) ***//

session_start();

	include_once ("./class/BlockChainClass.php");
	include_once ("./class/TransactionImgClass.php");
	include_once ("./class/SessionClass.php");
	include_once ("./class/WalletClass.php");
	
    $wallet        = new Wallet();
	$sesion        = new SessionClass();
	 $walletAdrres  = $sesion->getSession("sessionWalletAdres");

?><!DOCTYPE html>
<html lang="pl">
<head>
 <title>Wyślij Img Coin</title>
<meta charset="utf-8"/>
<meta content="pl"/>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="To jest test html5" />
<meta name="keywords" content="html, html5" />
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="./w3css/styl.css">
    
   </head>
<body>
<div class="container wrapper">
<div class="wrapper">
 <div id="top">
       <h1>Wyślij Img </h1>
	    <p><?php
	            // $coin  = new BlockChain();
          // echo "<br>Balans dla Coin to: <span style='color:#000000;'>".$coin->qubitCoinAll()." </span> Coin<br>";
		     echo "<br>Balans Acount to: <span style='color:#000000;'>".$wallet->balanceWallet($walletAdrres)." </span> Coin<br>";
	   ?></p>
   </div>
   <div>
   <?php
     	 if(!empty($walletAdrres) && $wallet->isChainValid() === true){  
   ?>
      <nav>
	    <ul id="menu">
		   <li><a href="balance.php">Balance</a></li>
		   <li><a href="sendCoin.php">Send Wyślij Kwotę</a></li>
		   <li><a href="answerCoin.php">Answer Odbierz Kwotę</a></li>
		   <li><a href="answer-Img.php">Answer Odbierz Img</a></li>
		   <li><a href="logoutQubit.php">Logout</a></li>
		</ul>
	  </nav>
  </div>
    <div id="content">
	<?php

	                            $coin = new BlockChain();
	              if(isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])){

				                     $coin->fileImg($_FILES, $walletAdrres);
	                            echo $coin->errorImg();

					         // stan załacznika dla wallet
	                    if($coin->isImgValid() === true ){
		                   echo "<b style='color: #FF00FF;'><br>Kodowanie załącznika OK !<br> </b>"; 
						   
						// kopiemy aby potwierdzić transakcje
						   $coin->minePendingTransaction("Adam-adres", true);
						   $coin->minePendingTransaction("Adam-adres", true);
	                        }else{
	                       echo "<b style='color: #ff9601;'><br>Kodowanie załącznika Error !<br></b>"; 
	                       }
					    }
	  
     // zrobić limit wysyłanych plikow np 100	  
	 // żeby wysłać załącznik musisz posiadać 100 Coin
	?>
	<form method="post" enctype="multipart/form-data" style="clear: both;">
        <h3>Upload</h3>
          <hr />
            <div>  
         	   <label>UploadFile</label>
	           <p> <input type="file" name="userfile" /></p>
               <p> <input type="submit" value="Wyślij" /></p>
			</div>
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
