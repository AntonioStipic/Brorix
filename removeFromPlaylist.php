<?php

session_start();
echo "1";

if (isset($_SESSION["loggedIn"])){
	$videoId = $_POST["videoId"];
	$playlistId = $_POST["playlistId"];
	echo "2";

	$username = $_SESSION["loggedIn"];

	$link = mysql_connect("localhost", "root", "", "b7_17103820_Alpha");
	mysql_select_db( 'b7_17103820_Alpha' );

echo "$playlistId";

	$getit = mysql_query("SELECT * FROM playlists WHERE ID='$playlistId'", $link) or die(mysql_error());
	$row = mysql_fetch_array($getit);
	$songs = $row["songs"];

	echo "4";
	echo $songs;
	if (strpos($songs, $videoId) !== false) {
		$songs = str_replace($videoId, "", $songs);
	}
	echo "$songs";

	$sql = "UPDATE playlists SET songs='$songs' WHERE ID='$playlistId'";

	if (mysql_query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $link->error;
	}

	// Close connection
	mysql_close($link);
}

?>