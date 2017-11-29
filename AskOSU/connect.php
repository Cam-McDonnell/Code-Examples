<?php 
	 $dbhost = "oniddb.cws.oregonstate.edu";
      $dbname = "mcdoncam-db";
      $dbuser = "mcdoncam-db";
      $dbpass = "xOwqKHjWfOFiJdfA";

	$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

	if (!$conn){
		die("Connection failed: " . mysqli_connect_error());
	}
?>