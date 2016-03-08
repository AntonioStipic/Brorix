<?php

session_start();

$videoId = $_SESSION["videoId"];
if ($videoId != ""){
	$url = "watch.php?v=" . $videoId;
}else{
	$url = "index";
}

unset($_SESSION['loggedIn']);

header("Location:$url");
die();

?>