<?
	include_once('../database/action.php');
	include_once('../database/country.php');
	include_once('../database/salt.php');
	include_once('../database/users.php');

	if (isset($_POST['username'] && users_userExists($_POST['username'])) {
		$stmt = $db->prepare('DELTE FROM Users WHERE username = :username');
		$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
		$stmt->execute();
	}
?>