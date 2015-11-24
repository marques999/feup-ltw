<?
	include('../template/header.php');

	if (isset($_SESSION['username'])) {
		unset($_SESSION['username']);
	}

	if (isset($_SESSION['userid'])) {
		unset($_SESSION['userid']);
	}

	session_destroy();
	header("Refresh: 3; URL={$_SERVER['HTTP_REFERER']}");
?>
<script>
	$(document).ready(function() {
		$('#nav_profile').addClass('active');
	});
</script>
<div class="ink-grid all-80 large-80 medium-90 small-90">
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block success" role="alert">
			<h4>Information</h4> 
			<p>You have successfully logged out and will be redirected shortly...</p>
		</div>
	</div>
</div>
<?
	include('../template/footer.php');
?>