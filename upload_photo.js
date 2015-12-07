$(function(){
	fileInput = $('#image');
	fileInput.change(function(){

	if (typeof(FileReader) == 'undefined') {
		return false;
	}

	var image_holder = $("div#avatar-parent")
	var image_preview = $("img#avatar");
	var reader = new FileReader();

	reader.onload = function (e) {
		image_preview.attr('src', e.target.result);
		image_preview.imgCentering();
	}

	if (!(/\.(gif|jpg|jpeg|png)$/i).test($(this)[0].value)) {
		image_preview.attr('src', null);
	}
	else {
		reader.readAsDataURL($(this)[0].files[0]);
	}
	});
});