<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include('template/header.php');
?>
<div class="ink-grid push-center all-50 large-60 medium-90 small-100 tiny-100">
<form action="action_logout.php" method="post" class="ink-form">
<fieldset class="align-center">
<legend class="align-center">Terminate Session</legend>
<p>Are you sure you want to terminate your current session and log out?</p>
<div class="control push-center all-20">
<button type="submit" class="ink-button"><i class="fa fa-key"></i> Logout</button>
</div>
</fieldset>
</form>
</div>
<?
	include('template/footer.php');
?>