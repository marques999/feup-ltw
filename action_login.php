<?
	session_start();

	include_once('database/connection.php');
	include_once('database/users.php');

	$sessionId = validateLogin($_POST['username'], $_POST['password']);
	$msg = 0;

	if ($sessionId > 0) {
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['userid'] = $sessionId;
		header("Location: index.php");
	}
	else {
		$msg = 1;
		header("Location: login.php?error=$msg");
	}
?>