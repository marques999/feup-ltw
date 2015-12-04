<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');	
	
	
	$types = event_listTypes();
	$n_types = count($types);
	
	
?>

<script>
	
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
			
</script>

<div class="ink-grid all-100 large-80 medium-80 small-100 tiny-100">
	<div class="ink-form column all-100 half-gutters">
		<div class="control-group column-group half-gutters">
			<label for="name" class="all-5 align-right">
				<b>Name</b>
			</label>
			<div class="control all-50">				
				<input type="text" id="name" name="name">		 
			</div>	
			<label for="type" class="all-5 align-left">
				<b>Type</b>
			</label>
			<div class="control all-20">
				<select id="type">			
					<option value="">All Types</option>
					<?for($i = 0; $i < $n_types; $i++) {?>
						<option value="<?=$types[$i]['type']?>"><?=$types[$i]['type']?></option>
					<?}?>
				</select>
			</div>	
			</div>	
				
		<div class="control-group column-group half-gutters">
			<label for="date_select" class="all-5 align-right">
				<b>Date</b>
			</label>
			<div class="control all-20">
				<select id="date_select">			
					<option value="-1">All Dates</option>		
					<option value="0">Events after</option>					
					<option value="1">Events before</option>
					<option value="2">Events exactly</option>	
					<option value="3">Events between</option>					
				</select>	
			</div>
			<div class="control all-50" id="display_buttons">
			</div>				
		</div>
	</div>	
	<div id="container" class="column all-90 large-85 medium-80 small-75 tiny-100">																														
	</div>	
</div>
<?
    include('template/footer.php')
?>