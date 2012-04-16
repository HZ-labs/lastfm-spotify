<?php
class Spotify {
	//define constants
	const SEARCH_URL = 'http://ws.spotify.com/search/1/';
	
	private function getResults($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


		$searchResults = curl_exec($ch);
		
		$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($httpstatus == '200') {
			return($searchResults);
		} else {
			return('error');
		}
	}

	public function createSearchURL($track, $artist) {
		$surl = self::SEARCH_URL . 'track?q=' . $this->searchSafe($track) . '+' . $this->searchSafe($artist);

		return $surl;
	}

	public function searchSafe($term) {
		// Replace "-" in regurlar search but leave it on "tag"-searches
		// such as "genre:brit-pop" or "label:deutsche-grammophon"
		$term = preg_replace("/(^[^a-z\:]+\-|[\_\(\)])/ui", " ", (trim($term)));

		// Replace multiple whitespaces with a single one
		$term = preg_replace("/\s{2,}/", " ", ($term));

		return urlencode((trim($term)));
	}

	public function getTrackID($track, $artist) {
		//create URL
		$url = $this->createSearchURL($track, $artist);
		//get the XML
		$xml = $this->getResults($url);
		//XML shizzle goes here
		if($xml == 'error') {
			//hmmm, handle error somehow
			$id = 'error';
		} else {
			//parse xml
			$parser = simplexml_load_string($xml);
			$idstring = $parser->track[0]["href"];
			$idarray = explode(':', $idstring);
			$id = $idarray[2];
		}
		
		return $id;
	}
}
?>
