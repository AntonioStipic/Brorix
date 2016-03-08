<?php

session_start();

if (isset($_SESSION["loggedIn"])){
	if ($_SESSION["loggedIn"] == "Antonio"){
		// START connect to database
		$connect = mysql_connect("localhost", "root", "") or die("Couldn't connect!");
		mysql_set_charset('utf8', $connect);
		mysql_select_db("b7_17103820_Alpha") or die("Couldn't find db!");
		// END


		// START find video IDs of first 50 songs on this playlist which shows the most popular songs today/this week and saves to array videoID
		$kod = file_get_contents("https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=PLrEnWoR732-BHrPp_Pm8_VleD68f9s14-&key=AIzaSyBcn4iHA6NhI5PA61gu--l0_nSPYSYnX24");
		$oldPos = 0;

		$playlistIDs = "";

		for ($i = 0; $i < 50; $i++) { 
			$pos[$i] = strpos($kod, 'videoId": "', $oldPos);
			$videoId[$i] = substr($kod, $pos[$i] + 11, 11);
			$oldPos = $pos[$i] + 1;
			$playlistIDs = $playlistIDs . $videoId[$i];
		}

		mysql_query('TRUNCATE TABLE songs;');

		for ($i = 0; $i < 50; $i++){
			// START finds video title of all 50 songs and saves them to array $titleTop, also saves title and ID to database
			$kod2 = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=id%2Csnippet&id=$videoId[$i]&key=AIzaSyBcn4iHA6NhI5PA61gu--l0_nSPYSYnX24");
				
			//echo $vrijednosti;
			$pos2[$i] = strpos($kod2, '"title":');
			$titleTop[$i] = substr($kod2, $pos2[$i] + 10, strpos($kod2, '"', $pos2[$i]));
			$titleTop[$i] = substr($titleTop[$i], 0, strpos($titleTop[$i], '"'));
			$titleTop[$i] = str_replace("'", "", $titleTop[$i]);
			$titleTop[$i] = str_replace("\"", '', $titleTop[$i]);
			$titleTop[$i] = str_replace("\\", '', $titleTop[$i]);
			echo "$titleTop[$i]<br>";

			mysql_query("INSERT INTO songs (ID, Title) VALUES (\"$videoId[$i]\", \"$titleTop[$i]\")");
			// END
		}
	}else{
	echo "Sorry, you don't have administrator permission...";
}
}else{
	echo "Sorry, you don't have administrator permission...";
}

?>