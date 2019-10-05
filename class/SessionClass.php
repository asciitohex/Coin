<?php

class SessionClass{

private $instance=null;
private $sesionState = false;

    public function __construct()
    {
	   if($this->sesionState === false)
	    { 
	      $this->sesionState = session_start();
	    }	 
    } 

    public function setSession($keySesion, $valueSesion)
	{
		if($this->sesionState === true)
	    { 
           $_SESSION[$keySesion] = $valueSesion;
		}
    }

    public function getSession($keySesion)
	{
		if($this->sesionState === true)
	    {
           return $_SESSION[$keySesion];
		}
    }
   
   public function destroy()
   {
          $this->sesionState = session_destroy();
            unset($_SESSION);
		  return  $this->sesionState;
   }

}
