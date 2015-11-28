<?
    include_once('database/connection.php');
    include_once('database/users.php');
	include('template/header.php');

	$thisError = 0;
	$nextId = users_getNextId() + 1;
	
	if (isset($_GET['error'])) {
		$thisError = $_GET['error'];
	}
?>

<script>

var wrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
var fileInput = $('#file-input').wrap(wrapper);

$(document).ready(function() {
	$('#nav_profile').addClass('active');
	$("#country-list option").remove();
	$.getJSON("json/countries.json", function(data) {  	
		$.each(data, function(index, item) {
    		$("#country-list").append($("<option></option>").text(item).val(index));
		});
	});

	wrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
	fileInput = $('#file-input');
/*	fileInput.change(function(){
    	$.ajax({
		type: 'post',
		url: 'actions/action_upload_photo.php',
		data: {
				'source': 'user',
				'idUser': <?=$nextId?>
		},
		contentType: false,
		cache: false,
		processData: false,
		success: function(destination) {
			if (destination == window.location.pathname) {
				window.location.reload();
			}
			else {
				window.location = destination;
			}
		}
		});
	})*/
});

function readURL(input) {
	
	if (input.files && input.files[0]) {
	    console.log("here");
	    var goUpload = true;
	    var uploadFile = input.files[0];
	    
	    if (!(/\.(bmp|gif|jpg|jpeg|png)$/i).test(uploadFile.name)) {
	        fileInput.effect("shake");
	        fileInput.text('You must select an image file only');
	       	    console.log("here2");
	        setTimeout(function() {
	        	fileInput.text('Choose an avatar...');
	     	} ,5000);
	        
	        return false;
	    }
	   	    console.log("here3");
	    if (uploadFile.size > 2000000) { // 2mb
	       fileInput.text('Please upload a smaller image, max size is 2 MB');
	        
	        setTimeout(function() {
	        	fileInput.text('Choose an avatar...');
	        }, 5000);
	        
	        return false;
	    }

        fileInput.text("Uploading "+uploadFile.name);

        var reader = new FileReader();
        
        reader.onload = function (e) {

            $('img#blah').attr('src', e.target.result);

            var width = $('#blah').width(); // Current image width
            var height = $('#blah').height(); // Current image height

            if (width > 200 || height > 200) {
                
                var newHeight = 120 * (height / width);
                var newWidth = 120 * (width / height);
               
                if (width > height) {
                    $('img#blah').css("width", newWidth + 'px'); // set new width
                    $('img#blah').css("height", '120px'); // scale height based on ratio

                }             
                else if (height > width) {

                    $('img#blah').css("height", newHeight + 'px'); // set new width
                    $('img#blah').css("width", '120px'); // scale height based on ratio

                }

                fileInput.text(uploadFile.name);
            }
            else {
                fileInput.text("Minimum file size: 120x120px");
                
                setTimeout(function() {
                	fileInput.text('Choose file');
                }, s5000);
            }

        }

        reader.readAsDataURL(uploadFile);
	}
}
</script>

<div class="ink-grid all-80">
<div class="column-group all-100 vertical-space">
	<form action="action_create_user.php" method="post" enctype="multipart/form-data" class="ink-form ink-formvalidator">
		<fieldset>
			<legend class="align-center">Register Account</legend>
			<div class="control-group required column-group half-gutters">
				<label for="username" class="all-25 align-right">Username</label>
				<div class="control append-symbol all-70">
					<span>
						<input name="username" id="username" type="text" data-rules="required|alpha_numeric" placeholder="Please enter your desired username">
						<i class="fa fa-user"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="password" class="all-25 align-right">Password</label>
				<div class="control append-symbol all-70">
					<span>
						<input name="password" id="password" type="password" data-rules="required|min_length[8]" placeholder="Please enter your password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="first-name" class="all-25 align-right">Name:</label>
				<div class="control all-35">
					<input type="text" name="first-name" data-rules="required">
					<p class="tip">First Name</p>
				</div>
				<div class="control all-35">
					<input type="text" name="last-name" data-rules="required">
					<p class="tip">Last Name</p>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="email" class="all-25 align-right">E-mail:</label>
				<div class="control append-symbol all-70">
					<span>
					<input type="text" name="email" data-rules="required|email">
					<i class="fa fa-envelope-o"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group half-gutters">
				<label for="location" class="all-25 align-right">Location:</label>
				<div class="control all-40">
					<input type="text" name="location" data-rules="required">
				</div>
				<div class="control all-30">
					<select name="country" id="country-list">
					</select>
				</div>
			</div>
			<div class="control-group column-group half-gutters">
				<label for="file-input" class="all-25 align-right">Avatar:</label>
				<input type="hidden" name="idUser" value="<?=$nextId?>">
				<div class="control all-70">
					<div class="input-file">
						<input type="file" name="userfile" id="file-input">
					</div>
				</div>
			</div>
			<div class="control-group column-group half-gutters">
			<label for="" class="all-25 align-right"></label>
				<div class="column control all-30">
					<input type="submit" value="Submit" class="ink-button success" />
				</div>
				<div class="column control all-30">
					<input type="reset" value="Reset" class="ink-button" />
				</div>
			</div>
		</fieldset>
	</form>
	<div id="blah" class="all-25 align-center small-100 tiny-100 vertical-space">
		<img id="blah" src="holder.js/200x200/auto/ink" alt="">
		<div class="ink-alert basic">
		 <p><b>Warning:</b> Avatar size must not exceed 50000 bytes!</p>
		</div>
	</div>
</div>
</div>

<?
	include('template/footer.php');
?>