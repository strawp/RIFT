<?php
  require_once( "core/navigation.class.php" );
  require_once( "core/navigation_item.class.php" );
  
  // Render menus
  if( SessionUser::isLoggedIn()){
  
    // Check cache now for expired navigation
    if( Cache::hasModel( "Navigation" ) ){
      addLogMessage( "navigation cached, retrieving" );
      $menu = Cache::getModel("Navigation");
      addLogMessage( sizeof( $menu->aItems )." items" );
    }
    
    // If it's expired and created new, there will be no items
    if( !Cache::hasModel( "Navigation" ) || sizeof( $menu->aItems ) == 0 ){
      $menu = new Navigation();
      
      switch( SITE_INTERFACE ){
        default:
          $menu->addItem( new NavigationItem( "Home", "/" ) );
          switch( SessionUser::getProperty("role") ){
            case "Staff":
              
              /*
                Quick access
              */
              $nav = new NavigationItem( "Shortcuts" );
              $nav->addChild( new NavigationItem( "My issues", "issue/user_id/".SessionUser::getId() ) );
              $nav->addChild( new NavigationItem( "My profile", "wizard/my_profile" ) );
              if( sizeof( $nav->aChildren ) > 0 ) $menu->addItem( $nav );
              
              /*
                Search by
              */
              $nav = new NavigationItem( "Search for" );
              $nav->addChild( new NavigationItem( "People", "user" ) );
              $nav->addChild( new NavigationItem( "Issues", "issue" ) );
              $nav->addChild( new NavigationItem( "Organisation units", "org_unit" ) );
              $menu->addItem( $nav );
              
              /*
                Reports
              */
              $rpt = new NavigationItem( "Reports" );
              $ni = new NavigationItem( "Management" );
              $ni->addChild( new NavigationItem( "Agenda", "report/agenda" ) );
              $ni->addChild( new NavigationItem( "Issues due a reponse", "report/issue_reponse_due" ) );
              $ni->addChild( new NavigationItem( "Deferred issues", "report/deferred" ) );
              $rpt->addChild( $ni );
              
              if( SessionUser::isAdmin() ){
                $ni = new NavigationItem( "System" );
                $ni->addChild( new NavigationItem( "List site features", "report/features" ) );
                $ni->addChild( new NavigationItem( "List schema", "report/schema" ) );
                $ni->addChild( new NavigationItem( "List field types", "report/fields" ) );
                $rpt->addChild( $ni );
              }

              $menu->addItem( $rpt );
              
              if( SessionUser::isAdmin() ){
                $admin = new NavigationItem( "Admin" );
                $admin->addChild( new NavigationItem( "Import New Users", "user_import" ) );
                $admin->addChild( new NavigationItem( "User Groups", "user_group" ) );
                $admin->addChild( new NavigationItem( "Issues", "issue" ) );
                $admin->addChild( new NavigationItem( "E-mail users", "mailer" ) );
                $ni = new NavigationItem( "Change Log", "change_log" );
                $ni->addChild( new NavigationItem( "Add new", "change_log/new" ) );
                $admin->addChild( $ni );
                $menu->addItem( $admin );
              }
              break;
          }
          $menu->addItem( new NavigationItem( "Site map", "map" ) );
          break;
          
        case "SIMPLE":
          $menu->addItem( new NavigationItem( "People", "user" ) );
          break;
      }
      // $menu->addItem( new NavigationItem( "Log out", "logout" ) );
      Cache::storeModel( $menu, "Navigation" );
    }else{
      $menu = Cache::getModel( "Navigation" );
    }
    
    // Add this page to the breadcrumb trail
    echo Breadcrumb::getHistoryFromCurrentPage();
    echo $menu->render();
  }
?>
