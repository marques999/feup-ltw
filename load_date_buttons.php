<?$sort_date=$_POST['sortDate'];?>
<script src="load_date_buttons.js"></script>
<?if($sort_date==0||$sort_date==1||$sort_date==2){?>
<div class="control all-100">				
	<input type="text" id="myDatePicker1" class="ink-datepicker">
</div>
<?}elseif($sort_date==3){?>
<div class="control all-30 medium-50 small-50 tiny-50">		
	<input type="text" id="myDatePicker1" class="all-100 ink-datepicker">
</div>
<div class="control all-30 medium-50 small-50 tiny-50">				
	<input type="text" id="myDatePicker2" class="all-100 ink-datepicker">
</div>	
<?}?>