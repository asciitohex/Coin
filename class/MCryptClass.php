<?php

//*** License ***//
//*** Attribution-NonCommercial 3.0 Poland (CC BY-NC 3.0 PL) ***//

class classMcrypt{
   
     private $keys;
	  private $iv_size;
     
	 public function __construct()
	 {	 
		 $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		 $this->keys    = "adsasdsadasdasdad7343478678";
		  //$this->keys    = rand(1,9999999999999);
	 }
	 
  public function kodowanie($string)
  {
	  
      $iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
      $kk = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->keys, $string, MCRYPT_MODE_CBC, $iv);
    return base64_encode($iv.$kk);
  }
  
  public function dekdowanie($string)
  {
	     $stringBase =  base64_decode($string);
        $iv_dec     = substr($stringBase, 0, $this->iv_size);
	     $stringBase = substr($stringBase, $this->iv_size);
     return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->keys, $stringBase, MCRYPT_MODE_CBC, $iv_dec);
  }
}
