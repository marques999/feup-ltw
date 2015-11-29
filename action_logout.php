<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include('template/header.php');

	if (isset($_SESSION['username'])) {
		unset($_SESSION['username']);
	}

	if (isset($_SESSION['userid'])) {
		unset($_SESSION['userid']);
	}

	if (isset($_SERVER['HTTP_REFERER'])) {
		$refererUrl = $_SERVER['HTTP_REFERER'];
	}
	else {
		$refererUrl = 'index.php';
	}

	session_destroy();
	header("Refresh: 3; URL=$refererUrl");
?>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
<div class="column ink-alert block success">
<h4>Information</h4>
<p>You have successfully logged out and will be redirected shortly...</p>
</div>
</div>
<?
	include('template/footer.php');
?>