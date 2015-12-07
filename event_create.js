$(function()
{
	currentTime = new Date();
	comboTypes = $("select#type");
	customType = $("input#custom-type").hide();
	$('input#hours').val(currentTime.getHours());
	$('input#minutes').val(currentTime.getMinutes());
	$.getJSON("json/events.json", function(data)
	{
		$.each(data, function(index, item)
		{
			$("select#type").append($("<option></option>").text(item).val(item));
		});
	});
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
			marker = new google.maps.Marker({
				map: gmaps_map,
				position: {'lat':position.coords.latitude,'lng':position.coords.longitude }
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