#!/usr/bin/php

<?
/*
	Script to parse extracted google music playlist files and copy music to a directory.
	Needs tab delimited text file extracted from google music web interface using 
	Chrome table extractor extenstion: 
	https://chrome.google.com/webstore/detail/table-capture/iebpjdmgckacbodjpijphcplhebcmeop?hl=en

	Usage: ./google_playlist.php <playlist_file> <music_src_dir> <dest_dir>

	<playlist_file> is the extracted text file to be parsed
	<music_src_dir> is the top level of your music dir, where file matches can be found
	<dest_dir> is the directory where matches will be copied into

	CopyRight: Tony Forma <tforma@gmail.com>
	License: Apache
*/

$count = 0;
if (!is_file($argv[1]) || !is_dir($argv[2]) || !is_dir($argv[3])) {
	print "Usage: google_playlist.php <playlist_file> <music_src_dir> <dest_dir>";
	exit;
}
$dest = $argv[3];
$src = $argv[2];
	
foreach (file($argv[1]) as $line) {
	$output = array();
	$winner = null;
	$count++;
	$thing = explode("\t", $line);
	if (count($thing) < 2) {
		print "Error parsing playlist file: Could not split $line!\n";
		continue;
	}
	$song = escape($thing[0]);
	$song = trim($song);
	$song = substr($song, 0, 31);
	$song = preg_replace("/\\\s?$/", "", $song);

	//  a different version of the text to try
	$song2 = preg_replace("/Feat\./", "Feat_", $song);
	$song2 = preg_replace("/,/", "_", $song);

	$artist = $thing[2];
	exec("find $src -name \"*$song*\"", $output);
	if (count($output) == 0) {
		$output = exec("find $src -name \"*$song2*\"");
		if (strlen($output) == 0) {
			print "NO MATCH for $song - $artist!\n";
			exit;
		}
	}

	// narrow results down by artist
	foreach ($output as $canidate) {
		if (preg_match("/$artist/i", $canidate)) {
			$winner = $canidate;
		}
	}

	if ((!isset($winner)) && (count($output) > 1)) {
		print "MORE THAN one match for $song - $artist!\n";
		var_dump($output);
		continue;
	}
	$info = pathinfo($output[0]);
	if (!count($info) || empty($info['filename'])) {
		print "Failed to get info about file $output\n";
		exit;
	}
	$filename = $info['filename'] . "." . $info['extension'];
	if (is_file("$dest/$filename")) {
		print "$filename exists, skipping...\n";
	} else {
		print "copying $filename to $dest/$filename...\n";
		copy("$output", "$dest/$filename");
	}
	//print "($filename) - $output\n";
	//print $line . "\n";
}

function escape($string) {
	$string = str_replace("'", "\'", $string);
	$string = str_replace("[", "\[", $string);
	$string = str_replace("]", "\]", $string);
	$string = str_replace("(", "\(", $string);
	$string = str_replace(")", "\)", $string);
	$string = str_replace("&", "\&", $string);
	return $string;
}
