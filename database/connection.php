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
		$workingString = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
		$workingString = preg_replace('!\s+!', ' ', $workingString);
		return htmlspecialchars($workingString);
	}
?>