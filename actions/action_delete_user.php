<?
	include_once('../database/action.php');
	include_once('../database/session.php');
	include_once('../database/users.php');

	if (isset($_POST['idUser']) && $loggedIn) {
		$userId = intval($_POST['idUser']);
		$userExists = users_idExists($userId);

		if ($userId > 0 && $userExists && $thisUser == $userId) {
			$stmt = $db->prepare('DELTE FROM Users WHERE idUser = :idUser');
			$stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);

			if ($stmt->execute() == false) {
				header("../database_error.php");
			}
		}
	}

	header("Location: ../index.php");
?>