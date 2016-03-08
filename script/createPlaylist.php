<?php

header("content-type: text/html; charset=UTF-8");
session_start();
$playlistName = $_POST["playlistName"];

echo $playlistName;

function unique_id($l = 11) {
	return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}


if (isset($_SESSION["loggedIn"])){
	$playlistID = unique_id();
	$username = $_SESSION["loggedIn"];

	$link = mysqli_connect("localhost", "root", "", "b7_17103820_Alpha");

	$connect = mysql_connect("localhost", "root", "") or die("Couldn't connect!");
	mysql_query ("set character_set_client='utf8'"); 
 	mysql_query ("set character_set_results='utf8'"); 

	mysql_query ("set collation_connection='utf8_general_ci'"); 
	mysql_select_db("b7_17103820_Alpha") or die("Couldn't find db!");

	$query = mysql_query("SELECT * FROM users WHERE username='$username'");

	$fetchTable = array();

	$fetchTable = mysql_fetch_array($query);

	$playlists = $fetchTable["playlists"];

	$playlists = $playlists . $playlistID;
		
	// Check connection
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	// Attempt insert query execution
	$sql2 = "INSERT INTO playlists (ID, name, creator) VALUES ('$playlistID', '$playlistName', '$username')";
	$sql = "UPDATE users SET playlists = '$playlists' WHERE username = '$username'";
	if(mysqli_query($link, $sql)){
		$link->query($sql2);
		header("Location:../playlists");
		die();
	}else{
		echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
 	// Close connection
	mysqli_close($link); 
}

?>