<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');
?>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
	<div class="column ink-alert block error">
		<h4>Database Error</h4>
		<p>Image upload failed, user has chosen an <strong>invalid</strong> image format...</p>
		<p>Please click <a href="register.php">here</a> to continue.</p>
	</div>
</div>
<?
	include('template/footer.php');
?>