<?php
include_once ("./class/AlgoHashClass.php");

//*** License ***//
//*** Attribution-NonCommercial 3.0 Poland (CC BY-NC 3.0 PL) ***//

class Block {
	
	public $index;
	public $timeStemp;
	public $data=array();
	public $dataAcont=array();
	public $dataImg=array();
	public $previousHash;
	public $hashC;
	public $nonce;
	public $calculateHash;
	public $QubitCoinCount=array();
	// czy trafiono blok
	private $blocReward = false;
	
	/**
	   Maksymalny rozmiar dodawanego załącznika
	   
	   TB pow(1024, 4)
	   GB pow(1024, 3)
	   MB pow(1024, 2)
	   Kb 1024
	   
	   public $maxBlock = pow(1024, 2);
	*/
	   // 2 MB  4000 tys transakcji
	private $maxBlock = 2097152;
	
	
	// dodać balans wszystkich kojnów do pierwszego bloku a potem odejmować od niego
	public function __construct($index, $timeStemp, $data, $dataImg = null, $dataAcont = null, $previousHash = null, $QubitCoinCount = null){
		
		$this->index        = $index;
	    $this->timeStemp    = $timeStemp;
		
		// Dzielimy date na części jak 
		// jest większa od określonego bloku
		// echo "<br> Wielkość Danych Data ".strlen(serialize($data))."<br>";
		
		if(strlen(serialize($data)) < $this->maxBlock){
		 $this->data         = $data;
		 
		}else{
		echo 'Za duży blok Transakcji !';
		// Dopisać algorytm do zwracania Transakcji
		// Dodawania do koleiki
		}
	   
		$this->dataImg  = $dataImg;
		
		$this->dataAcont       = $dataAcont;
	    $this->previousHash    = $previousHash;
		$this->QubitCoinCount  = $QubitCoinCount;
		$this->hashC           = $this->calculateHash();
		$this->nonce = 0;
		
		// przypisujemy tak jaki jest pierwszy blok
		$this->calculateHash = "4aee9e1fb113f7b196b52add0e4a8521e6c3578971dc7be72b7e6b59c3a2b2123d7a6403546f39c4b2233df962a7f1a07e48b3cd131f27d8ce98a4dcc4142746";
	}
	
	public function calculateHash(){
		
		         $h = new AlgoHash();
				 
				 ## Proof of state
			  //return $h->HashCC($this->index.$this->timeStemp.$this->data.$this->dataImg.$this->previousHash);
			  ## return Proof of Work  
			  $chain = $this->index.$this->timeStemp.json_encode($this->data).json_encode($this->dataImg).json_encode($this->dataAcont).$this->nonce.$this->previousHash.json_encode($this->QubitCoinCount);
			  
		   return $h->HashCC($chain);
	}

	public function mineBlock($difficulty){
        
     // Proof of work
	 // Trudność kopania

		for($i=0; $i< $difficulty; $i++){
		    $s[0] = substr_replace($s[0],'0',0,0);
		}

			// sprawdza na poczatku zera
			 while(substr($this->hashC,0,$difficulty) !== $s[0]){
			// sprawdza w calosci zera
			$this->nonce++;
			$this->hashC = self::calculateHash();
			$this->calculateHash = self::calculateHash();
		}
		
		 if(substr($this->hashC,0,$difficulty) === $s[0]){
                   $this->blocReward = true;
	            }else{
			      $this->blocReward = false;
			    }
		
		echo 'Block Mined:: '.$this->hashC.' <br>';
	}
	
	public function getMineBlockReward(){

	    return $this->blocReward;
	}
	
	public function mineBlockState($chain){
	       // Wymaga dokończenia
           // ilosc wymaganych coinow dla nagrody
	        $balansWymagany = 0.5;
	          // print_r($chain);

	  // Proof of state
	     $adres = array();
		 $adres1 = array();
		 if(count($chain) > 0){
	for($i=0; $i<count($chain); $i++){
		for($j=0; $j<count($chain[$i]->data); $j++){
		   
			   if($chain[$i]->data[$j]->amount > 0){
				  $adres[$i][(string) $chain[$i]->data[$j]->fromAdres] = $chain[$i]->data[$j]->amount;
				  $adres1[$i][(string)$chain[$i]->data[$j]->toAdres] = $chain[$i]->data[$j]->amount;
		        }
		    }
		}
		      // adam1 -1, adam2 +1
		for($i=0; $i<count($chain); $i++){
		  for($j=0; $j<count($chain[$i]->data); $j++){
			   $r  = array_keys($adres[$i]);	
			 if($chain[$i]->data[$j]->fromAdres == $r[$j]){
			 
			 if(array_sum($adres[$i]) > $balansWymagany){
				  $adresy[$i] = $chain[$i]->data[$j]->fromAdres;
			    }
			 }
                $rk = array_keys($adres1[$i]);
				
			 if($chain[$i]->data[$j]->toAdres == $rk[$j]){
			 
			 if(array_sum($adres1[$i]) > $balansWymagany){
				  $adresy1[$i] = $chain[$i]->data[$j]->toAdres;
				   array_push($adresy, $adresy1[$i]);
			    }
			 }
		  }
		}
		
		// przekazywanie nagrody kopiącemy za znalezienie bloku
			  $tabRand = array();
			  $tabAdres = array();
			  
			  $i = 0;
			  if(count($adresy1) > 0){
	              while(count($tabRand) < 5){
	                $tabRand[] = mt_rand(1, count($adresy1));
	                 $tabRand = array_unique($tabRand);
					  if($adresy1[$tabRand[$i]] != null){
					  $this->nonce++;
			          $this->hashC = self::calculateHash();
			          $this->calculateHash = self::calculateHash();
	                  $tabAdres[] = $adresy1[$tabRand[$i]];
					     }
		               $i++;
			  }
			    }
			       }
			
           // print_r($tabAdres);
		// adresy zwycięsców
		 return $tabAdres;
	}
}

