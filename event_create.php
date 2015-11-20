<?php
	include('template/header.php')
?>
<div class="column-group gutters all-100">
	<form action="" class="ink-form ink-formvalidator all-50 small-100 tiny-100">
		<fieldset>
			<legend class="align-center">Create event</legend>
			<div class="control-group required column-group gutters">
				<label for="name" class="all-50 align-right">Name</label>
				<div class="control all-50">
					<input name="name" id="name" type="text" data-rules="required|alpha" placeholder="Please enter your desired username">
				</div>
			</div>

			<div class="control-group required column-group gutters">
				<label for="password" class="all-50 align-right">Password</label>
				<div class="control append-symbol all-50">
					<span>
						<input name="password" id="password" type="password" data-rules="required" placeholder="Please enter your password">
						<i class="fa fa-key"></i>
					</span>
				</div>
			</div>
			<div class="control-group required column-group gutters">
				<label for="date" class="all-50 align-right">Date/Time</label>
		        <div class="control all-50">
		            <input name="date" id="date" type="text" class="ink-datepicker" data-format="d-m-Y" />
		        </div>
		    </div>
			<div class="control-group required column-group gutters">
				<label for="first-name" class="all-50 align-right">Name:</label>
				<div class="control all-25">
					<input type="text" id="first-name" data-rules="required|alpha">
					<p class="tip">First Name</p>
				</div>
				<div class="control all-25">
					<input type="text" id="last-name" data-rules="required|alpha">
					<p class="tip">Last Name</p>
				</div>
			</div>

			<div class="control-group required column-group gutters">
				<label for="email" class="all-50 align-right">E-mail:</label>
				<div class="control append-symbol all-50">
					<span>
					<input type="text" id="email" data-rules="required|email">
					<i class="fa fa-envelope-o"></i>
					</span>
				</div>
			</div>

			<div class="control-group required column-group gutters">
				<label for="location" class="all-50 align-right">Location:</label>
				<div class="control all-25">
					<input type="text" id="location" data-rules="required">
				</div>
				<div class="control all-25">
					<select>
						<option value="volvo">Volvo</option>
						<option value="saab">Saab</option>
						<option value="mercedes">Mercedes</option>
						<option value="audi">Audi</option>
					</select>
				</div>
			</div>

			<div class="control-group column-group gutters">
				<label for="file-input" class="all-50 align-right">Avatar:</label>
				<div class="control all-50">
					<div class="input-file">
						<input type="file" name="" id="file-input">
					</div>
				</div>
			</div>

			<div class="control-group column-group gutters">
				 <label for="" class="all-50 align-right"></label>
				<div class="control all-20 align-right">
					<input type="submit" name="sub" value="Submit" class="ink-button success" />
				</div>
			</div>
		</fieldset>
	</form>

	<div class="all-20 align-center small-100 tiny-100">
		<img src="holder.js/200x200/auto/ink" alt="">
	</div>
</div>
<?include('footer.php')?>