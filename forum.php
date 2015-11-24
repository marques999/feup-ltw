<?php
    include('template/header.php');
?>
<script>
    $(document).ready(function() {
        $('#nav_forum').addClass('active');
    });
</script>
<div class="ink-grid all-80 large-80 medium-90 small-90">
	<div class="column-group half-vertical-space">
		<div class="column all-20 large-15 medium-10 small-0 tiny-0"></div>
		<div class="column all-60 large-70 medium-80 small-100 tiny-100 ink-alert block error" role="alert">
			<h4>Forbidden</h4>
			<p>You don't have permission to access this page!</p>
			<p>Please <a href="login.php">log in</a> with your account first.</p>
		</div>
	</div>
</div>
</div>
<?php
	include('template/footer.php')
?>