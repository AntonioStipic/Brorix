<?php




?>

<html>
	<head>
		<title>Looking for Top videos...</title>

		<meta charset="UTF-8">
	</head>

	<body>
	<?php // ONLY REASON WE LOCK UPDATING SONGS ONLY TO ADMINS IS SO ORDINARY USERS CAN'T DDOS ATTACK WEBSITE AND DATABASE

		// Starting session so we can check is user admin or not
		session_start();
		// Check if admin is logged in, if true songs will update, else prints access denied
		if (isset($_SESSION["username"])) {

			// admin is logged in so we print "Welcome, //admin-name//!"
			echo "Welcome, " . $_SESSION["username"] . "!<br><br>";

			// START connect to database
			$connect = mysql_connect("sql105.byethost7.com", "b7_17103820", "Spaldin172839417528639") or die("Couldn't connect!");
			mysql_set_charset('utf8', $connect);
			mysql_select_db("b7_17103820_Alpha") or die("Couldn't find db!");
			// END

			// START find video IDs of first 50 songs on this playlist which shows the most popular songs today/this week and saves to array videoID
			$kod = file_get_contents("https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=PLFgquLnL59alCl_2TQvOiD5Vgm1hCaGSI&key=AIzaSyBcn4iHA6NhI5PA61gu--l0_nSPYSYnX24");
			$oldPos = 0;

			for ($i = 0; $i < 50; $i++) { 
				$pos[$i] = strpos($kod, 'videoId": "', $oldPos);
				$videoId[$i] = substr($kod, $pos[$i] + 11, 11);
				$oldPos = $pos[$i] + 1;
			}
			// END

			// START clears whole table topSongs
			mysql_query('TRUNCATE TABLE topSongs;');

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
				echo "$titleTop[$i]$i<br>";

				mysql_query("INSERT INTO topSongs (ID, Title, numberID) VALUES (\"$i+1\", \"$videoId[$i]\", \"$titleTop[$i]\")") or die(mysql_error());
				// END
			}
			echo "<h2>SUCCESSFULLY UPDATED SONGS TO DATABASE</h2>";
		}else{ // User is not admin so user doesn't have control over updating songs
			echo "	<h1>Access denied</h1>\n		<h4>You are not logged in as administrator!</h4>\n";
		}

	?>
	</body>
</html>
	