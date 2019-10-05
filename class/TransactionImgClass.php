<?php
include_once("MCryptClass.php");

class TransactionIMG{
	// dołączmy do Wallet
	public $fromAdres;
	/**
	   Opłata pobierana podczas dodania 
	   załącznika
	*/
	public $amountIMG = 34;
	/** 
	   załącznik
	*/
	public $file;
	/** 
	   załącznik nazwa 
	*/
	public $nameFile;
	
	/**
	format załączonego pliku np txt, png, jpeg itd...
	*/
	public $format;
	
	/**
	   Maksymalny rozmiar dodawanego załącznika 5120 kb
	   
	   TB pow(1024, 4)
	   GB pow(1024, 3)
	   MB pow(1024, 2)
	   Kb 1024
	   
	   public $maxFile = pow(1024, 2);
	*/
	 
	public $maxFile = 512000;
	
	public function __construct($fromAdres, $file, $nameFile, $format){
	
		if(filesize($file) < $this->maxFile){
		   if(file_exists($file)){
		      $uchwyt      = fopen($file, "rb");
              $trescFile   = fread($uchwyt, filesize($file));
	           fclose($uchwyt);
		 }

	         $crypt = new classMcrypt();

		$this->fromAdres = $fromAdres;
		$this->file      = $crypt->kodowanie($trescFile);
		$this->format    = $format;
		$this->nameFile  = $nameFile;
		$this->amountIMG;
		
		}else{
		// echo "<br> Załącznik jest za duży -> ".round(filesize($file)/1024, 2)." Dopuszczalny rozmiar to ".round($this->maxFile/1024, 2)."  <br>";
		      $this->error = 1;
		}
	}
	
	// raczej się jesj nie wykorzysta
	public function errorImg(){
	
	if(!empty($this->error)){
	
	echo "<br> Załącznik jest za duży -> Dopuszczalny rozmiar to ".round($this->maxFile/1024, 2)."  <br>";
	  }
	}
}
