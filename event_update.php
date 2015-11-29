<?

    include_once('database/connection.php');
	include_once('database/session.php');
    include_once('database/users.php');
	include_once('database/events.php');
	include('template/header.php');
	
    $thisEvent = $defaultEvent;
    $eventId = 0;
    $ownEvent = false;

    if (isset($_GET['id']) && $loggedIn) {
    	$eventId = intval($_GET['id']);
    	$thisEvent = events_listById($eventId);

    	if (count($thisEvent > 0)) {
    		$thisEvent = $thisEvent[0];
    		$ownEvent = $thisUser == $thisEvent['idUser'];
    	}
	}

	if($loggedIn&&$ownEvent){
?>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
$(function() 
{     	
	currentTime = new Date(); 
	$('input#hours').val(currentTime.getHours());	
	$('input#minutes').val(currentTime.getMinutes());		
	$.getJSON("json/events.json", function(data)
	{ 	
		$.each(data, function(index, item)
		{
			$("select#type").append($("<option></option>").text(item).val(item));
		});
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

	if (navigator.geolocation) 
	{
		navigator.geolocation.getCurrentPosition(function(position) 
		{
			marker = new google.maps.Marker(
			{
				map: gmaps_map,
				position: {'lat':position.coords.latitude,'lng':position.coords.longitude}
			});
			locationString.val(marker.getPosition().toString());
			gmaps_map.setCenter(marker.getPosition());
		}, function() 
		{
			gmaps_map.setCenter(defaultMarker.getPosition());
		});
	}
	$('input#location').keydown(function(event) 
	{
		if (event.keyCode == 13 || event.which == 13) 
		{
			geocoder.geocode({'address':locationString.val()}, function(results, status)
			{
				if (status == google.maps.GeocoderStatus.OK)
				{
					marker = new google.maps.Marker({map:gmaps_map,position:results[0].geometry.location});
					gmaps_map.setCenter(marker.getPosition());
					locationString.val(results[0].geometry.location);
				}
			});
			event.preventDefault();
		}
	});	
});
</script>
<div class="ink-grid all-100">
<div class="column-group gutters">
	<form action="actions/action_update_event.php" class="ink-form ink-formvalidator all-50 small-100 tiny-100">
		<fieldset>
			<legend class="align-center">Update Event</legend>
			<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
			<button><img src="img/facebook-logo2.png" width="50" height="50"><a href="www.facebook.com"></a></button>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="name" class="all-30 align-right">Name:</label>
				<div class="control all-60">
					<input name="name" id="name" type="text" value=" <?php echo $thisEvent['name'];?>" data-rules="required|alpha">			
				</div>
			</div>
			
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
					<input type="text" id="location" data-rules="required">			
					<i class="fa fa-globe"></i>
					</span>
				</div>			
			</div>
			<!-- END EVENT DESCRIPTION -->


			<!-- BEGIN EVENT MAP -->
			<div style="height:300px" class="control append-symbol all-100" id="location-map">
			</div>
			<!-- END EVENT MAP -->


			<!-- BEGIN EVENT TYPE -->
			<div class="control-group required column-group half-gutters">
				<label for="type" class="all-30 align-right">Type:</label>
				<div class="control all-30">
					<select name="type" id="type">
					</select>
				</div>
				<div class="control all-30">
					<input type="text" name="other-type" placeholder="Other" data-rules="required">
				</div>
			</div>
			<!-- END EVENT TYPE -->


			<!-- BEGIN EVENT DATE -->
			<div class="control-group required column-group half-gutters">
				<label for="date" class="all-30 align-right">Date/Time</label>
		        <div class="control all-40">
		            <input name="date" id="date" type="text" class="ink-datepicker" data-format="d-m-Y" />
		        </div>
		        <div class="control all-10">
		       		<input name="hours" id="hours" type="number" value="12">
		        </div>
		     	<span class="quarter-left-space">
		     		:
		     	</span>
		        <div class="control all-10">
		        	<input name="minutes" id="minutes" type="number" value="30">
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
					<input type="submit" name="sub" value="Submit" class="ink-button success" />
				</div>
			</div>
			<!-- END EVENT SUBMIT -->
		</fieldset>
	</form>
	<div class="all-20 align-center small-100 tiny-100">
		<img src="holder.js/200x200/auto/ink" alt="">
	</div>
</div>
</div>
<?}else{?>
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
<?}?>
<?
	include('template/footer.php');
?>