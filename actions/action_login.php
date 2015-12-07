<?
	session_start();
	include_once('../database/action.php');
	include_once('../database/salt.php');
	include_once('../database/users.php');

	$sessionId = validateLogin($_POST['username'], $_POST['password']);

	if ($sessionId > 0) {
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['userid'] = $sessionId;
		header("Location: ../index.php");
	}
	else {
		header("Location: ../login.php?error=1");
	}
?>