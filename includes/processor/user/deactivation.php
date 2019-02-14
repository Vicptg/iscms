<?php defined('isQUERY') or die;
	
	$result = dbUser('deactivation', $_GET);
	
	if ($result && $result == 1) {
		print "All old registration notes is deleted.";
		
	} else {
		print "Sorry, an error occurred while connecting to the database.";
	}
	
	exit;
	
?>