<?php
class Lastfm {
	const xmlChartURL = 'http://ws.audioscrobbler.com/2.0/user/[username]/weeklytrackchart.xml';

	public $tracks = array();
	public $end;

	public function getXML() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::xmlChartURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec($ch);
		curl_close($ch);

		return $xml;
	}
	
	public function getTimeLimit() {
		$xml = $this->getXML();

		$parser = simplexml_load_string($xml);
		$end = $parser->attributes()->to;

		return $end;
	}

	public function getTrackNames() {
		$xml = $this->getXML();

		$parser = simplexml_load_string($xml);

		foreach($parser->track as $track) {
			$rank = (string) $track["rank"];
			$name = (string) $track->name;
			$artist = (string) $track->artist[0];

			$tracks[$rank] = array($name, $artist);
		}
		
		return $tracks;
	}
}
