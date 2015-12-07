google.maps.event.addDomListener(window, 'load', function() {
	var map = new google.maps.Map(document.getElementById('map-location'), {
		center: {lat: 0, lng: 0},
		disableDefaultUI: true,
		zoomControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var geocoder = new google.maps.Geocoder();
	var infowindow = new google.maps.InfoWindow;
	var locationString = $('a#location-link');
	var mapContainer = $('div#map-container');
	var marker = null;
	var latlngStr = locationString.text().split(',', 2);
	var latlng = {
		lat:parseFloat(latlngStr[0]),
		lng:parseFloat(latlngStr[1])
	};

	mapContainer.hide();
	geocoder.geocode({'location':latlng}, function(results, status) {
		if (status === google.maps.GeocoderStatus.OK && results[1]) {
			marker = new google.maps.Marker({position:latlng,map:map});
			map.setZoom(16);
			infowindow.setContent('<b>' + results[1].formatted_address + "</b><p>" + marker.getPosition().toString() + '</p>');
			locationString.text(results[1].formatted_address);
		}
		google.maps.event.addListener(marker, 'click', function(event) {
			infowindow.open(map,marker);
		});
	});

	locationString.click(function() {
		mapContainer.slideToggle('fast', function() {
			if (mapContainer.is(":visible")) {
				google.maps.event.trigger(map, 'resize');
				map.setCenter(marker.getPosition());
				infowindow.open(map, marker);
			}
		});
	});
});