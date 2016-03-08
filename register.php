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
	$email = $_POST["email"];
	$salt = unique_id();

	if (isset($_POST["videoId"])){
		$videoId = $_POST["videoId"];
		$videoId = "watch.php?v=" . $videoId;
	}else{
		$videoId = "index.php";
	}

	if ($username && $password && $email){ // If username, password and email are set it continues
			
		$link = mysqli_connect("localhost", "root", "", "b7_17103820_Alpha");
		
		// Check connection
		if($link === false){
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$cryptedPass = crypt($password, $salt);
		// Attempt insert query execution
		$sql = "INSERT INTO users (email, username, password, salt) VALUES ('$email', '$username', '$cryptedPass', '$salt')";
		if(mysqli_query($link, $sql)){
			$_SESSION["loggedIn"] = $username;
			header("Location:index");
			die();
		}else{
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
		}
 		// Close connection
		mysqli_close($link); 
	}else{
		echo "Fill in all the black cells";
	}
?>