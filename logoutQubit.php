<?php
//*** License ***//
//*** Attribution-NonCommercial 3.0 Poland (CC BY-NC 3.0 PL) ***//

session_start();

	include_once ("./class/SessionClass.php");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
 <title>Wallet Coin</title>
 
<meta charset="utf-8"/>
<meta content="pl"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="description" content="To jest test html5" />
<meta name="keywords" content="html, html5" />
<meta http-equiv='refresh' content='2; url=loginQubit.php'/>
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="./w3css/styl.css">
   </head>
<body>
	<?php
	 $sesion = new SessionClass();
	 $sesion->destroy();
	 echo 'Po 2 sek. zostaniesz przeniesiony do logowania';
	?>
</body>
</html>
