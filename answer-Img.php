<?php
//*** License ***//
//*** Attribution-NonCommercial 3.0 Poland (CC BY-NC 3.0 PL) ***//

session_start();
          include_once ("./class/MCryptClass.php");
	      include_once ("./class/WalletClass.php");
	      include_once ("./class/SessionClass.php");
	
	$walletAcont    = new Wallet();
	$session        = new SessionClass();
	
	// adres pozyskujemy podczas sesji logowania
	// $walletAdrres1 = "07243a32cd445721c37e1099dffbb0f30d6808e2879d8e8c06bea3dc382dee5b0502f12246fc3f120d44a6f2254b421d667dd3acf2d5abebafac7cc52138f020";
	// $session->setSesion("sessionWalletAdres", $walletAdrres1);
	 $walletAdrres  = $session->getSession("sessionWalletAdres");
?><!DOCTYPE html>
<html lang="pl">
<head>
 <title>Odbierz Img Coin</title>
 
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
       <h1>Odbierz Img </h1>
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
		   <li><a href="sendCoin.php">Send Wyślij Kwotę</a></li>
		   <li><a href="answerCoin.php">Answer Odbierz Kwotę</a></li>
		    <li><a href="send-Img.php">Send Wyślij Img</a></li>
		   <li><a href="logoutQubit.php">Logout</a></li>
		</ul>
	  </nav>
  </div>
    <div id="content">
	Szukaj w blockchaine moich
	obrazków <br>
	
	<?php
	// Pobieramy wszystkie obrazki
	 $tab2 = $walletAcont->getFileImgCount($walletAdrres);
	        // print_r($tab2);
			?>
	  <form method="post"  style="clear: both;">
	      <hr />
        <h3>Odbierz Img z Blockchain</h3>
			<select style='width: 250px;' name='odbierzImg'>
			<?php
         foreach(array_keys($tab2) as $tab1){
	// Tablica 2[][] wymiarowa
	     if(!empty($tab1)){
            foreach(array_keys($tab2[$tab1]) as $tab){
			  ?>
			    <option value="<?php echo $tab1."-".$tab;?>"><?php echo $tab2[$tab1][$tab]['fileName']; ?></option>
			   <?php
			   echo "<br>";
			  }
			}
	    }
	?>
	</select>
	 <input type="submit" value="Odbierz" />
    </form>
	<?php
	
	if(isset($_POST['odbierzImg']) && !empty($_POST['odbierzImg'])){
	 // ktory załącznik z portfrla
	     $ktoryFile  = strip_tags($_POST['odbierzImg']);
	     $trescFile1 = $walletAcont->getFileIMG($walletAdrres, $ktoryFile);
		 
		 if($trescFile1){
		     echo "dekodowanie załącznika OK<br> Sprawdź folder 'imgOdebrane'";
		 }else{
		     echo "dekodowanie załącznika Error<br>";
		 }
	 
	 }else{
	 
	// echo "Nie wybrano pliku !<br>";
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
