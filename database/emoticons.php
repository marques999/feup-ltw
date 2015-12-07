<?
	$emoticons = array();
	$emoticons[] = array(':S','<img src="img/awww.png">');
	$emoticons[] = array(':@','<img src="img/angry.png">');
	$emoticons[] = array(':|','<img src="img/disheartened.png">');
	$emoticons[] = array('XD','<img src="img/ecstatic.png">');
	$emoticons[] = array(':D','<img src="img/great.png">');
	$emoticons[] = array(':x','<img src="img/mouthshut.png">');
	$emoticons[] = array(':)','<img src="img/nice.png">');
	$emoticons[] = array(':o','<img src="img/omg.png">');
	$emoticons[] = array(':(','<img src="img/sad.png">');
	$emoticons[] = array(':P','<img src="img/tongue.png">');

	function parseEmoticons($text) {
		global $emoticons;
		foreach ($emoticons as $emoticon) {
			$text = str_replace($emoticon[0],$emoticon[1],$text);
		}
		return $text;
	}
?>