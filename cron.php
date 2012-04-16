<?php

include("spotify.php");
include("lastfm.php");

$lastfm = new Lastfm();
$spotify = new Spotify();

$tracks = $lastfm->getTrackNames();
$idstring = '';
foreach($tracks as $rank => $data) {
	$name = $data[0];
	$artist = $data[1];
	//You can set this rank value to have more or less tracks in your playlist
	if($rank <= 30) {
		$id = $spotify->getTrackID($name, $artist);
		$idstring = $idstring . ',' . $id;
	}
}

$idstring = substr($idstring, 1);
$idstring = str_replace(',,', ',', $idstring);
$idstring = eregi_replace(',$', '', $idstring);

//echo $idstring;

$fh = fopen("/location/of/textfile", "wb");
fwrite($fh, $idstring);
fclose($fh);

?>
