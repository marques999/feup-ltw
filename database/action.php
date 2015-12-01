<?
	$db = new PDO('sqlite:../events.db');
	function safe_getId($array, $id){
		if (!isset($array[$id])){
			return 0;
		}
		$id=intval($array[$id]);
		return $id<0?0:$id;
	}
?>