<?php
include_once ("./class/DataBaseClass.php");
include_once ("./class/AlgoHashClass.php");
include_once ("./class/MCryptClass.php");

class Wallet{
	/**
	Zrobić kodowanie sida tylko przy indiwidualnym 
	kodzie mCrypt
	*/
	
	/**
	ilość znaków w haśle do konta
	jak zmieniamy to w klasie xxx też
	*/
	private $charAcontPassword = 10;
	private $privateSeed;
	public  $fromAdres;
	public  $toAdres;
	public  $amount;
	private $blockChain;
	private $db;
	private $db1;
	private $adresWalet;
	private $userAcont;
	private $seed;
	private $userAcontCount = array();
	private $mCrypt;
	private $algoHash;
	
	public function __construct(){
	
		 
		              $db = new DataBase();
         $this->db  = $db->dbBlockChain();
		 $this->db1 = $db->dbWalletAcont(); 
		 $this->mCrypt = new classMcrypt();
		 $this->algoHash = new AlgoHash();
		
	}
	
	public function balanceWallet($adres){
	
		// odczyt blockchaina następuje z Wallet
		$balance = $this->getBalanceOfAdres($adres);

		return $balance;
	}
	
	public function setCreateAdresWallet($seed){
		
		// adres musi być większy niż 8 znaków
		   if(strlen($seed) < $this->charAcontPassword){
		       return false;
               }
			   
	   $this->adresWalet = $this->algoHash->HashCC($seed); 
	   
	   // private Seed to hasło(seed) + adres portfela
	    $this->privateSeed = md5(md5($seed).$this->algoHash->HashCC($seed));
		 
	        $this->setUserAcontAll($this->adresWalet);
            $this->getUserAcontCount();
	  
	   // zapobieganie wprowadzenia tego samego konta do bazy
	   
		if($this->userAcont === 0 || $this->userAcont === null){
	   
              $stmt1 = $this->db1->prepare("INSERT INTO `useracont` (idUser, privateSeed, adresWallet, seedWallet) VALUES (:idUser, :privateSeed, :adresWallet, :seedWallet)");
    		  $stmt1->bindValue(':idUser', count($this->userAcontCount)+1, SQLITE3_INTEGER);
			  $stmt1->bindValue(':privateSeed', $this->privateSeed);
			  $stmt1->bindValue(':adresWallet', $this->adresWalet);
			  $stmt1->bindValue(':seedWallet', $this->mCrypt->kodowanie($seed));
			  $stmt1->execute();	
          } 
	}
	
	public function setAdresWallet($seed){
	
	$this->adresWalet = $this->algoHash->HashCC($seed); 
	}
	
	public function getAdresWallet(){
	
	  return $this->adresWalet;
	}
	
	public function getPrivateSeed(){
	// prywatny klucz
	// zapisujemy go tylko na portfelu komputera
	  return $this->privateSeed;
	}
	
	public function isSollWallet($soll){
	   /**
	    Potwierdzenie następuje po odczytaniu solla z Transakcji czy 
		jest równy prywatnemu adresowi z portfela i adresu waleta
		jeżeli tak to true
	   */
	   
	    // cały blockChain z bazy danych
		    $chain = $this->getBlockAll();

	          $acontWallet = $this->getUserAcontCount();
			  
		  for($i=0; $i<count($acontWallet); $i++){
		    
	         $seed = $this->mCrypt->dekdowanie($this->getSeedDB($acontWallet[$i]));
	
			 $privateSeed = md5(md5(trim($seed)).trim($acontWallet[$i]));
			
			 // klucz publiczny
			  $publicSeed = md5(trim($seed).trim($acontWallet[$i]));		 
			  
			 // soll to prywatny Seed + adres portfela
			 for($j=0; $j<count($chain); $j++){
			   for($k=0; $k<count($chain[$j]->dataAcont); $k++){
			   
		          if($privateSeed === $soll && $publicSeed === $chain[$j]->dataAcont[$k]->soll){
			          // echo "<br>Jestem tu isSoll <br>";
		                  return 1;
		                }
				      }
				   }
		        }
	          return 0;
	}
	
	private function getBalanceOfAdres($adres){
        $chain = array();
		$balance = 0;
		   // cały blockChain z bazy danych
		    $chain = $this->getBlockAll();

		for($i=0; $i<count($chain); $i++){
		  for($j=0; $j<count($chain[$i]->data); $j++){		
			 
			  #### create Transaction balance - ####
		       if($chain[$i]->data[$j]->fromAdres === $adres){
			      $balance -= $chain[$i]->data[$j]->amount;
		           }
				   
               #### create Transaction balance + ####
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
	
		public function getIsAdresAcont($adres){
        $chain = array();
		$balance = 0;
		   // cały blockChain z bazy danych
		    $chain = $this->getBlockAll();

		for($i=0; $i<count($chain); $i++){
		  for($j=0; $j<count($chain[$i]->data); $j++){		
			 
			  #### create Transaction balance - ####
		       if($chain[$i]->dataAcont[$j]->waletAdrres === $adres){
			       return 1;
		           }else{
				   return 0;
				   }
		  }
		}
		 return 0;
	}
	
		private function getBlockAll(){
	    
	    $stmt1 = $this->db->prepare('SELECT blockChain FROM `blockchaincoin`');
	         $result1 = $stmt1->execute();

	          $chain = array();
         while($cha = $result1->fetchArray(SQLITE3_ASSOC)){
			   array_push($chain, json_decode($cha['blockChain']));
         }
		 
		return $chain;
	}
		private function getCalculateHashChain($chain, $index){

    $chain1 = $this->algoHash->HashCC($chain[$index]->index.$chain[$index]->timeStemp.json_encode($chain[$index]->data).json_encode($chain[$index]->dataImg).json_encode($chain[$index]->dataAcont).$chain[$index]->nonce.$chain[$index]->previousHash.json_encode($chain[$index]->QubitCoinCount));	
	     return $chain1;
	}
	
	public function setUserAcontSeed($userWallet){
	    
	    $stmt1 = $this->db1->prepare('SELECT privateSeed, adresWallet FROM `useracont` WHERE adresWallet = :adresWallet');
	         $stmt1->bindValue(':adresWallet', $userWallet);
			 $result1 = $stmt1->execute();

         while($cha = $result1->fetchArray(SQLITE3_ASSOC)){
		    if(!empty($cha['adresWallet'])){
		       return $cha['adresWallet'];
		      }else{
			   return 0;
			  }
            }
	}
	
	public function setUserAcontAll($userWallet){
	    
	    $stmt1 = $this->db1->prepare('SELECT adresWallet FROM `useracont` WHERE adresWallet = :adresWallet');
	         $stmt1->bindValue(':adresWallet', $userWallet);
			 $result1 = $stmt1->execute();

         while($cha = $result1->fetchArray(SQLITE3_ASSOC)){
		    if(!empty($cha['adresWallet'])){
		       $this->userAcont = 1;
		      }else{
			   $this->userAcont = 0;
			  }
            }
	}
	
	public function getUserAcontCount(){
	    
	    $stmt1 = $this->db1->prepare('SELECT adresWallet FROM `useracont`');
			 $result1 = $stmt1->execute();

			  while($cha = $result1->fetchArray(SQLITE3_ASSOC)){
			   array_push($this->userAcontCount, $cha['adresWallet']);
         }
		 return $this->userAcontCount;
	}
	public function getSeedDB($userWallet){
	
	   $stmt1 = $this->db1->prepare('SELECT seedWallet FROM `useracont` WHERE adresWallet = :adresWallet');
	         $stmt1->bindValue(':adresWallet', $userWallet);
			 $result1 = $stmt1->execute();

			  while($cha = $result1->fetchArray(SQLITE3_ASSOC)){
			  return  $cha['seedWallet'];
         }
	}
	
	public function getFileImgCount($adres){
	
	        $chain = array();
		   // cały blockChain z bazy danych
		    $chain = $this->getBlockAll();
			
		    $tablica = array();
	
		for($i=0; $i<count($chain); $i++){
		  for($j=0; $j<count($chain[$i]->dataImg); $j++){
		  
	        if($chain[$i]->dataImg[$j]->fromAdres === $adres){
			
			$tablica[$i][$j]['file']     = $chain[$i]->dataImg[$j]->file;
			$tablica[$i][$j]['fileName'] = $chain[$i]->dataImg[$j]->nameFile;
		        }
			}
		}
		
		return $tablica;
	}
	
	public function getFileIMG($adres, $ktoryFile){
      
	          $tabFilt = explode('-', $ktoryFile);
	       // ścieżka gdzie zapisać załączniki
	        $sciezkaFile ="./imgReceived/";
	        // Tablica dwu wymiarowadwó
              $tab = $this->getFileImgCount($adres);
		
		if(!empty($tab[$tabFilt[0]][$tabFilt[1]]['file']) && !empty($tab[$tabFilt[0]][$tabFilt[1]]['fileName'])){
			  $crypt = new classMcrypt();
		 $trescFile1 = $crypt->dekdowanie($tab[$tabFilt[0]][$tabFilt[1]]['file']);
              $uchwyt1 = fopen($sciezkaFile.$tab[$tabFilt[0]][$tabFilt[1]]['fileName'], "w"); 
			                                                      // UTF-8
	         // fwrite($uchwyt1, $trescFile1, mb_strlen($trescFile, latin1));
                      fwrite($uchwyt1, $trescFile1, strlen($trescFile1));
	                  fclose($uchwyt1);
		return true;
		}
	 return false;
	}
	
	public function isChainValid($newBlockChain = null){
		
		if($newBlockChain == null){
		// Cały blockChain z bazy
		  $chain = $this->getBlockAll();
		  }else{
		 // nowy blok dodany do chaina
		   $chain = $newBlockChain;
		  }
		
	   // echo count($this->chain);
	        $CurentBlock   = array();
		    $PreviousBlock = array();
	       // print_r($this->chain);
	
		for($i=1; $i< count($chain); $i++){
			
			// $CurentBlock[$i] = $chain[$i];
			$PreviousBlock[$i] = $chain[$i-1];
			$curentHashAll = $this->getCalculateHashChain($chain, $i);
			
				if($chain[$i]->calculateHash === null ||
		        	$curentHashAll === null ||
			        $chain[$i]->calculateHash !== $curentHashAll){
			
			         return 4;
			       }
			
			if($chain[$i]->hashC !== $chain[$i]->calculateHash ||	
			     $chain[$i]->hashC === null ||
			     $chain[$i]->calculateHash === null){
				// zmienic na false
				return 2;
			}
			
			if($PreviousBlock[$i]->hashC !== $chain[$i]->previousHash || 
			   $PreviousBlock[$i]->hashC === null ||
			   $chain[$i]->previousHash === null){
				// zmienic na false
				  return 3;
			}
		}
		return true;
	}
}
