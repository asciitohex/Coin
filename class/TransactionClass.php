<?php

class Transaction{
	// Dołączamy do Wallet
	public $fromAdres;
	public $toAdres;
	public $amount;
	public $soll;
	
	public function __construct($fromAdres, $toAdres, $amount, $soll){
		
		$this->fromAdres = $fromAdres;
		$this->toAdres   = $toAdres;
		$this->amount    = $amount;
		$this->soll      = $soll;
	}
}
