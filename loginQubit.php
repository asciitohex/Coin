<?php
session_start();

	include_once ("./class/BlockChainClass.php");
	include_once ("./class/WalletClass.php");
	include_once ("./class/WalletAcontAdresClass.php");
	include_once ("./class/SessionClass.php");
	
	 $wallet = new Wallet();
	 $session = new SessionClass();
	 //$session->getInstance();
	
	if(isset($_POST["seed"]) && !empty($_POST["seed"])){
	   $seed = strip_tags($_POST["seed"]); 

	            $wallet->setAdresWallet($seed);
	     // nowy adres utworzony po podaniu seeda
	   $walletAdrres = $wallet->getAdresWallet();
	
	if(!empty($walletAdrres) && $wallet->isChainValid() === true){
	                   $session->setSession("sessionWalletAdres", $walletAdrres);
	 $walletAdrres1  = $session->getSession("sessionWalletAdres");
	 //echo "adre ".$walletAdrres1;

	 if(!empty($walletAdrres1)){
	      echo "Konto zostało stworzone obecny adres to: <br>";
	      echo $walletAdrres1."<br><br>.";
	      unset($_POST["seed"]);
	  }else{
	 echo 'Błąd logowania';
	 }
	 // soll do podpisywania transakcji
	 // $soll = md5(md5($seed).$walletAdrres);
	}else{
	  echo "Logowanie nie poprawne, sprawdź ilość liter, nie mniej od 10 !";
	  }
   }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
 <title>Wallet Coin</title>
 
<meta charset="utf-8"/>
<meta content="pl"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="To jest test html5" />
<meta name="keywords" content="html, html5" />
<?php
    if(!empty($walletAdrres1)){
	?>
      <meta http-equiv='refresh' content='0; url=balance.php'/>
	  <?php 
	  }
      ?>
<link rel="icon" href="/favicon.ico" type="image/x-icon"/>
<link rel="stylesheet" href="./w3css/styl.css">
   </head>
<body>
<div class="container wrapper">
<div class="wrapper">
 <div id="top">
       <h1>Coin Wallet </h1>
	   <p>Logowanie</p>
   </div>
   <div>
      <nav>
	    <ul id="menu">
		<?php
		
		 if(empty($walletAdrres1)){
		 ?>
		   <li><a href="registerWallet.php">Rejestracja</a></li>
		  <?php 
		  }else{
		  ?>
		  <li><a href="logoutQubit.php">LogOut</a></li>
		  <?php
		  }
		   ?>
		</ul>
	  </nav>
  </div>
    <div id="content">
	<?php
          // czy bloki sie zgadzaja
	  	  echo "<br> Is walid Blockchain ".$wallet->isChainValid()."<br>";
		  if(empty($walletAdrres1)){
	?>
	<form method="post" style="clear: both;">
	      <hr />
        <h3>Logowanie</h3>
      <div><label>Podaj Hasło do Portfela</label><input type="text" name="seed" /></div>
      <hr />
      <input type="submit" value="Logowanie" />
    </form>
	<?php  
	}
	?>
    </div>
  </div>
  <footer id="footer"> 
to jest footer i dół strony html 5 
</footer>
</div>
</body>
</html>
