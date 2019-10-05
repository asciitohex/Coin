<?php

class AlgoHash{
	/**
	Kodujemy nasz blockchain tak jak chcemy 
	od sha 256
	do sha 2024
	*/
	public function HashCC($stringHash){
		return hash("sha512", $stringHash, false);
	}
}
