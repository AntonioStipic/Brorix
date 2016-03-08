<?php

session_start();

if (isset($_SESSION["loggedIn"])){
	$videoId = $_POST["v"];

	$username = $_SESSION["loggedIn"];

	$link = mysqli_connect("localhost", "root", "", "b7_17103820_Alpha");
	mysql_select_db('b7_17103820_Alpha');

	$getit = mysql_query("SELECT * FROM users WHERE username = '$username'", $link) or die(mysql_error());
	$row = mysql_fetch_array($getit);
	$favorites = $row["favorites"];

	if (strpos($favorites, $videoId) !== false) {
		
	}else{
		$favorites = $favorites . $videoId;
	}

	$sql = "UPDATE users SET favorites='$favorites' WHERE username='$username'";

	if (mysql_query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $link->error;
	}

	// Close connection
	mysql_close($link);
}

?>