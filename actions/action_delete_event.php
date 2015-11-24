<?
	include_once('../database/connection.php');
	include_once('../database/events.php');

	if (isset($_GET['id']) && isset($_SESSION['userid'])) {

		$thisEvent = events_listById($_GET['id']);
		$thisUser = $_SESSION['userid'];

		if (count($thisEvent) == 1) {
			$thisEvent = $thisEvent[0];
		}
		else {
			header("Location: index.php");
		}
	}

	include('../template/header.php');
?>
<div class="column-group">
	<div class="all-25"></div>
	<?if($thisUser!=$thisEvent['idUser']){?>
		<div class="all-50 ink-alert block error" role="alert">
			<h4>Forbidden</h4>
			<p>You don't have permission to access this page!</p>
		</div>
	<?}else{?>
		<div class="all-50 ink-alert block success" role="alert">
			<h4>Action completed successfully!</h4>
			<p>You have deleted your own event.</p>
		</div>
	<?}?>
</div>
<?
	global $db;
	$stmt = $db->prepare('DELTE FROM Events WHERE idUser = :idUser AND idEvent = :idEvent');
	$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_STR);
	$stmt->bindParam(':idUser', $thisEvent['idUser'], PDO::PARAM_STR);
	$stmt->execute();

	include('../template/footer.php');
?>