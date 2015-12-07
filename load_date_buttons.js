$(function(){
Ink.requireModules(['Ink.Dom.Selector_1','Ink.UI.DatePicker_1'],function( Selector, DatePicker ){

	var datePickerObj = new DatePicker('#myDatePicker1', { onSetDate: function() {
		unprocdate1 = datePickerObj.getDate();
		date1 = unprocdate1.getFullYear() + "-" + (unprocdate1.getMonth() + 1) + "-" + unprocdate1.getDate();
		refreshContent(type, name, date1, date2, date_select);
	}});

	datePickerObj.setDate(date1);

	if($('#myDatePicker2').length > 0) {

		var datePickerObj2 = new DatePicker('#myDatePicker2', { onSetDate: function() {
			unprocdate2 = datePickerObj2.getDate();
			date2 = unprocdate2.getFullYear() + "-" + (unprocdate2.getMonth() + 1) + "-" + unprocdate2.getDate();
			refreshContent(type, name, date1, date2, date_select);
		}});

		var unprocdate = datePickerObj.getDate();
		var mm = unprocdate.getMonth() + 1;
		var yyyy = unprocdate.getFullYear();
		var dd = unprocdate.getDate();

		if (mm + 1 > 12) {
			yyyy++;
			mm = 1;
		}
		else {
			mm++;
		}

		datePickerObj2.setDate(yyyy + "-" + mm + "-" + dd);
	}
});
});