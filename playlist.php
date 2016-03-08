<?php

header("content-type: text/html; charset=UTF-8"); 
session_start();
error_reporting(0);

$_SESSION["videoId"] = "";

$playlistId = $_GET["v"];

if (isset($_SESSION["loggedIn"])){
	$username = $_SESSION["loggedIn"];
	$loginReplaceText = "<img id=\"menuArrow\" onClick=\"clickedMenu();\" src=\"./img/menu_arrow_down.png\" width=\"2%\">Hello, $username!"; //<img src='./img/arrow_down.png' width='20%'>
}else{
	$loginReplaceText = '<span><a href="#login-box" class="login-window"></a><a onClick="clickedSignin();" class="login-window">Sign In</a> / <a onClick="clickedSignup();" class="login-window">Sign Up</a></span>';
}

$connect = mysql_connect("localhost", "root", "") or die("Couldn't connect!");
mysql_query ("set character_set_client='utf8'"); 
mysql_query ("set character_set_results='utf8'"); 

mysql_query ("set collation_connection='utf8_general_ci'"); 
mysql_select_db("b7_17103820_Alpha") or die("Couldn't find db!");
					
$query = mysql_query("SELECT * FROM playlists WHERE ID='$playlistId'");

$fetchTable = array();

$fetchTable = mysql_fetch_array($query);

$songs = $fetchTable["songs"];
$playlistTitle = $fetchTable["name"];



?><!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Latest compiled and minified Boostrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="./style/index_style.css">
		<link rel="stylesheet" type="text/css" href="./style/search_style.css">
		<link rel="stylesheet" type="text/css" href="./style/favorites_style.css">
		<link rel="shortcut icon" href="./img/brorix_icon.png">

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
		<title>Brorix - Playlist</title>

		<style type="text/css">
		#goBack_a{
			float: left;
			margin-left: 3%;
		}
		</style>
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
			<a href="playlists" class="btn btn-success" id="goBack_a"><span class="
glyphicon glyphicon-arrow-left"></span> Go back</a>
		</div>



		<div id="topSongsList">
			<h2 class='moveToRight'>Songs in playlist "<?php echo $playlistTitle; ?>":</h2>
			<?php

					$song = str_split($songs, 11);
					$song = array_reverse($song);

					$numberSongs = count($song);

					function youtube_title($ids) {
						// $id = 'YOUTUBE_ID';
						// returns a single line of JSON that contains the video title. Not a giant request.
						$videoTitle = file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=".$ids."&key=AIzaSyBcn4iHA6NhI5PA61gu--l0_nSPYSYnX24&fields=items(id,snippet(title),statistics)&part=snippet,statistics");
						// despite @ suppress, it will be false if it fails
						if ($videoTitle){
							$json = json_decode($videoTitle, true);
							return $json['items'][0]['snippet']['title'];
						}else{
							return false;
						}
					}

					for ($i = 0; $i < $numberSongs; $i++){
						$title[$i] = youtube_title($song[$i]);
					}
					if($numberSongs > 0 && $title[0] != ""){
						for ($i = 0; $i < $numberSongs; $i++) { 
							echo "<a href=\"watch.php?v=$song[$i]&p=$playlistId&n=$i\">
	<div id=\"song[$i]\" class=\"songList\">
		<img src=\"http://img.youtube.com/vi/$song[$i]/0.jpg\">
		<span><b>$title[$i]</b></span>
		<a href=\"#\" onClick=\"removeFavorite('$song[$i]', '$playlistId');\"><img alt=\"Remove from favorites\" src=\"./img/x.png\" id=\"removeFavoriteImg\"></a>
	</div>
</a>";
						}
					}else{
						echo "<h3 class='moveToRight'>You haven't added any songs to this playlist yet...</h3>";
					}?>
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

		function removeFavorite(videoId, playlistId){
			var http = new XMLHttpRequest();
			http.open("POST", "removeFromPlaylist", true);
			http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			var params = "videoId=" + videoId + "&playlistId=" + playlistId; // probably use document.getElementById(...).value
			http.send(params);
			http.onload = function() {
				alert(http.responseText);
			}

			location.reload();
		}

		</script>
	</body>
</html>