<?php

	// PHP uses UTF-8 charset, session is starting
	header("content-type: text/html; charset=UTF-8"); 
	session_start();


	//	FUNCTIONS
	function unique_id($l = 32) {
		return substr(md5(uniqid(mt_rand(), true)), 0, $l);
	}

	$username = $_POST["username"];
	$password = $_POST["password"];

	if (isset($_POST["videoId"])){
		$videoId = $_POST["videoId"];
		$videoId = "watch.php?v=" . $videoId;
	}else{
		$videoId = "index.php";
	}

	if ($username && $password){ // If username and password are set it continues
			
		// Connecting to database and checking username from table
		$connect = mysql_connect("localhost", "root", "") or die("Couldn't connect!");
		mysql_select_db("b7_17103820_Alpha") or die("Couldn't find db!");
		$query = mysql_query("SELECT * FROM users WHERE username='$username'");
		$numrows = mysql_num_rows($query);

		// Takes username, password and salt from database
		if ($numrows != 0){
			while ($row = mysql_fetch_assoc($query)){
				$dbEmail = $row["email"];
				$dbPassword = $row["password"];
				$dbUsername = $row["username"];
				$dbSalt = $row["salt"];
			}

			// If username and crypted password user entered match username and crypted password from database SESSION loggedIn is started
			if ($username == $dbUsername && crypt($password, $dbSalt) == $dbPassword){
				$_SESSION["loggedIn"] = $username;
                
                header("Location:$videoId");
                die();
                
			}else{
				echo "Incorrect password";
                die();
			}
		}else{ // If can't find user in table
            echo "The user doesn't exist";
            die();
		}
	}else{
		echo "Fill in all the black cells";
	}

?>