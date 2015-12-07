<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');
?>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
<div class="column ink-alert block error">
<h4>Notice</h4>
<p>
<strong>WARNING: </strong>
Javascript is disabled on your current browser.
Please activate it in order to take full advantage of this site and refresh the page</p>
<p>Alternatively, you can <a href="index.php">click here</a> to return to the home page.</p>
</div>
</div>
<?
	include('template/footer.php');
?>