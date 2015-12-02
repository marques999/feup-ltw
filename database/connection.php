<?
	$db = new PDO('sqlite:events.db');
	function safe_getId($array, $id){
		if (!isset($array[$id])){
			return 0;
		}
		$newid=intval($array[$id]);
		return $newid<0?0:$newid;
	}
	function safe_redirect($defaultUrl){
		$refererUrl = $defaultUrl;
		if (isset($_SERVER['HTTP_REFERER'])) {
			$refererUrl = $_SERVER['HTTP_REFERER'];
		}
		header("Location: $refererUrl");
	}
	function safe_check($array, $id){
		return isset($array[$id]) && !empty($array[$id]);
	}
	function safe_trim($text, $tags = '') {
		preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
		$tags = array_unique($tags[1]);

		if (is_array($tags) && count($tags) > 0) {
			return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
		}

		$workingString = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
		return htmlspecialchars(trim($workingString));
	}
?>