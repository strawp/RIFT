<?php
  /*
  * Unit test for the oracle database model
  */
  require_once( "settings.php" );
  class TestOfOracleDb extends UnitTestCase {
    
    function __construct(){
    }
    
    function testConnectToOracleDbServer(){
      $db = new OracleDB();
      // $db->debug = true;
      $this->assertTrue( $db->connect() );
    }

    function testQuery(){
      $db = new OracleDB();
      $db->connect();
      $db->query( "SELECT banner, 'hello oracle' as \"hello\" FROM v$version WHERE banner LIKE 'Oracle%'" );
      $row = $db->fetchRow();
      $this->assertEqual( $row["hello"], "hello oracle" );
    }
  }
?>
