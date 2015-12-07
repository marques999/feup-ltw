<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');

	if (safe_check($_GET, 'id')) {
		$serverRedirect = safe_getId($_GET, 'id');
		$serverReferer = "invite_users.php?id=$serverRedirect";
	}
	else {
		$serverReferer = safe_check($_SERVER, 'HTTP_REFERER') ? $_SERVER['HTTP_REFERER'] : 'index.php';
	}
?>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
<div class="column ink-alert block success">
<h4>Information</h4>
<p>Selected users were successfully invited!</p>
<p>Please click <a href="<?=$serverReferer?>">here</a> to continue.</p>
</div>
</div>
<?
	include('template/footer.php');
?>