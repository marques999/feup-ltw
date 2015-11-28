<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include('template/header.php');
?>

<div class="ink-grid all-45 large-60 medium-80 small-100 tiny-100">
	<div class="column all-100 ink-alert block error">
		<h4>Database Error</h4>
		<ul>
		<li>Do not panic</li>
		<li>Do not call our service line</li>
		<li>Breathe deep and wait an hour</li>
	</div>
</div>

<?
	include('template/footer.php');
?>