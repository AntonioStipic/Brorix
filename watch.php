<?php

header("content-type: text/html; charset=UTF-8"); 
error_reporting(0);
session_start();

if (isset($_SESSION["loggedIn"])){
	$username = $_SESSION["loggedIn"];
	$loginReplaceText = "<img id=\"menuArrow\" onClick=\"clickedMenu();\" src=\"./img/menu_arrow_down.png\" width=\"2%\">Hello, $username!"; //<img src='./img/arrow_down.png' width='20%'>
}else{
	$loginReplaceText = '<span><a href="#login-box" class="login-window"></a><a onClick="clickedSignin();" class="login-window">Sign In</a> / <a onClick="clickedSignup();" class="login-window">Sign Up</a></span>';
}

$videoId = $_GET["v"];
$playlistId = $_GET["p"];
$playlistSong = $_GET["n"];

$placeholder = "Search for a song...";

if (isset($_COOKIE["lastSearch"])){
	$lastSearch = $_COOKIE["lastSearch"];
}else{
	$lastSearch = "";
}

/* require('youtube-dl.class');
try {
	// Instantly download a YouTube video as MP3 (using the default settings).
	new yt_downloader("http://www.youtube.com/watch?v=$videoId", TRUE, 'audio');
}
catch (Exception $e){
	die($e->getMessage());
} */

$playlistControls = "";
$isPlaylist = 0;
if ($playlistId != ""){
	$connect1 = mysql_connect("localhost", "root", "") or die("Couldn't connect!");
	mysql_query ("set character_set_client='utf8'"); 
 	mysql_query ("set character_set_results='utf8'"); 

	mysql_query ("set collation_connection='utf8_general_ci'"); 
	mysql_select_db("b7_17103820_Alpha") or die("Couldn't find db!");
					
	$query = mysql_query("SELECT * FROM playlists WHERE ID='$playlistId'");

	$fetchTable = array();

	$fetchTable = mysql_fetch_array($query);

	$songs = $fetchTable["songs"];

	$song = str_split($songs, 11);
	$song = array_reverse($song);

	$numberSongs = count($song);

	$playlistSong = $playlistSong % $numberSongs;
	$isPlaylist = 1;

	if ($videoId == ""){
		$videoId = $song[0];
		$playlistSong = 0;
	}

	if ($song[$playlistSong+1] == ""){
		$playlistControls = '<img onClick="javascript:previousSong();" src="./img/previous_icon.png" width="5%" class="songControls">';
	}else if ($song[$playlistSong-1] == ""){
		$playlistControls = '<img onClick="javascript:nextSong();" src="./img/next_icon.png" width="5%" class="songControls">';
	}else{
		$playlistControls = '<img onClick="javascript:nextSong();" src="./img/next_icon.png" width="5%" class="songControls"><img onClick="javascript:previousSong();" src="./img/previous_icon.png" width="5%" class="songControls">';
	}
}


function redirectTo($functionURL){ // CHECKS WHERE IT REDIRECTS
	$target = $functionURL;
	$urls = array(
		$functionURL
	);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	foreach($urls as $url){
		curl_setopt($ch, CURLOPT_URL, $url);
		$out = curl_exec($ch);

		// line endings is the wonkiest piece of this whole thing
		$out = str_replace("\r", "", $out);

		// only look at the headers
		$headers_end = strpos($out, "\n\n");
		if( $headers_end !== false ){ 
			$out = substr($out, 0, $headers_end);
		}

		$headers = explode("\n", $out);
		foreach($headers as $header){
			if( substr($header, 0, 10) == "Location: " ){ 
				$target = substr($header, 10);

				//echo "[$url] redirects to [$target]<br>";
				continue 2;
			}
		}

		//echo "[$url] does not redirect<br>";
	}

	return $target;
}




$kod = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=id%2Csnippet&id=$videoId&key=AIzaSyBcn4iHA6NhI5PA61gu--l0_nSPYSYnX24");
					
$json = json_decode($kod);
$title = $json->items[0]->snippet->title;

$title = str_replace("(", "", $title);
$title = str_replace(")", "", $title);
$title = str_replace("[", "", $title);
$title = str_replace("]", "", $title);
$title = str_replace("Audio", "", $title);
$title = str_replace("Official", "", $title);
$title = str_replace("Official Video", "", $title);
$title = str_replace("Music Video", "", $title);
$title = str_replace("Music", "", $title);
$title = str_replace("Video", "", $title);
$title = str_replace("Explicit", "", $title);
$title = str_replace(" And ", " & ", $title);

// URL za iframe za video
//$noviURL = "http://www.youtube.com/embed/$videoId?version=3&loop=1&playlist=$videoId&autoplay=1&showinfo=0&html5=1&controls=0";
$noviURL = "http://www.youtube.com/embed/$videoId?enablejsapi=1&version=3&origin=http://localhost/BrorixBootstrap/watch&loop=1&playlist=$videoId&autoplay=1&showinfo=0&html5=1&controls=1";

//////// Exceptions
$newTitle = strtolower($title);

$tmpTitle = "";
if ($newTitle == "wham! - last christmas"){
	$tmpTitle = "last christmas";
}
if ($videoId == "j9jbdgZidu8"){
	$tmpTitle = "Fairytale Of New York";
}
if ($videoId == "4v1zuIYNoFw"){
	$tmpTitle = "am i a psycho";
}



////////

if (strpos($newTitle, "ft.") > 0){
	$newTitle = substr($newTitle, 0, strpos($newTitle, "ft."));
}

if (strpos($newTitle, "feat.") > 0){
	$newTitle = substr($newTitle, 0, strpos($newTitle, "feat."));
}

$newTitle = preg_replace("/[^ \w]+/", "", $newTitle);

$newTitle = str_replace("-", " ", $newTitle);
$newTitle = str_replace("hd", " ", $newTitle);
$newTitle = str_replace("3d", " ", $newTitle);
$newTitle = str_replace("2d", " ", $newTitle);
$newTitle = str_replace("Alt.", " ", $newTitle);
$newTitle = str_replace("hq", " ", $newTitle);
$newTitle = str_replace("full length", " ", $newTitle);
$newTitle = str_replace("official video", " ", $newTitle);
$newTitle = str_replace("music video", " ", $newTitle);
$newTitle = str_replace("official single", " ", $newTitle);
$newTitle = str_replace("official music video", " ", $newTitle);
$newTitle = str_replace("metal cover by leo moracchioli", " ", $newTitle);
$newTitle = str_replace("original mix", " ", $newTitle);
$newTitle = str_replace("(", " ", $newTitle);
$newTitle = str_replace(")", " ", $newTitle);
$newTitle = str_replace("!", " ", $newTitle);
$newTitle = str_replace("[", " ", $newTitle);
$newTitle = str_replace("]", " ", $newTitle);
$newTitle = str_replace("feat.", " ", $newTitle);
$newTitle = str_replace("ft.", " ", $newTitle);
$newTitle = str_replace("pt.", " ", $newTitle);
$newTitle = str_replace(".", " ", $newTitle);
$newTitle = str_replace("&", " ", $newTitle);
$newTitle = str_replace("$", " ", $newTitle);
$newTitle = str_replace("#", " ", $newTitle);
$newTitle = str_replace("?", " ", $newTitle);
$newTitle = str_replace("=", " ", $newTitle);
$newTitle = str_replace("*", " ", $newTitle);
$newTitle = str_replace("/", " ", $newTitle);
$newTitle = str_replace("{", " ", $newTitle);
$newTitle = str_replace("}", " ", $newTitle);
$newTitle = str_replace("audio", " ", $newTitle);
$newTitle = str_replace("live", " ", $newTitle);
$newTitle = str_replace("acoustic", " ", $newTitle);
$newTitle = str_replace("version", " ", $newTitle);
$newTitle = str_replace("lyrics", " ", $newTitle);
$newTitle = str_replace("official", " ", $newTitle);
$newTitle = str_replace("video", " ", $newTitle);
$newTitle = str_replace("explicit", " ", $newTitle);
$newTitle = str_replace("track", " ", $newTitle);
$newTitle = str_replace('"', " ", $newTitle);
$newTitle = str_replace('0', " ", $newTitle);
$newTitle = str_replace('1', " ", $newTitle);
$newTitle = str_replace('2', " ", $newTitle);
$newTitle = str_replace('3', " ", $newTitle);
$newTitle = str_replace('4', " ", $newTitle);
$newTitle = str_replace('5', " ", $newTitle);
$newTitle = str_replace('6', " ", $newTitle);
$newTitle = str_replace('7', " ", $newTitle);
$newTitle = str_replace('8', " ", $newTitle);
$newTitle = str_replace('9', " ", $newTitle);

if ($tmpTitle != ""){
	$newTitle = $tmpTitle;
}


$newUrl = redirectTo("http://songmeanings.com/query/?type=songtitles&query=$newTitle");
$kod2 = file_get_contents($newUrl);
//echo "$kod2";
//echo $newUrl

if (strpos($kod2, "<div class=\"holder lyric-box\">") > 1){
	$beggining = strpos($kod2, '<div class="holder lyric-box">');
	$end = strpos($kod2, '<div style="min-height: 2');

	$lyrics = substr($kod2, $beggining, $end - $beggining);
}else{
	$beggining = strpos($kod2, '<a style="" class="" href="'); //27
	$end = strpos($kod2, '"', $beggining + 28);

	$newLink = substr($kod2, $beggining + 27, $end - $beggining);
	$newLink = substr($newLink, 0, strpos($newLink, '"'));

	$kod2 = file_get_contents($newLink);

	$beggining = strpos($kod2, '<div class="holder lyric-box">');
	$end = strpos($kod2, '<div style="min-height: 2');

	$lyrics = substr($kod2, $beggining, $end - $beggining);
}

if ($lyrics == ""){
	$lyrics = '<font size="3" color="red">Our magic 8 ball says to try again later.<br>Stupid magic 8 ball...</font><script>sliderClicked2(1000);</script><br><br><button type="button" class="btn btn-primary" id="findSearchEngine" onClick="findLyricsOn();">Find lyrics on Google</button>';
}
$lyrics = $lyrics . "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
// <div class="h2_songtitle">

$_SESSION["videoId"] = $videoId;

if (isset($_SESSION["loggedIn"])){

	$link = mysql_connect("localhost", "root", "", "b7_17103820_Alpha");
	mysql_select_db( 'b7_17103820_Alpha' );

	$getit = mysql_query("SELECT * FROM users WHERE username = '$username'", $link) or die(mysql_error());
	$row = mysql_fetch_array($getit);
	$history = $row["history"];
	$favorites = $row["favorites"];

	if (strpos($favorites, $videoId) !== false){
		$favoriteReplaceText = '<button type="button" class="btn btn-danger iframeButton" onClick="removeFromFavorites();"><span class="glyphicon glyphicon-heart-empty"></span> Remove from favorites</button>';
	}else{
		$favoriteReplaceText = '<button type="button" class="btn btn-danger iframeButton" onClick="addToFavorites();"><span class="glyphicon glyphicon-heart"></span> Add to favorites</button>';
	}

	$playlistReplaceText = '<input type="submit" class="btn btn-warning iframeButton" id="addToPlaylist" value="Add to playlist" onClick="addToPlaylist();">';

	if (substr($history, -11) != $videoId){
		$history = $history . $videoId;
	}

	$sql = "UPDATE users SET history='$history' WHERE username='$username'";

	if (mysql_query($sql) === TRUE){
		//echo "Record updated successfully";	
	} else {
		//echo "Error updating record: " . $link->error;
	}

	// Close connection
	mysql_close($link);
}


?><!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Latest compiled and minified Boostrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="./style/index_style.css">
		<link rel="stylesheet" type="text/css" href="./style/watch_style.css">
		<link rel="shortcut icon" href="./img/brorix_icon.png">

		<meta charset="UTF-8">

		<title><?php echo "$title"; ?> - Brorix</title>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://api.convert2mp3.cc/api.js"></script>
		<script src="http://www.youtube.com/player_api"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			$('a.login-window').click(function(){
		
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
			$('a.close, #mask').live('click', function(){ 
				$('#mask , .login-popup').fadeOut(300 , function(){
					$('#mask').remove();  
				}); 
			return false;
			});
		});
		</script>
		<script type="text/javascript">
		var state01 = 0;
		function onHover(){
			if (state01 == 0){
				$("#slider").attr('src', './img/slider_down_white.png');
			}else{
				$("#slider").attr('src', './img/slider_up_white.png');
			}
		}
		function offHover(){
			if (state01 == 0){
				$("#slider").attr('src', './img/slider_down_black.png');
			}else{
				$("#slider").attr('src', './img/slider_up_black.png');
			}
		}

		var state02 = 0;
		function onHover2(){
			if (state02 == 0){
				$("#slider2").attr('src', './img/slider_right_white.png');
			}else{
				$("#slider2").attr('src', './img/slider_left_white.png');
			}
		}
		function offHover2(){
			if (state02 == 0){
				$("#slider2").attr('src', './img/slider_right_black.png');
			}else{
				$("#slider2").attr('src', './img/slider_left_black.png');
			}
		}
		</script>
	</head>
	<body>
		<script type="text/javascript">
			$(window).load(function(){
				// When the page has loaded
				sliderClicked(0);
				sliderClicked(0);
				sliderClicked2(0);
				
			});

			var state = 0;
			function sliderClicked(first){
				if (state == 0){
					$( "#wrapperVideo" ).animate({
						top: "95%"
					}, first, function(){
							// Animation complete.
							document.getElementById("slider").src = "./img/slider_up_black.png";
							state01 = 1;
					});
					state = 1;
				}else{
					$( "#wrapperVideo" ).animate({
						top: "28%"
					}, first, function(){
							// Animation complete.
							document.getElementById("slider").src = "./img/slider_down_black.png";
							state01 = 0;
					});
					state = 0;
				}
				return $.Deferred().resolve();
			}

			var state1 = 0;
			function sliderClicked2(first){
				if (state1 == 0){
					$( "#wrapperLyrics" ).animate({
						right: "0%"
					}, first, function(){
							// Animation complete.
							document.getElementById("slider2").src = "./img/slider_right_black.png";
							state02 = 0;
					});
					state1 = 1;
				}else{
					$( "#wrapperLyrics" ).animate({
						right: "-29%"
					}, first, function(){
							// Animation complete.
							document.getElementById("slider2").src = "./img/slider_left_black.png";
							state02 = 1;
					});
					state1 = 0;
				}
				return $.Deferred().resolve();
			}
			</script>

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
			<form action="javascript:void(0);" onSubmit="submitForm()">
				<span class="searchBar">
					<input type="text" name="q" id="searchInput" value="<?php echo $lastSearch; ?>" placeholder="<?php echo $placeholder; ?>">
				</span>
				<span class="searchBar">
					<button><img src="./img/search_icon.png" id="searchImg"></button>
				</span>
				<span class="searchBar" id="xImg1">
					<img src="./img/x.png" id="xImg2" onClick="javascript:deleteSearch();">
				</span>
				<hr>
			</form>
		</div>

		<div id="searchResultsDiv"></div>


		<div class="videoWrapper" id="wrapperVideo">
			<div>
				<img src="./img/slider_down_black.png" width="40em" id="slider" onmouseover="onHover();" onmouseout="offHover();" onclick="sliderClicked(800)">
				<h4><?php echo $title; ?></h4>
					
			</div>

			<div id="videoBox" width="96%" height="50%"></div>

			<h4><a href='http://www.youtubeinmp3.com/fetch/?video=http://www.youtube.com/watch?v=<?php echo $videoId; ?>' style='text-decoration:none;color:#03a730;'>
					<input type="submit" class="btn btn-info" id="downloadButton" value="Download MP3">	
				</a>
			</h4>
			<h4 id="playlistButton">
				<?php echo "$playlistReplaceText"; ?>
			</h4>
			<h4 id="favoriteButton">
				<?php echo "$favoriteReplaceText"; ?>
			</h4>
			
			<?php echo "$playlistControls"; ?>
			

		</div>

		<div class="lyricsWrapper" id="wrapperLyrics">
			<img src="./img/slider_right_black.png" width="40em" id="slider2" onmouseover="onHover2();" onmouseout="offHover2();" onclick="sliderClicked2(800)">
			<h5 id="lyricsHeader"><?php echo $lyrics; ?></h5>
		</div>

		<div id="addToPlaylistDiv">
			<a href="javascript:closePlaylists();"><img src="./img/x.png" width="6%" id="xButtonPlaylists"></a>
				<?php

					$connect = mysql_connect("localhost", "root", "") or die("Couldn't connect!");
					mysql_query ("set character_set_client='utf8'"); 
 					mysql_query ("set character_set_results='utf8'"); 

					mysql_query ("set collation_connection='utf8_general_ci'"); 
					mysql_select_db("b7_17103820_Alpha") or die("Couldn't find db!");
					
					$query = mysql_query("SELECT * FROM users WHERE username='$username'");

					$fetchTable = array();

					$fetchTable = mysql_fetch_array($query);

					$playlists = $fetchTable["playlists"];

					$playlist = str_split($playlists, 11);
					$playlist = array_reverse($playlist);

					$numberPlaylist = count($playlist);

					if($numberPlaylist > 0 && $playlist[0] != ""){
						for ($i = 0; $i < $numberPlaylist; $i++){
							$query2 = mysql_query("SELECT * FROM playlists WHERE ID='$playlist[$i]'");
							$fetchTable2 = mysql_fetch_array($query2);
							$name[$i] = $fetchTable2["name"];
							echo "<a href=\"javascript:addToPlaylist2('$videoId', '$playlist[$i]')\">
	<div id=\"playlist[$i]\" class=\"songList\">
		<h4><b>$name[$i]</b></h4>
	</div>
</a>";
}
					}else{
						echo "<h4 id='h42'>You haven't created any playlists...</h4>";
					}?>

		</div>



		<div id="login-box" class="login-popup"></div>
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

		function convert2mp3(){
			document.getElementById('downloadMP3id').contentWindow.startConversion();
		}

		function eventFire(el, etype){
			if (el.fireEvent){
				el.fireEvent('on' + etype);
			} else {
				var evObj = document.createEvent('Events');
				evObj.initEvent(etype, true, false);
				el.dispatchEvent(evObj);
			}
		}

		function clickedSignin(){
		
			var div = document.getElementById('login-box');

			div.innerHTML = '<a href="#" class="close"><img src="./img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a> <form method="POST" class="signin" action="login"> <fieldset class="textbox"> <label class="username"> <span>Username</span> <input id="username" name="username" value="" type="text" autocomplete="on" placeholder="Username"> </label> <label class="password"> <span>Password</span> <input id="password" name="password" value="" type="password" placeholder="Password"> </label> <input type="submit" class="submit button" value="Sign in"> <p> <a class="forgot" href="#">Forgot your password?</a> </p><input hidden type="text" name="videoId" value="<?php echo $videoId; ?>"> </fieldset> </form>';

			eventFire($("a.login-window").get(0), 'click');
		}

		function clickedSignup(){
		
			var div = document.getElementById('login-box');

			div.innerHTML = '<a href="#" class="close"><img src="./img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a> <form method="POST" class="signin" action="register"> <fieldset class="textbox"> <label class="email"> <span>Email</span> <input id="email" name="email" value="" type="text" autocomplete="on" placeholder="Email"> </label><label class="username"> <span>Username</span> <input id="username" name="username" value="" type="text" autocomplete="on" placeholder="Username"> </label> <label class="password"> <span>Password</span> <input id="password" name="password" value="" type="password" placeholder="Password"> </label> <input type="submit" class="submit button" value="Sign up"> <input hidden type="text" name="videoId" value="<?php echo $videoId; ?>"> </fieldset> </form>';

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
		<script type="text/javascript">
		function submitForm(){
			if (state1 == 1){
				sliderClicked2(800);
			}
			if (state == 0){
				sliderClicked(800);
			}
			var http = new XMLHttpRequest();
			http.open("POST", "searchWatch", true);
			http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			var params = "q=" + document.getElementById("searchInput").value; // probably use document.getElementById(...).value
			http.send(params);
			http.onload = function(){
				document.getElementById("searchResultsDiv").innerHTML = http.responseText;
				document.getElementById("wrapperLyrics").style.top = "28.2%";
				document.getElementById("xImg1").style.display = "inline";
			}
		}

		function deleteSearch(){
			document.getElementById("searchResultsDiv").innerHTML = "";
			document.getElementById("xImg1").style.display = "none";
			if (state1 == 0){
				sliderClicked2(800);
			}
			if (state == 1){
				sliderClicked(800);
			}
		}

		function findLyricsOn(){
			var url = "http://www.google.com/search?q=<?php echo $newTitle; ?>%20lyrics";
			OpenInNewTab(url);
		}

		function OpenInNewTab(url){
			var win = window.open(url, '_blank');
			win.focus();
		}

		function addToPlaylist(){

			$("#addToPlaylistDiv").show(800);

			if (state1 == 1){
				sliderClicked2(800);
			}
			if (state == 0){
				sliderClicked(800);
			}
		}

		function addToFavorites(){
			var videoIdJS = "<?php echo $videoId; ?>";

			var http2 = new XMLHttpRequest();
			http2.open("POST", "./script/addToFavorites.php", true);
			http2.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			var params2 = "v=" + videoIdJS; // probably use document.getElementById(...).value
			http2.send(params2);

			document.getElementById("favoriteButton").innerHTML = '<button type="button" class="btn btn-danger iframeButton" onClick="removeFromFavorites();"><span class="glyphicon glyphicon-heart-empty"></span> Remove from favorites</button>';
		}

		function removeFromFavorites(){
			var videoIdJS = "<?php echo $videoId; ?>";

			var http2 = new XMLHttpRequest();
			http2.open("POST", "removeFromFavorites", true);
			http2.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			var params2 = "v=" + videoIdJS; // probably use document.getElementById(...).value
			http2.send(params2);

			document.getElementById("favoriteButton").innerHTML = '<button type="button" class="btn btn-danger iframeButton" onClick="addToFavorites();"><span class="glyphicon glyphicon-heart"></span> Add to favorites</button>';
		}

		function addToPlaylist2(videoId1, playlistId1){
			var http3 = new XMLHttpRequest();
			http3.open("POST", "./script/addToPlaylist", true);
			http3.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			var params3 = "playlistId=" + playlistId1 + "&videoId=" + videoId1; // probably use document.getElementById(...).value
			http3.send(params3);

			$("#addToPlaylistDiv").hide(800);

			if (state1 == 0){
				sliderClicked2(800);
			}
			if (state == 1){
				sliderClicked(800);
			}
		}

		function post(path, params, method){
			method = method || "post"; // Set method to post by default if not specified.

			// The rest of this code assumes you are not using a library.
			// It can be made less wordy if you use one.
			var form = document.createElement("form");
			form.setAttribute("method", method);
			form.setAttribute("action", path);

			for(var key in params){
				if(params.hasOwnProperty(key)){
					var hiddenField = document.createElement("input");
					hiddenField.setAttribute("type", "hidden");
					hiddenField.setAttribute("name", key);
					hiddenField.setAttribute("value", params[key]);

					form.appendChild(hiddenField);
				}
			}

			document.body.appendChild(form);
			form.submit();
		}

		function closePlaylists(){
			$("#addToPlaylistDiv").hide(800);

			if (state1 == 0){
				sliderClicked2(800);
			}
			if (state == 1){
				sliderClicked(800);
			}
		}

		</script>
		 <script>
		
		// create youtube player
		var isPlaylistJS = "<?php echo $isPlaylist; ?>";
		var player;
		function onYouTubePlayerAPIReady(){
			player = new YT.Player('videoBox', {
				height: '50%',
				width: '95%',
				videoId: '<?php echo $videoId; ?>',
				playerVars: { 
					'autoplay': 0,
					'controls': 1, 
					'rel' : 0,
					'showinfo' : 0
     			},
				events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerStateChange
				}
			});
		}

		// autoplay video
		function onPlayerReady(event){
			event.target.playVideo();
		}

		// when video ends
		function onPlayerStateChange(event){      
			if(event.data === 0){
				if (isPlaylistJS == "1"){
					var nextId = "";
					window.location.href = "http://localhost/BrorixBootstrap/watch?v=<?php echo $song[$playlistSong+1]; ?>&p=<?php echo $playlistId; ?>&n=<?php echo $playlistSong+1; ?>";
				}
				//alert('done');
			}
		}

		</script>
		<script type="text/javascript">
		var playlistId = "<?php echo $playlistId; ?>";
		function nextSong(){
			var nextId = "<?php echo $song[$playlistSong+1]; ?>";
			var songNum = <?php echo $playlistSong; ?> + 1;

			if (nextId != ""){
				window.location.href = "http://localhost/BrorixBootstrap/watch?v=" + nextId + "&p=" + playlistId + "&n=" + songNum;
			}
		}
		function previousSong(){
			var previousId = "<?php echo $song[$playlistSong-1]; ?>";
			var songNum = <?php echo $playlistSong; ?> - 1;

			if (previousId != ""){
				window.location.href = "http://localhost/BrorixBootstrap/watch?v=" + previousId + "&p=" + playlistId + "&n=" + songNum;
			}
		}
		</script>

	</body>