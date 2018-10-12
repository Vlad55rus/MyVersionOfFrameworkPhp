<?php
 abstract class Model
 {
   protected $config = null;
   protected $db = null;
   protected $loader = null;
   
   public function __construct()
   {
     $this->config = Configuration::getConfiguration();
     $this->db = DB::getInstance(); 
     $this->loader = new Loader();
   }
 }