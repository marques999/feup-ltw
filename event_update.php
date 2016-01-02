<?
	include_once('database/connection.php');
	include_once('database/session.php');
	include_once('database/users.php');
	include_once('database/events.php');
	include('template/header.php');

	$thisEvent = $defaultEvent;
	$ownEvent = false;
	$eventId = safe_getId($_GET, 'id');

	if ($loggedIn) {
		$getEvent = events_listById($eventId);
		if (count($getEvent) > 0) {
			$thisEvent = $getEvent[0];
			$ownEvent = $thisUser == $thisEvent['idUser'];
		}
	}

	$formattedDate = gmdate("Y-m-d", $thisEvent['date']);
	$temporaryDate = gmstrftime("%H:%M", $thisEvent['date']);
	$dateArray = strptime($temporaryDate, "%H:%M");
?>
<script>
$(function(){
	$('#nav_events').addClass('active');
});
</script>
<?if($loggedIn && $ownEvent){?>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAObn6F3iTncHgv8HrByEfgnlAbNSnfPOE"></script>
<script src="js/imgcentering.min.js"></script>
<script src="event_update.js"></script>
<script src="upload_photo.js"></script>
<script>
$(function(){
	currentTime = new Date();
	comboTypes = $("select#type");
	customType = $("input#custom-type").hide();
	eventTypes = {};
	comboTypes.change(function() {
		if(comboTypes.val() == 'Other') {
			customType.attr('data-rules', 'required');
			customType.show();
		}
		else {
			customType.removeAttr('data-rules');
			customType.hide();
		}
	});
	$.getJSON("json/events.json", function(data) {
		$.each(data, function(index, item) {
			eventTypes[item] = item;
			comboTypes.append($("<option></option>").text(item).val(item));
		});

		Ink.requireModules(['Ink.UI.DatePicker_1'],function(DatePicker) {
			datePicker = new DatePicker('#date');
			datePicker.setDate("<?=$formattedDate?>");
		});

		thisEventType = '<?=$thisEvent['type']?>';

		if (eventTypes[thisEventType] != undefined) {
			comboTypes.val(thisEventType).change();
		}
		else {
			comboTypes.val("Other").change();
			customType.val(thisEventType);
		}
	});
});
</script>
<div class="ink-grid all-100">
<div class="column-group">
	<form action="actions/action_update_event.php" method="post" enctype="multipart/form-data" class="ink-formvalidator all-50 medium-60 small-100 tiny-100">
		<fieldset>
			<input type="hidden" name="idEvent" id="idEvent" value="<?=$eventId?>">
			<legend class="align-center">Update Event</legend>

			<!-- BEGIN EVENT NAME -->
			<div class="control-group required column-group half-gutters">
				<label for="name" class="all-20 align-right">Name:</label>
				<div class="control all-80">
					<input name="name" id="name" type="text" value="<?=$thisEvent['name']?>" data-rules="required|text[true,true]" placeholder="Please enter the event name">
				</div>
			</div>
			<!-- END EVENT NAME -->


			<!-- BEGIN EVENT DESCRIPTION -->
			<div class="control-group required column-group half-gutters">
				<label for="description" class="all-20 align-right">Description:</label>
				<div class="control all-80">
					<textarea name="description" rows="8" cols="60" data-rules="required" placeholder="Please enter the event description"><?=$thisEvent['description']?></textarea>
				</div>
			</div>
			<!-- END EVENT DESCRIPTION -->


			<!-- BEGIN EVENT LOCATION -->
			<div class="control-group required column-group half-gutters">
				<label for="location" class="all-20 align-right">Location:</label>
				<div class="control append-symbol all-80">
					<span>
					<input type="text" id="location" name="location" value="<?=$thisEvent['location']?>" data-rules="required">
					<i class="fa fa-globe"></i>
					</span>
				</div>
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
		            <input name="date" id="date" type="text" data-rules="required" data-format="d-m-Y" />
		        </div>
		        <div class="control all-15">
		       		<input name="hours" id="hours" type="number" data-rules="integer|range[0,23]" value="<?=$dateArray['tm_hour']?>">
		        </div>
		        <div class="control all-15">
		        	<input name="minutes" id="minutes" type="number" data-rules="integer|range[0,59]" value="<?=$dateArray['tm_min']?>">
		        </div>
		    </div>
			<!-- END EVENT DATE -->


			<!-- BEGIN EVENT PHOTOS -->
			<div class="control-group column-group half-gutters">
				<label for="image" class="all-20 align-right">Photo:</label>
				<div class="control all-80">
					<div class="input-file">
						<input type="file" name="image" id="image">
					</div>
				</div>
			</div>
			<!-- END EVENT PHOTOS -->


			<!-- BEGIN EVENT SUBMIT -->
			<div class="control-group column-group half-gutters">
				<p class="align-center">
					<button type="submit" class="ink-button">Update</button>
					<button type="reset" class="ink-button" onclick="history.go(-1)">Cancel</button>
				</p>
			</div>
			<!-- END EVENT SUBMIT -->
		</fieldset>
	</form>
	<div class="padding align-center all-40 small-100 tiny-100">
		<div id="avatar-parent" class="half-bottom-space">
			<img id="avatar" class="all-100" src="<?=events_getImage($thisEvent,'medium')?>">
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