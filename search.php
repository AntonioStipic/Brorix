<?php

header("content-type: text/html; charset=UTF-8"); 
error_reporting(0);
session_start();

$_SESSION["videoId"] = "";

if (isset($_SESSION["loggedIn"])){
	$username = $_SESSION["loggedIn"];
	$loginReplaceText = "<img id=\"menuArrow\" onClick=\"clickedMenu();\" src=\"./img/menu_arrow_down.png\" width=\"2%\">Hello, $username!"; //<img src='./img/arrow_down.png' width='20%'>
}else{
	$loginReplaceText = '<span><a href="#login-box" class="login-window"></a><a onClick="clickedSignin();" class="login-window">Sign In</a> / <a onClick="clickedSignup();" class="login-window">Sign Up</a></span>';
}

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
}else{
	header("Location: index");
	die();
}?><!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Latest compiled and minified Boostrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="./style/index_style.css">
		<link rel="stylesheet" type="text/css" href="./style/search_style.css">
		<link rel="shortcut icon" href="./img/brorix_icon.png">

		<meta charset="utf-8">

		<title>Brorix - Search</title>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<a href="index"><img src="./img/BRORIX_logo.png" id="logoImg"></a>

				<a href="index"><p>Home</p></a>
				<a href="hot"><p>What's hot?</p></a>
				<a href="about"><p>About us</p></a>

				<div id="login">
					<?php echo $loginReplaceText; ?>
				</div>
			</div>
		</div>

		<div id="contents">
			<form action="search" method="GET">
				<span class="searchBar">
					<input type="text" name="q" id="searchInput" value="<?php echo $tmpQ; ?>">
				</span>
				<span class="searchBar">
					<button><img src="./img/search_icon.png" id="searchImg"></button>
				</span>
			</form>

			<hr>
		</div>

		<div id="login-box" class="login-popup">
			<a href="#" class="close"><img src="./img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
			<form method="POST" class="signin" action="login">
				<fieldset class="textbox">
				<label class="username">
				<span>Username</span>
				<input id="username" name="username" value="" type="text" autocomplete="on" placeholder="Username">
				</label>
				
				<label class="password">
				<span>Password</span>
				<input id="password" name="password" value="" type="password" placeholder="Password">
				</label>
				
				<input type="submit" class="submit button" value="Sign in">
				
				<p>
				<a class="forgot" href="#">Forgot your password?</a>
				</p>
				
				</fieldset>
			</form>
		</div>

		<div id="list">
			<?php
			for ($i = 0; $i < 25; $i++){
				echo "<a href=\"watch?v=$videoId[$i]\"><div id=\"song[$i]\" class=\"songList\">
					<img src=\"$thumbnail[$i]\">
					<span><b>$title[$i]</b></span>
				</div></a>
				";
			}

			?>
		</div>

		<div id="menu">
			<form action="logout">
				<input type="submit" class="btn btn-warning" id="logoutButton" value="Log out">
			</form>
			<br>
			<form action="history">
				<input type="submit" class="btn btn-success" id="historyButton" value="History">
			</form>
			<br>
			<form action="favorites">
				<input type="submit" class="btn btn-danger" id="favoritesButton" value="Favorites">
			</form>
			<br>
			<form action="playlists">
				<input type="submit" class="btn btn-info" id="playlistsButton" value="Playlists">
			</form>
		</div>

		<script type="text/javascript">

		function eventFire(el, etype){
			if (el.fireEvent) {
				el.fireEvent('on' + etype);
			} else {
				var evObj = document.createEvent('Events');
				evObj.initEvent(etype, true, false);
				el.dispatchEvent(evObj);
			}
		}

		function clickedSignin(){
		
			var div = document.getElementById('login-box');

			div.innerHTML = '<a href="#" class="close"><img src="./img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a> <form method="POST" class="signin" action="login"> <fieldset class="textbox"> <label class="username"> <span>Username</span> <input id="username" name="username" value="" type="text" autocomplete="on" placeholder="Username"> </label> <label class="password"> <span>Password</span> <input id="password" name="password" value="" type="password" placeholder="Password"> </label> <input type="submit" class="submit button" value="Sign in"> <p> <a class="forgot" href="#">Forgot your password?</a> </p> </fieldset> </form>';

			eventFire($("a.login-window").get(0), 'click');
		}

		function clickedSignup(){
		
			var div = document.getElementById('login-box');

			div.innerHTML = '<a href="#" class="close"><img src="./img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a> <form method="POST" class="signin" action="register"> <fieldset class="textbox"> <label class="email"> <span>Email</span> <input id="email" name="email" value="" type="text" autocomplete="on" placeholder="Email"> </label><label class="username"> <span>Username</span> <input id="username" name="username" value="" type="text" autocomplete="on" placeholder="Username"> </label> <label class="password"> <span>Password</span> <input id="password" name="password" value="" type="password" placeholder="Password"> </label> <input type="submit" class="submit button" value="Sign up"> </fieldset> </form>';

			eventFire($("a.login-window").get(0), 'click');
		}

		menuState = 0;
		function clickedMenu(){

			if (menuState == 0){
				document.getElementById("menuArrow").src = "./img/menu_arrow_up.png";
				document.getElementById("menu").style.display = "inline";
				menuState = 1;
			}else{
				document.getElementById("menuArrow").src = "./img/menu_arrow_down.png";
				document.getElementById("menu").style.display = "none";
				menuState = 0;
			}
		}

		</script>
	</body>
</html>