<?php
  /*
    AUTO-GENERATED CLASS
    Generated 9 Aug 2013 12:03
  */
  class Cake extends Model{
    
    function __construct(){
      $this->Model( get_class($this) );
      $this->addField( Field::create( "strName" ) );
      $this->addField( Field::create( "strIcing" ) );
      $this->addField( Field::create( "cnfVegan" ) );
      $this->addField( Field::create( "chdUser", "displayname=Fans" ) );
    }
  }
?>