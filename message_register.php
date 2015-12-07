<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');
	$errorId = safe_getId($_GET, 'id');
?>
<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
<?if($errorId>0){?>
	<div class="column ink-alert block error">
	<h4>Database Error</h4>
<?}else{?>
	<div class="column ink-alert block success">
	<h4>Information</h4>
<?}?>
<?if($errorId==0){?>
	<p>User account created successfully!</p>
<?}elseif($errorId==1){?>
	<p>User account creation failed: username already taken!</p>
<?}elseif($errorId==2){?>
	<p>User account creation failed: another user with the same e-mail address exists!</p>
<?}?>
<?if($errorId>0){?>
	<p>Please click <a href="register.php">here</a> to continue</p>
<?}else{?>
	<p>Please click <a href="login.php">here</a> to continue</p>
<?}?>
</div>
</div>
<?
	include('template/footer.php');
?>