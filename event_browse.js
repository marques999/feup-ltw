var defaultOrder = {
	name: 0,
	date: 0,
	popularity: 1,
	type: 0
};

var sort_type = getUrlVariables()['tp'] || 'name';
var descendingOrder = defaultOrder[sort_type];
var start = 0;

function getUrlVariables() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
	function(m,key,value) {
	  vars[decodeURIComponent(key)] = decodeURIComponent(value);
	});
	return vars;
};

$(function(){
	insertIcon(descendingOrder, sort_type);
	$("a#sort-" + sort_type).parent().addClass('active');
	refreshContent(sort_type, descendingOrder, start);
	$('a#sort-name').click(function() {
		removeIcon(sort_type);
		changeOrder('name');
		insertIcon(descendingOrder, sort_type);
		refreshContent(sort_type, descendingOrder, start);
	});
	$('a#sort-date').click(function() {
		removeIcon(sort_type);
		changeOrder('date');
		insertIcon(descendingOrder, sort_type);
		refreshContent(sort_type, descendingOrder, start);
	});
	$('a#sort-popularity').click(function() {
		removeIcon(sort_type);
		changeOrder('popularity');
		insertIcon(descendingOrder, sort_type);
		refreshContent(sort_type, descendingOrder, start);
	});
	$('a#sort-type').click(function() {
		removeIcon(sort_type);
		changeOrder('type');
		insertIcon(descendingOrder, sort_type);
		refreshContent(sort_type, descendingOrder, start);
	});
	$('#sort-ascending').click(function(){
		descendingOrder = 0;
		removeIcon(sort_type);
		insertIcon(descendingOrder, sort_type);
		refreshContent(sort_type, descendingOrder, start);
	});
	$('#sort-descending').click(function(){
		descendingOrder = 1;
		removeIcon(sort_type);
		insertIcon(descendingOrder, sort_type);
		refreshContent(sort_type, descendingOrder, start);
	});
});

function changeOrder(st) {
	var oldElement = $("a#sort-" + sort_type).parent();
	var newElement = $("a#sort-" + st).parent();

	if (st == sort_type) {
		descendingOrder ^= 1;
	}
	else {
		oldElement.removeClass('active');
		newElement.addClass('active');
		descendingOrder = defaultOrder[st];
		sort_type = st;
	}
};

function removeIcon(sort_type) {
	$("a#sort-" + sort_type).parent().find('i').remove();
};

function insertIcon(sort_order, sort_type) {

	var targetElement = $("a#sort-" + sort_type);

	if (!sort_order) {
		targetElement.append('<i class="fa fa-sort-asc"></i>');
	}
	else {
		targetElement.append('<i class="fa fa-sort-desc"></i>');
	}
};

function refreshContent(sort, order, st) {
	$.ajax({
		type: 'post',
		url: 'list_events.php',
		data: {
			'sortType': sort,
			'sortOrder': order,
			'startEvent': st,
			'eventsPerPag': 8
		},
		success: function(data) {
			$('div#container').children().not(':first').remove();
			$('div#container').append(data);
		}
	});
};

function nextPag() {
	start++;
	refreshContent(sort_type, descendingOrder, start);
};

function prevPag() {
	start--;
	refreshContent(sort_type, descendingOrder, start);
};

function goToPag(pag) {
	start = pag;
	refreshContent(sort_type, descendingOrder, start);
};