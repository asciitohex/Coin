<?php

class classMcrypt{
   
       static private $key = "12Abgtu677.klhK";
     
  public function kodowanie($string){
      $kk = mcrypt_cbc(MCRYPT_TripleDES, self::$key, $string, MCRYPT_ENCRYPT, "12345678");
    return base64_encode($kk);
  }
  
  public function dekdowanie($string){
     return mcrypt_cbc(MCRYPT_TripleDES, self::$key, base64_decode($string), MCRYPT_DECRYPT, "12345678");
  }
}
