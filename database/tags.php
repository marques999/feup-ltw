<?
	function strip_tags_content($text, $tags = '') {
		preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
		$tags = array_unique($tags[1]);

		if (is_array($tags) && count($tags) > 0) {
			return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
		}

		return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
	}
?>