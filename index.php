<?php
require_once('simple_html_dom.php');
require './vendor/autoload.php';
?>

<html>
<head>
	<title>Web Crawler | Fadhil Amadan | 160414063</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>

<form class="form-wrapper" action="" method="POST">
	<center>
		<h1>Web Crawler</h1>
		<h4>Fadhil Amadan | 160414063</h4>
	</center>
    <input type="text" name="keyword" id="search" placeholder="Search for..." required>
    <input type="submit" value="go" id="submit" name="submit">
</form>

<?php 
if(isset($_POST["keyword"])) {

	//Load URL
	$html = file_get_html('https://twitter.com/search?f=tweets&vertical=news&q='.$_POST["keyword"]);

	//Array
	$daftar_tweet=[];

	//Cari 5 Tweet Teratas
	$i = 0;
	foreach ($html->find('div[class="content"]') as $tweet) {
		if ($i > 5) break;
		else {
			$userTweet = $tweet->find('span[class="username u-dir"]',0)->innertext;
			$textTweet = $tweet->find('p[class="TweetTextSize  js-tweet-text tweet-text"]',0)->innertext;
			
				/*START - Stemmer and Stop Word*/
			    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
			    $stemmer = $stemmerFactory->createStemmer();

			    $stopwordFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
			    $stopword = $stopwordFactory->createStopWordRemover();

			    $sentence = $textTweet;
			    $output = $stemmer->stem($sentence);

			    $output2 = $stopword->remove($output);
			    /*END - Stemmer and Stop Word */

			$daftar_tweet[] = [
				'<strong>User</strong>' => $userTweet,
				'<strong>Tweet Asli</strong>' => $textTweet,
				'<strong>Tweet Stem</strong>' => $output2];
		}
		$i++;
	}

	//Print
	foreach ($daftar_tweet as $key => $item) {
		foreach ($item as $index => $content) {
			echo $index . " : " . $content . "<br>";
		}
		echo "<br><br>";
	}
}
?> 

</body>  
</html>  