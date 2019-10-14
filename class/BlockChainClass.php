<?php

include_once ("./class/DataBaseClass.php");
include_once ("./class/BlockClass.php");
include_once ("./class/TransactionClass.php");
include_once ("./class/TransactionImgClass.php");
include_once ("./class/WalletClass.php");
include_once ("./class/AlgoHashClass.php");
//*** License ***//
//*** Attribution-NonCommercial 3.0 Poland (CC BY-NC 3.0 PL) ***//


class BlockChain{
	
	/**
	tablica blokow
	*/
	private $chain = array();
	/**
	Trudność kopania
	*/
	private $difficulty = 2;
	/**
	ilość znaków w haśle do konta
	*/
	private $charAcontPassword = 10;
	/**
	Tablica transakcji
	*/
	private $pendingTransactions = array();
	/**
	Tablica transakcji nowe konta Wallet
	*/
	private $dataAcont = array();
	/**
	Tablica Transakcji IMG
	*/
	private $dataIMG = array();
	/**
	   nagroda za transakcje
	*/
	public $miningReward = 10;
	/**
	Ogolny balans wszystkich monet
	*/
	public  $balanceAll = 55000000;
	private $db;
	private $imgError;
	private $Coin = 55000000;
	private $error;
	
	public function __construct(){
		
		  $db = new DataBase();
         $this->db = $db->dbBlockChain();
		
		$this->chain = array(self::createGenesisBlock());
		$this->difficulty;
		$this->dataIMG;
		$this->miningReward;
		$this->balanceAll;
	}
	/**
	  Tworzenie pierwszego bloku dla blockchain
	*/
	public function createGenesisBlock(){
	
		$Block = new Block(1, "01-01-2018", $this->createGenesisReward(), "Data IMG", "Acont Wallet", "preview", $this->createQubitCoinBalance());
	     @ $this->db->exec("INSERT INTO `blockchaincoin` (idChain, blockChain) VALUES (1, '".json_encode($Block)."')");
		
		return $Block;
	}
	
	/**
	  Pierwsza transakcja przekazana później do bloku
	  bez sprawdzania soll ostatni parametr to soll
	  parametr transakcji -> addresFrom, adresTo, amount, soll
	*/
	public function createGenesisReward(){

	/// ustawić adresy zakodowane do konta 
		return array(new Transaction( "QubitCoin", "Adam-100000", "1000000", "QubitCoin"),
		             new Transaction( "QubitCoin", "Adam-200000", "1000000", "QubitCoin"),
		             new Transaction( "QubitCoin", "Adam-300000", "1000000", "QubitCoin"),
		             new Transaction( "QubitCoin", "Adam-400000", "1000000", "QubitCoin"),
		             new Transaction( "QubitCoin", "Adam-500000", "1000000", "QubitCoin"),
					 new Transaction( "QubitCoin", "07243a32cd445721c37e1099dffbb0f30d6808e2879d8e8c06bea3dc382dee5b0502f12246fc3f120d44a6f2254b421d667dd3acf2d5abebafac7cc52138f020", "1000000", "QubitCoin"));
		
	}
	/**
	
	 Odejmujemy bilans z pierwszej transakcji 6000000 QubitCoin
	 
	*/
	public function createQubitCoinBalance(){
	
	                       $Coin = ($this->Coin - 6000000);
           
		return array("adres"=>"QubitCoin", "balance"=>$Coin);
	}
	
	/**
	   Proof of Stake
       implementation	   
	*/
	public function minePendingTransactionState($miningRewardAdres){
		// $this->createTransaction(new Transaction( null, $miningRewardAdres, $this->miningReward))
		$endArray = end($this->chain);
		//print_r($this->dataIMG);
		$newBlock = new Block($endArray->index+1, date("Y-m-d | h:i:s"), $this->pendingTransactions, $this->dataIMG, $this->dataAcont);
		// przypisujemy hash z jednego bloku wcześniej
		$newBlock->previousHash = $this->chain[count($this->chain)-1]->hashC;
	    $newBlock->calculateHash = $newBlock->calculateHash();

		echo "Blok sukces...";
           
		// Zanim zapiszemy blok sprawdź czy jest pawidłowy jak nie to szukaj dalej
		
		// zmienić na prepare()
		// print_r($newBlock)."<br> newBlok <br>";
		@ $this->db->exec("INSERT INTO `blockchaincoin` (idChain, blockChain) VALUES (".($endArray->index+1).", '".json_encode($newBlock)."')");
		
		   // $stmt = $this->db->prepare("UPDATE `blockchaincoin` SET blockChain=:blockChain WHERE idChain=:id");
           // $stmt->bindValue(':id', ($endArray->index+1), SQLITE3_INTEGER);
		   // $stmt->bindValue(':blockChain', json_encode($this->chain));
			//$stmt->execute();
				   
		     $stmt1 = $this->db->prepare('SELECT idChain, blockChain FROM `blockchaincoin`');
	         $result1 = $stmt1->execute();
			  
	                $chain = array();
	          $this->chain = array();
         while($cha = $result1->fetchArray(SQLITE3_ASSOC)){
               //$chain[json_decode($cha['idChain'])] = json_decode($cha['blockChain']);
			   array_push($this->chain, json_decode($cha['blockChain']));
         }
              // $this->chain = $chain;
			  // print_r($this->chain);
			 // print_r($this->chain[1]->data[0]->toAdres);
			  
	   ## Proof of State
		$adresyZwycieskie = $newBlock->mineBlockState($this->chain);
		// print_r($adresyZwycieskie);
		
	// wysokość nagrody dla zwycięsców
	$miningReward = array( 1 , 2, 3, 4, 5);
	
		$this->balanceAll = ($this->balanceAll - ($miningReward[0]+
												  $miningReward[1]+
												  $miningReward[2]+
												  $miningReward[3]+
												  $miningReward[4])
							);
					
     if(count($adresyZwycieskie) > 0){					
			 $this->pendingTransactions = array(new Transaction( "QubitCoin1", $adresyZwycieskie[0], $miningReward[0], "QubitCoin1"),
                                    			new Transaction( "QubitCoin2", $adresyZwycieskie[1], $miningReward[1], "QubitCoin1"),
												new Transaction( "QubitCoin3", $adresyZwycieskie[2], $miningReward[2], "QubitCoin1"),
                                    			new Transaction( "QubitCoin4", $adresyZwycieskie[3], $miningReward[3], "QubitCoin1"),
												new Transaction( "QubitCoin5", $adresyZwycieskie[4], $miningReward[4], "QubitCoin1"));
			 }
			           		
		// przekazywanie nagrody kopiącym za znalezienie bloku

		$endArray1 = end($this->chain);
		
		echo "|| ile pętli szukania bloku ".$endArray1->nonce;
		echo "|| dlugosc calego hasa ".strlen($endArray1->hashC)." <br>";
	
	}
	
	/**
	   Proof of Work 
	   implementation
	*/
	
	public function clearTransactionAll(){
	
	$this->pendingTransactions=null;
	$this->dataIMG=null;
	$this->dataAcont=null;
	}
	
    public function minePendingTransaction($miningRewardAdres, $potwierdzenieZSieci=false){
	
		####### dodać validacje #########
		###### usunąć podczas uruchamiania #########
		
		 $seed          = "1234567890";
		 $soll          = md5(md5($seed).$miningRewardAdres);

		   $transaction = new Transaction("QubitCoin", $miningRewardAdres, $this->miningReward, $soll);
		/// cała ilość Coinów				
		    $chainQ = $this->qubitCoinAll();

		if($this->isTransactionRewardValid($transaction) === true){
			array_push($this->pendingTransactions, $transaction);
			
		    $QubitCoinCount = ($chainQ - $this->miningReward);
		 }else{
		    $QubitCoinCount = $chainQ;
		    echo "<br>W pending transaktion Transakcja Error zły balans adresu lub zły adres konta<br>";
		 }

		$endArray = end($this->chain);
		 echo "<br>Ktory index  ". ($endArray->index+1) ."<br>";
		$newBlock = new Block(($endArray->index+1), date("Y-m-d | h:i:s"), $this->pendingTransactions, $this->dataIMG, $this->dataAcont, $previousHash = null, array("adres"=>"QubitCoin", "balance"=>$QubitCoinCount));
		// przypisujemy hash z jednego bloku wcześniej
		$newBlock->previousHash = $this->chain[count($this->chain)-1]->hashC;
	    $newBlock->calculateHash = $newBlock->calculateHash();
		
		## Proof of Work
	    $newBlock->mineBlock($this->difficulty);	
		$blockReward = $newBlock->getMineBlockReward();
		
		// Potwierdzenie wykopania bloku
		if($blockReward === true){
		echo "Blok nr ".($endArray->index+1)." sukces...";
		      array_push($this->chain, $newBlock);
		      $this->chain = array();
		    if($this->isChainValid($this->chain) === true && $potwierdzenieZSieci){
		
		@$stmt = $this->db->prepare("INSERT INTO `blockchaincoin` (idChain, blockChain) VALUES (:id, :blockChain)");	
                 $stmt->bindValue(':id', ($endArray->index+1), SQLITE3_INTEGER);
		         $stmt->bindValue(':blockChain', json_encode($newBlock));
		@$stmt->execute();
			
		    }else{
		
		echo "Chain ==>  Brak potwierdzenia dla operacji ";
		// transakcjie czekające na potwierdzenie
		    }
		}else{
		echo "Blok No sukces...";
		}
           /// cały blockChain z bazy danych				
		    $this->chain = $this->getBlockAll();
		    $endArray1 = end($this->chain);
		
		echo "|| ile pętli szukania bloku ".$endArray1->nonce;
		echo "|| dlugosc calego hasa ".strlen($endArray1->hashC)." <br>";	  
	}

	/**
	 Cały balans Coin od początku ile zostało wydanych
	*/
	
	public function qubitCoinAll(){
	      $chainQ = $this->getBlockAll();
	        for($i=0; $i<count($chainQ); $i++){
			    if($chainQ[$i]->QubitCoinCount->balance !== null){
			            $Q[$i] = $chainQ[$i]->QubitCoinCount->balance;
			     }
	        }
			  $qc = end($Q);
			return $qc;
	}
	
	/**
	    Tworzenie nowej Transakcji i dopisanie do tablicy oczekujących jak takie są
	*/
	public function createTransaction(Transaction $transaction){
		if($this->isTransactionValid($transaction) === true){
			array_push($this->pendingTransactions, $transaction);
		 }else{
		 echo "<br> Transakcja Error zły balans adresu lub zły adres konta<br>";
		 }
	}
	
	/**
	    Tworzenie nowej Transakcji
	*/
		private function isTransactionRewardValid(Transaction $transaction){
		
		         // balans Coin
		$balance = $this->qubitCoinAll();
		      // Balans musi być większy od kwoty transakcji
		      // Adres musi być większy niż okreslona ilość znaków
			  // sprawdzenie soll z solla z portfela
				
             if($balance > $transaction->amount && $transaction->amount <= $this->miningReward){
			       echo "<br> Transakcja przebiegła pomyślnie Reward. <br>";
			        return true;
		        }else{
				    return false;
				}
	}
	
	/**
	   Sprawdzenie poprawności transakcji pod względem solli
	   ilość znaków w adresie
	   czy konto posiada odpowiedni stan gotowki
	*/
	private function isTransactionValid(Transaction $transaction){
	
		$wallet = new Wallet();
		
		$balance = $this->getBalanceOfAdres($transaction->fromAdres);
		      // Balans musi być większy od kwoty transakcji
		      // Adres musi być większy niż okreslona ilość znaków
			  // sprawdzenie soll z solla z portfela
				
             if($balance > $transaction->amount && $transaction->amount > 0 &&
		         strlen($transaction->fromAdres) > $this->charAcontPassword && 
				 // łączymy się z danym waletem proszącym o Transakcje i sprawdzamy 
				 // czy posiada dany seed przez podanie soll
		                $wallet->isSollWallet($transaction->soll)){
			            echo "<br> Transakcja przebiegła pomyślnie Wallet. <br>";
			        return true;
		        }
				
		// echo "Transakcja przebiegła Error nie pomyślnie";
		 // Ma być fale dla testów true
		    return true;
	}
	/**
     Transakcja dla Img i przypisanie do kolejki oczekujących
	 Przenosimy dane do Blockchaina poprzez sieć
	*/
	public function createDataTransactionIMG(TransactionIMG $transactionIMG){
	                           ## IMG ##
                     if($this->isTransactionImgValid($transactionIMG) === true){
			             array_push($this->dataIMG, $transactionIMG);
		                }
	}
	/**
     Validacja dla Img
	*/	
	private function isTransactionImgValid( TransactionIMG $transactionImg){
		
		if(empty($transactionImg->fromAdres)){
		      $this->imgError = false;
		      return false;
		}
		
		$balance = $this->getBalanceOfAdres($transactionImg->fromAdres);
		if($balance >= $transactionImg->amountIMG && $transactionImg->amountIMG > 0){
			 $this->imgError = true;
			return true;
		}
		 $this->imgError = false;
		return false;
	}
	
	/**
	  Sprawdzanie czy transakcja przebiegła prawidłowo
	  wywołanie w $_POST
	*/
	public function isImgValid(){
	   return $this->imgError;
	}
	
	/**
	  Pobieramy Tablice $_FILES 
	*/
	public function fileImg($files, $walletAdrres){
	
	 $maxFile = 512000;
	 $this->error = "";
	 
	
		
	// Tablica dopuszcza te typy do blockchaina
	 $mimeTab = array('text/plain', 'image/jpeg', 'text/html', 'image/png',
	                  'image/gif', 'application/pdf', 'application/msword',
					  'application/vnd.ms-excel', 'application/zip', 'image/vnd.adobe.photoshop',
					  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/x-rar', 'application/octet-stream', 'application/vnd.oasis.opendocument.text');
	
	    if(!empty($walletAdrres)){
	        if ($files['userfile']['error'] === UPLOAD_ERR_OK) { 
	   
	       // echo "mime type: ".$_FILES['userfile']['type'];
	   
	    if(file_exists($files['userfile']['tmp_name'])){ 
				// sprawdzenie długości nazwy nie większej jak 150 znaków
			
		 if(strlen($files['userfile']['name']) < 50){
		       
             if(is_uploaded_file($files['userfile']['tmp_name'])){ 
		           
               if($files['userfile']['size'] > $maxFile){ //Sprawdz czy jest mniejszy od 0,5 MB 
			            
                 $this->error .= "<b style='color: #ff9601;'>Stop Plik większy Od 0,5 MB </b>"; 
                 }else{
				 
				 if(!in_array($files['userfile']['type'], $mimeTab)){ /// sprawdz czy zdjecie - image 
				     
                 $this->error .= "<b style='color: #ff9601;'>Stop plik ma niewłaściwe mime !</b>";                
				}else{ 
			  
			         list($nazwa, $format) = explode(".", $files['userfile']['name']);
					 
	                      $sciezka_pliku = $files['userfile']['tmp_name'];
	                      $nazwa_pliku   = $files['userfile']['name'];
				   
				     $this->createDataTransactionIMG(new TransactionIMG($walletAdrres, $sciezka_pliku, $nazwa_pliku, ".".$format));
	                   
                     // move_uploaded_file($this->files_temp_name_xml(), $scie); /// gdzie zapisac 
                     $this->error.= "<b style='color: #ff9601;'><br>Zapis Udany<br></b>"; 
	                 } 
                   }         
                } 
                  }else{ 
				 
                     $this->error.= "<b style='color: #ff9601;'>Stop Błąd Pliku</b>"; 
               }
		     }else{
			
			       $this->error.= "<b style='color: #ff9601;'>Za długa nazwa !</b>"; 
		          	 }    
           } 
		   }else{
		   $this->error.= "<b style='color: #ff9601;'>Podaj prawidłowy adres !</b>"; 
		   }
	}
	/**
	  Błędy zwrócone podczas przesyłania Files
	  umieszczamy pod fileImg($file)
	*/
	public function errorImg(){
	
	return $this->error;
	}
	/**
     Tworzenie nowego konta Walleta
	 Przenosimy dane do Blockchaina poprzez sieć
	*/
	public function createDataWalletAcont(WalletAcont $waletAcont){
	                 ## Create Acont ##
		
				if(!empty($waletAcont)){
			         array_push($this->dataAcont, $waletAcont);
			      }
	}

	/**
	  Wszystkie konta w kolejce do zatwierdzenia
	*/
	public function getCreateAcontAll(){
		
		return $this->dataAcont;
	}
	/**
	  Wszystkie Transakcje w kolejce do zatwierdzenia
	*/
	public function getTransactionAll(){
		
		return $this->pendingTransactions;
	}
	/**
	  Rozmiar wszystkich transakcji w kolejce do zatwierdzenia
	*/
	public function getTransactionAllSize(){
		
		return strlen(serialize($this->pendingTransactions));
	}
	/**
	  Odczytanie balansu danego adresu
	  Przenieśc do walleta wersie public tutaj private
	*/
	private function getBalanceOfAdres($adres){
         $chain = array();
		$balance = 0;
		   // cały blockChain z bazy danych
		    $chain = $this->getBlockAll();
		for($i=0; $i<count($chain); $i++){
		  for($j=0; $j<count($chain[$i]->data); $j++){		
	          
			  #### create Transaction ####
		       if($chain[$i]->data[$j]->fromAdres === $adres){
			      $balance -= $chain[$i]->data[$j]->amount;
		           }
				   
				 //  print_r($this->chain[$i]->data[$j]->toAdres);
				if($chain[$i]->data[$j]->toAdres === $adres){
			       $balance += $chain[$i]->data[$j]->amount;
		           } 
              #### create data transaction IMG #####
		    if($chain[$i]->dataImg[$j]->fromAdres === $adres){
			      $balance -= $chain[$i]->dataImg[$j]->amountIMG;
		           }
		      }
		}
		return $balance;
	}
	/**
	  pobieramy cały blockchain z bazy danych uprzednio ściągniętej
	*/
	private function getBlockAll(){
	
	    $stmt1 = $this->db->prepare('SELECT blockChain FROM `blockchaincoin`');
	         $result1 = $stmt1->execute();

	          $chain = array();
         while($cha = $result1->fetchArray(SQLITE3_ASSOC)){
			   array_push($chain, json_decode($cha['blockChain']));
         }
		 
		return $chain;
	}
	/**
	  Dane haszowane do sprawdzenia czy chain się zgadza z danymi w bazie blockchaina
	*/
	private function getCalculateHashChain($chain, $index){ 
	   $h = new AlgoHash();
    $chain1 = $h->HashCC($chain[$index]->index.$chain[$index]->timeStemp.json_encode($chain[$index]->data).json_encode($chain[$index]->dataImg).json_encode($chain[$index]->dataAcont).$chain[$index]->nonce.$chain[$index]->previousHash.json_encode($chain[$index]->QubitCoinCount));	
		 return $chain1;
	}
	/**
	  Sprawdzamy cały blockchain czy się zgadza
	*/
	public function isChainValid($newBlockChain = null){
		
		// if($Block == null){
			if($newBlockChain == null){
		// Cały blockChain z bazy
		  $chain = $this->getBlockAll();
		}else{
		 // nowy blok dodany do chaina
		   $chain = $newBlockChain;
		}
	        $CurentBlock   = array();
		    $PreviousBlock = array();
	
		for($i=1; $i< count($chain); $i++){
			
			$CurentBlock[$i]   = $chain[$i];
			$PreviousBlock[$i] = $chain[$i-1];
			
			$curentHashAll = $this->getCalculateHashChain($chain, $i);
			
				if($CurentBlock[$i]->calculateHash === null ||
		        	$curentHashAll === null ||
			        $CurentBlock[$i]->calculateHash !== $curentHashAll){
			         return false;
			       }
			
			if($CurentBlock[$i]->hashC !== $CurentBlock[$i]->calculateHash || 
			   $CurentBlock[$i]->hashC === null ||
			   $CurentBlock[$i]->calculateHash === null){
				// zmienic na false
				return false;
			}
			
			if($PreviousBlock[$i]->hashC !== $CurentBlock[$i]->previousHash || 
			   $PreviousBlock[$i]->hashC == null ||
			   $CurentBlock[$i]->previousHash == null){
				// zmienic na false
				  return false;
			}
		}
		
		return true;
	}
}
