<?php

//*** License ***//
//*** Attribution-NonCommercial 3.0 Poland (CC BY-NC 3.0 PL) ***//

include_once ("./class/WalletClass.php");

class WalletAcont{

// Dołączamy do Wallet
	// public $seed;
	public $soll;
	public $walletAdres;
	
	public function __construct($seed){
		
		// klucz publiczny i klucz prywatny
		    $wallet = new Wallet();

		    $wallet->setCreateAdresWallet($seed);
			
		   $this->walletAdres = $wallet->getAdresWallet();
		   //$this->seed = $seed;
		   
		   // klucz publiczny
		    $this->soll = md5($seed.$this->walletAdres);
	       // }
	}
}
