<?
	include_once('../database/connection.php');
	include_once('../database/events.php');

	if (isset($_GET['id']) && isset($_SESSION['userid'])) {
		$thisEvent = events_listById($_GET['id']);
		$thisUser = $_SESSION['userid'];

		if (count($thisEvent) > 0) {
			global $db;
			$stmt = $db->prepare('DELTE FROM Events WHERE idUser = :idUser AND idEvent = :idEvent');
			$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_STR);
			$stmt->bindParam(':idUser', $thisEvent[0]['idUser'], PDO::PARAM_STR);
			$stmt->execute();
		}
		else {
			header("Location: index.php");
		}
	}
?>