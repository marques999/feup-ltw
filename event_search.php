<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');
?>
<script src="event_search.js"></script>
<div class="ink-grid all-100">
<form>
	<div class="control-group column-group half-gutters">
		<label for="name" class="all-10 medium-10 small-15 tiny-15 align-right"><b>Name</b></label>
		<div class="control all-50 small-40 tiny-40"><input type="text" id="name" name="name"></div>
		<label for="type" class="all-5 medium-10 small-15 tiny-15 align-right"><b>Type</b></label>
		<div class="control all-20 medium-25 small-30 tiny-30">
			<select id="type">
			<option value="">All Types</option>
			<?$types = event_listTypes();
			foreach($types as $type){?>
			<option value="<?=$type['type']?>"><?=$type['type']?></option>
			<?}?>
			</select>
		</div>
	</div>
	<div class="control-group column-group half-gutters">
		<label for="date_select" class="all-10 small-15 tiny-15 align-right"><b>Date</b></label>
		<div class="control column all-30">
			<select id="date_select">
				<option value="-1">All Dates</option>
				<option value="0">Events after</option>
				<option value="1">Events before</option>
				<option value="2">Events exactly</option>
				<option value="3">Events between</option>
			</select>
		</div>
		<div class="control-group column-group half-gutters all-50 medium-55 small-55 tiny-55" id="display_buttons">
		</div>
	</div>
</form>
<div id="container" class="column all-100">
</div>
</div>
<?
	include('template/footer.php')
?>