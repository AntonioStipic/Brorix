<?php

header("content-type: text/html; charset=UTF-8"); 
session_start();

$_SESSION["videoId"] = "";

if (isset($_SESSION["loggedIn"])){
	$username = $_SESSION["loggedIn"];
	$loginReplaceText = "<img id=\"menuArrow\" onClick=\"clickedMenu();\" src=\"./img/menu_arrow_down.png\" width=\"2%\">Hello, $username!"; //<img src='./img/arrow_down.png' width='20%'>
}else{
	$loginReplaceText = '<span><a href="#login-box" class="login-window"></a><a onClick="clickedSignin();" class="login-window">Sign In</a> / <a onClick="clickedSignup();" class="login-window">Sign Up</a></span>';
}

?><!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Latest compiled and minified Boostrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="./style/index_style.css">
		<link rel="stylesheet" type="text/css" href="./style/index2_style.css">
		<link rel="shortcut icon" href="./img/brorix_icon.png">

		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>

		<meta charset="utf-8">

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			$('a.login-window').click(function() {
		
				// Getting the variable's value from a link 
				var loginBox = $(this).attr('href');

				//Fade in the Popup and add close button
				$(loginBox).fadeIn(300);
		
				//Set the center alignment padding + border
				var popMargTop = ($(loginBox).height() + 24) / 2; 
				var popMargLeft = ($(loginBox).width() + 24) / 2; 
				
				$(loginBox).css({ 
					'margin-top' : -popMargTop,
					'margin-left' : -popMargLeft
				});
		
				// Add the mask to body
				$('body').append('<div id="mask"></div>');
				$('#mask').fadeIn(300);
			
				return false;
			});
	
			// When clicking on the button close or the mask layer the popup closed
			$('a.close, #mask').live('click', function() { 
				$('#mask , .login-popup').fadeOut(300 , function() {
					$('#mask').remove();  
				}); 
			return false;
			});
		});
		</script>
		<title>Brorix</title>
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
					<input type="text" name="q" id="searchInput" placeholder="Search for a song...">
				</span>
				<span class="searchBar">
					<button><img src="./img/search_icon.png" id="searchImg"></button>
				</span>
			</form>
			<hr>
			<div id="firstDiv" class="part1">
				<img src="./img/notes02.png" width="20%">
				<h1 float="right">COOPERATION, MUSIC AND LYRICS</h1>
				<h2 float="right">Brorix makes listening music even easier</h2>
			</div>
			<div id="emptySpace"></div><hr>
			<div id="secondDiv" class="part2">
				<h1>Listen, view and create playlists.<br>Learn lyrics immediately.<br>Enjoy the music on repeat.</h1>
				<img src="./img/tape01.png" width="25%">
			</div>
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