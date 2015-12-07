<?
	include_once('database/connection.php');
	include_once('database/session.php');
	include_once('database/users.php');
	include('template/header.php');
?>
<script>
$(function(){
	$('#nav_events').addClass('active');
});
</script>
<?if($loggedIn){?>
<script src="js/gmaps.min.js"></script>
<script src="js/imgcentering.min.js"></script>
<script src="event_create.js"></script>
<script src="upload_photo.js"></script>
<div class="ink-grid all-100">
<div class="column-group">
	<form action="actions/action_create_event.php" method="post" enctype="multipart/form-data" class="ink-formvalidator all-50 medium-60 small-100 tiny-100">
		<fieldset>
			<input type="hidden" name="idUser" id="idUser" value="<?=$thisUser?>">
			<legend class="align-center">Create Event</legend>

			<!-- BEGIN EVENT NAME -->
			<div class="control-group required column-group half-gutters">
				<label for="name" class="all-20 align-right">Name:</label>
				<div class="control all-80">
					<input name="name" id="name" type="text" data-rules="required|text[true,true]" placeholder="Please enter the event name">
				</div>
			</div>
			<!-- END EVENT NAME -->


			<!-- BEGIN EVENT DESCRIPTION -->
			<div class="control-group required column-group half-gutters">
				<label for="description" class="all-20 align-right">Description:</label>
				<div class="control all-80">
					<textarea name="description" id="description" rows="8" cols="60" data-rules="required" placeholder="Please enter the event description"></textarea>
				</div>
			</div>
			<!-- END EVENT DESCRIPTION -->


			<!-- BEGIN EVENT LOCATION -->
			<div class="control-group required column-group half-gutters">
				<label for="location" class="all-20 align-right">Location:</label>
				<div class="control append-symbol all-60">
					<span>
					<input type="text" id="location" name="location" data-rules="required">
					<i class="fa fa-globe"></i>
					</span>
				</div>
				<ul class="control no-margin unstyled all-20">
					<li>
						<input type="checkbox" id="private" name="private" value="0">
						<label for="private">Private</label>
					</li>
				</ul>
			</div>
			<!-- END EVENT DESCRIPTION -->


			<!-- BEGIN EVENT TYPE -->
			<div class="control-group required column-group half-gutters">
				<label for="type" class="all-20 align-right">Type:</label>
				<div class="control all-40">
					<select name="type" id="type">
					</select>
				</div>
				<div class="control all-40">
					<input type="text" id="custom-type" name="custom-type">
				</div>
			</div>
			<!-- END EVENT TYPE -->


			<!-- BEGIN EVENT DATE -->
			<div class="control-group required column-group half-gutters">
				<label for="date" class="all-20 align-right">Date/Time</label>
		        <div class="control all-50">
		            <input name="date" id="date" type="text" class="ink-datepicker" data-rules="required" data-format="d-m-Y" />
		        </div>
		        <div class="control all-15">
		       		<input name="hours" id="hours" type="number" data-rules="integer|range[0,23]" value="12">
		        </div>
		        <div class="control all-15">
		        	<input name="minutes" id="minutes" type="number" data-rules="integer|range[0,59]" value="30">
		        </div>
		    </div>
			<!-- END EVENT DATE -->


			<!-- BEGIN EVENT PHOTO -->
			<div class="control-group column-group half-gutters">
				<label for="image" class="all-20 align-right">Photo:</label>
				<div class="control all-80">
					<div class="input-file">
						<input type="file" name="image" id="image">
					</div>
				</div>
			</div>
			<!-- END EVENT PHOTO -->


			<!-- BEGIN SUBMIT BUTTONS -->
			<div class="control-group column-group half-gutters">
				<div class="control all-20 push-center">
					<input type="submit" value="Submit" class="ink-button">
				</div>
			</div>
			<!-- END SUBMIT BUTTONS -->
		</fieldset>
	</form>
	<div class="column-group padding align-center all-40 small-100 tiny-100">
		<div id="avatar-parent" class="half-bottom-space">
			<img id="avatar" class="all-100" src="holder.js/400x256/auto/ink">
		</div>
		<div class="gmaps control all-100" id="location-map">
		</div>
	</div>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>