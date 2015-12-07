var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1;
var yyyy = today.getFullYear();
var name = '';
var type = '';
var date1 = yyyy+'-'+mm+'-'+dd;

if(mm + 1 > 12) {
	yyyy++;
	mm = 1;
}
else {
	mm++;
}

var date2 = yyyy+'-'+mm+'-'+dd;
var date_select = -1;

$(function() {

	refreshContent(type,name, date1, date2, date_select);
	refreshButtons(date_select);

	$('#type').change(function() {
		if($('#type').val() != type) {
			type = $('#type').val();
			refreshContent(type, name, date1, date2, date_select);
		}
	});

	$('#name').keyup(function() {
		if($('#name').val() != name) {
			name = $('#name').val();
			refreshContent(type,name, date1, date2, date_select);
		}
	});

	$('#date_select').change(function() {
		if($('#date_select').val() != date_select) {
			date_select = $('#date_select').val();
			refreshButtons(date_select);
			refreshContent(type,name, date1, date2, date_select);
		}
	});
});

function refreshContent(type, name, date1, date2, date_select) {
	$.ajax({
		type: 'post',
		url: 'list_filtered_events.php',
		data: {
			'sortType': type,
			'sortName': name,
			'sortDate1': date1,
			'sortDate2': date2,
			'dateOptions': date_select
		},
		success: function(data) {
			$('div#container').children().not(':first').remove();
			$('div#container').append(data);
		}
	});
};

function refreshButtons(date_select) {
	$.ajax({
		type: 'post',
		url: 'load_date_buttons.php',
		data: {
			'sortDate': date_select
		},
		success: function(data) {
			$('div#display_buttons').children().remove();
			$('div#display_buttons').append(data);
		}
	});
};