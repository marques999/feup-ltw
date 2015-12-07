google.maps.event.addDomListener(window, 'load', function()
{
	var gmaps_map = new google.maps.Map(document.getElementById('location-map'),
	{
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var geocoder = new google.maps.Geocoder();
	var locationString = $("input#location");
	var marker = null;

	locationString.keydown(function(event)
	{
		if (event.keyCode == 13 || event.which == 13)
		{
			geocoder.geocode({'address':locationString.val()}, function(results, status)
			{
				if (status == google.maps.GeocoderStatus.OK)
				{
					marker = new google.maps.Marker(
					{
						map:gmaps_map,
						position:results[0].geometry.location
					});
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