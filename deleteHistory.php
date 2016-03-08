<?php

session_start();
error_reporting(0);

$username = $_SESSION["loggedIn"];

$sure = $_GET["sure"];

if ($sure == "yes"){
	$link = mysqli_connect("localhost", "root", "", "b7_17103820_Alpha");
	mysql_select_db( 'b7_17103820_Alpha' );

	$sql = "UPDATE users SET history='' WHERE username='$username'";

	if (mysql_query($sql) === TRUE) {
		//echo "Record updated successfully";	
	} else {
		//echo "Error updating record: " . $link->error;
	}

	// Close connection
	mysql_close($link);
}

header("Location: index.php");
die();


?>