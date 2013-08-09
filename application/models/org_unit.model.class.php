<?php
  /*
    AUTO-GENERATED CLASS
    Generated 22 Mar 2012 11:37
  */
  require_once( "core/model.class.php" );
  require_once( "core/db.class.php" );

  class OrgUnit extends Model implements iFeature {
    
    function getFeatureDescription(){
      return "Mirrors the full hierachical list of organisation units in the HR system";
    }
    
    function OrgUnit(){
      $this->Model( "OrgUnit" );
      $this->addAuth( "role", "Staff", "r" );
      $this->addField( Field::create( "strName" ) );
      $this->addField( Field::create( "strCode" ) );
      $this->addField( Field::create( "strShortName" ) );
      $this->addField( Field::create( "lstParentId", "belongsto=OrgUnit" ) );
      $this->addField( Field::create( "chdOrgUnit", "displayname=Child Units;linkkey=parent_id" ) );
    }
    
    function afterCreateTable(){
      $this->recache();
    }
    
    /**
    * Get full list of org units from HR 
    */
    function recache($aArgs=array()){

    }
  }
?>