<?php
	$urlSearch = "https://gdata.youtube.com/feeds/api/videos?q=am i a psycho";

	$sxml = simplexml_load_file($urlSearch);
	ob_start();
	var_dump($sxml);
	$videoInfo = ob_get_clean();

	$lpTitle = 0;
	for ($i = 0; $i < 3; $i++){ 
		$lastPositionTitle = strpos($videoInfo, '["title"]', $lpTitle);
		$lpTitle = $lastPositionTitle + 1;
	}

	$lpCode = 0;
	for ($i = 0; $i < 2; $i++){
		$lastPositionCode = strpos($videoInfo, "http://gdata.youtube.com/feeds/api/videos/", $lpCode);
		$lpCode = $lastPositionCode + 1;
	}

	for ($i = 0; $i < 10; $i++){
		$title[$i] = substr($videoInfo, $lastPositionTitle + 30, strpos($videoInfo, '"', $lastPositionTitle + 30) - (strpos($videoInfo, '"', $lastPositionTitle) + 1));
		$lastPositionTitle = strpos($videoInfo, '["title"]', $lpTitle);
		$lpTitle = $lastPositionTitle + 1;

		$code[$i] = substr($videoInfo, $lastPositionCode + 42, 11);
		$lastPositionCode = strpos($videoInfo, "http://gdata.youtube.com/feeds/api/videos/", $lpCode);
		$lpCode = $lastPositionCode + 1;

		echo "<script type='text/javascript'>alert('$code[$i]');</script>";
		$title[$i] = substr($title[$i], 0, -28);

		$thumbnail[$i] = "http://img.youtube.com/vi/$code[$i]/default.jpg";
	}
?>

<?php/* THIS SCRIPT FINDS VIDEO ID OF FIRST VIDEO THAT IS ON THE LIST OF SEARCH QUERY
$yt_search = $titleTop[$i];
			$yt_source = file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=1&order=relevance&q='.urlencode($yt_search).'&key=AIzaSyBcn4iHA6NhI5PA61gu--l0_nSPYSYnX24');
			$yt_decode = json_decode($yt_source, true);
			if ($yt_decode['pageInfo']['totalResults']>0) {
				if (strlen($yt_decode['items'][0]['id']['videoId'])>5) {
					$yt_videoid[$i] = trim($yt_decode['items'][0]['id']['videoId']);
						echo "$yt_videoid[$i]<br><br>";
				}
			}
*/?>
