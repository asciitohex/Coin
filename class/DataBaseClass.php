<?php

class DataBase{

        private $db;
        private $db1;

      public function __construct(){
         $this->db  = new SQLite3('./db/CubitQoin.db');
		 $this->db1 = new SQLite3('./db/WalletAcont.db');
       }
	   
	 public function dbBlockChain(){
	 
	 return $this->db;
	 }  
	 
	  public function dbWalletAcont(){
	 
	 return $this->db1;
	 }  
	 // unlink('mysqlitedb.db');
}
