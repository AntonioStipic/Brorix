<?php

header("content-type: text/html; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
error_reporting(0);

$q = $_GET["q"];
$tmpQ = $q;
$cookie_name = "lastSearch";
$cookie_value = "$q";
setcookie($cookie_name, $cookie_value, time() + 60);
$q = str_replace(" ", "%20", $q);

if ($q != ""){
	$searchResultLink = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=$q&key=AIzaSyBcn4iHA6NhI5PA61gu--l0_nSPYSYnX24&maxResults=25&type=video";
	$searchResultKod = file_get_contents($searchResultLink);

	$json = json_decode($searchResultKod);

	for ($i = 0; $i < 25; $i++){
		$title[$i] = $json->items[$i]->snippet->title;
		$videoId[$i] = $json->items[$i]->id->videoId;
		$thumbnail[$i] = $json->items[$i]->snippet->thumbnails->default->url;
	}
}?>

			<?php
			for ($i = 0; $i < 25; $i++){
				echo "<a href=\"watch.php?v=$videoId[$i]\"><div id=\"song[$i]\" class=\"songList\">
					<img src=\"$thumbnail[$i]\">
					<span><b>$title[$i]</b></span>
				</div></a>
				";
			}

			?>