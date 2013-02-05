<?php
/* init */
$live = true;

if ($live == true) {
// For live server 
	$dbh=mysql_connect("localhost", "DBUSER", "DBPASS") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db("otwikiflash");
	$server = 'http://otwikiflash.net';
	$usecaptcha = false;
} else {
// For development server
	$dbh=mysql_connect("localhost", "DBUSER-Test", "DBPASS-Test") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db("lamp-wikiflash");
	$server = 'http://lamp.otjobinfo';
	$server = 'http://lamp.otwikiflash';
	$usecaptcha = false;
}


// Used all over the shop
function q($query,$assoc=1) {
   $r = @mysql_query($query);
   if( mysql_errno() ) {
       $error = 'MYSQL ERROR #'.mysql_errno().' : <small>' . mysql_error(). '</small><br><VAR>'.$query.'</VAR>';
       echo($error); return FALSE;
   }
   if( strtolower(substr($query,0,6)) != 'select' ) return array(mysql_affected_rows(),mysql_insert_id());
   $count = @mysql_num_rows($r);
   if( !$count ) return 0;
   if( $count == 1 ) {
       if( $assoc ) $f = mysql_fetch_assoc($r);
       else $f = mysql_fetch_row($r);
       mysql_free_result($r);
       if( count($f) == 1 ) {
           list($key) = array_keys($f);   
           return $f[$key];
       } else {
           $all = array();
           $all[] = $f;
           return $all;
       }
   } else {
       $all = array();
       for( $i = 0; $i < $count; $i++ ) {
           if( $assoc ) $f = mysql_fetch_assoc($r);
           else $f = mysql_fetch_row($r);
           $all[] = $f;
       }
       mysql_free_result($r);
       return $all;
   }
}
?>
