<?
	$db = new PDO('sqlite:events.db');
	function safe_getId($array, $id){
		if (!isset($array[$id])){
			return 0;
		}
		$newid=intval($array[$id]);
		return $newid<0?0:$newid;
	}
?>