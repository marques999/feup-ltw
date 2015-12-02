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
?>

<script>
$(function(){
	$('#nav_events').addClass('active');
});
</script>

<?if($loggedIn && $ownEvent){?>
<script src="https://maps.googleapis.com/maps/api/js"></script>
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

google.maps.event.addDomListener(window, 'load', function() {

	var defaultMarker = new google.maps.Marker({
		map: gmaps_map,
		position: {'lat': 44.5403, 'lng': -78.5463}
	});

	var gmaps_map = new google.maps.Map(document.getElementById('location-map'), {
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var geocoder = new google.maps.Geocoder();
	var locationString = $("input#location");
	var marker = null;

	locationString.keydown(function(event) {
		
		if (event.keyCode == 13 || event.which == 13) {
			
			geocoder.geocode({'address':locationString.val()}, function(results, status) {
				
				if (status == google.maps.GeocoderStatus.OK) {
					marker = new google.maps.Marker({map:gmaps_map,position:results[0].geometry.location});
					gmaps_map.setCenter(marker.getPosition());
					locationString.val(results[0].geometry.location);
				}
			});

			event.preventDefault();
		}
	});

	startEvent = $.Event('keydown');
	startEvent.which = 13;
	locationString.trigger(startEvent);
});
</script>

<div class="ink-grid all-100">
<div class="column-group gutters">
	<form action="actions/action_update_event.php" method="post" class="ink-form ink-formvalidator all-60 small-100 tiny-100">
		<fieldset>
			<legend class="align-center">Update Event</legend>

			<!-- BEGIN EVENT NAME -->
			<div class="control-group required column-group half-gutters">
				<label for="name" class="all-30 align-right">Name:</label>
				<div class="control all-60">
					<input name="name" id="name" type="text" value=" <?=$thisEvent['name']?>" data-rules="required">
				</div>
			</div>
			<!-- END EVENT NAME -->


			<!-- BEGIN EVENT DESCRIPTION -->
			<div class="control-group required column-group half-gutters">
				<label for="description" class="all-30 align-right">Description:</label>
				<div class="control all-60">
					<textarea name="description" rows="8" cols="60" data-rules="required"><?=$thisEvent['description']?></textarea>
				</div>
			</div>
			<!-- END EVENT DESCRIPTION -->


			<!-- BEGIN EVENT LOCATION -->
			<div class="control-group required column-group half-gutters">
				<label for="location" class="all-30 align-right">Location:</label>
				<div class="control append-symbol all-60">
					<span>
					<input type="text" value="<?=$thisEvent['location']?>" id="location" data-rules="required">
					<i class="fa fa-globe"></i>
					</span>
				</div>
			</div>
			<!-- END EVENT DESCRIPTION -->


			<!-- BEGIN EVENT TYPE -->
			<div class="control-group required column-group half-gutters">
				<label for="type" class="all-30 align-right">Type:</label>
				<div class="control all-30">
					<select name="type" id="type">
					</select>
				</div>
				<div class="control all-30">
					<input type="text" id="custom-type" name="custom-type">
				</div>
			</div>
			<!-- END EVENT TYPE -->


			<!-- BEGIN EVENT DATE -->
			<?$dateArray=getdate($thisEvent['date']);?>
			<div class="control-group required column-group half-gutters">
				<label for="date" class="all-30 align-right">Date/Time</label>
		        <div class="control all-40">
		            <input name="date" id="date" type="text" data-format="d-m-Y" />
		        </div>
		        <div class="control all-10">
		       		<input name="hours" id="hours" type="number" value="<?=$dateArray['hours']?>">
		        </div>
		        <div class="control all-10">
		        	<input name="minutes" id="minutes" type="number" value="<?=$dateArray['minutes']?>">
		        </div>
		    </div>
			<!-- END EVENT DATE -->


			<!-- BEGIN EVENT PHOTOS -->
			<div class="control-group column-group gutters">
				<label for="file-input" class="all-30 align-right">Photo:</label>
				<div class="control all-50">
					<div class="input-file">
						<input type="file" name="" id="file-input">
					</div>
				</div>
			</div>
			<!-- END EVENT PHOTOS -->


			<!-- BEGIN EVENT SUBMIT -->
			<div class="control-group column-group gutters">
				 <label for="" class="all-50 align-right"></label>
				<div class="control all-20 align-right">
					<input type="submit" name="sub" value="Submit" class="ink-button" />
				</div>
			</div>
			<!-- END EVENT SUBMIT -->
		</fieldset>
	</form>
	

	<div class="padding all-40 align-center small-100 tiny-100">
		

		<!-- BEGIN EVENT PHOTO -->
		<div class="vertical-space">
			<img class="all-100" src="<?=events_getImage($thisEvent,'medium')?>">
		</div>
		<!-- END EVENT PHOTO -->


		<!-- BEGIN EVENT MAP -->
		<div style="height:400px" class="control all-100" id="location-map">
		</div>
		<!-- END EVENT MAP -->


	</div>
</div>
</div>
<?}else{
	include_once('message_guest.php');
}
include('template/footer.php');
?>