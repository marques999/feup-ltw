<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');
    
    $s_type = 'name';
    $eventsPerPag = 4;
     			  
    if(isset($_GET['tp'])) {
		$s_type = $_GET['tp'];
    }
        
    if($s_type != 'date' &&
		$s_type != 'type' &&
		$s_type != 'popularity') {
		$s_type = 'name';
    }      
?>

<script>
	var sort_type = '<?=$s_type?>';
	var descendingOrder = 0;
	var start = 0;

	$(function()
	{			
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
		if(st == sort_type) {
			descendingOrder ^= 1;
		}
		else {
			oldElement.removeClass('active');
			newElement.addClass('active');
			descendingOrder = 0;
			sort_type = st;		
		}
	};
	
	function removeIcon(sort_type) {
		$("a#sort-" + sort_type).parent().find('i').remove();
	};
	
	function insertIcon(sort_order, sort_type) {
		var targetElement = $("a#sort-" + sort_type);
		if(!sort_order) {
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
				'eventsPerPag': <?=$eventsPerPag?>				
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
</script>
<?if($loggedIn){?>
	<div class="ink-grid all-100">
		<div class="column-group gutters half-vertical-space">
			<div class="column align-center all-15 large-15 medium-20 small-25">
				<nav class="ink-navigation bottom-space">	
					<h4 class>Sort By</h4>
					<ul class="menu vertical">			
						<li><a id="sort-name">Name&nbsp;&nbsp;</a></li>
						<li><a id="sort-date">Date&nbsp;&nbsp;</a></li>
						<li><a id="sort-popularity">Popularity&nbsp;&nbsp;</a></li>
						<li><a id="sort-type">Type&nbsp;&nbsp;</a></li>
					</ul>					
				</nav>						
				<button id="sort-ascending" class="ink-button">
					<i class="fa fa-sort-amount-asc"></i>
				</button>
				<button id="sort-descending" class="ink-button">
					<i class="fa fa-sort-amount-desc"></i>
				</button>										
			</div>
			<div id="container" class="column all-85 large-85 medium-80 small-75 tiny-100">		
				<h3 class="slab half-vertical-space">Public Events</h3>																																				
			</div>						
		</div>						
	</div>			
<?}else{?>
	<div class="ink-grid push-center all-50 large-70 medium-80 small-100 tiny-100">
		<div class="column ink-alert block error">
			<h4>Forbidden</h4>
			<p>You don't have permission to access this page!</p>
			<p>Please <a href="login.php">log in</a> with your account first.</p>
		</div>
	</div>
<?}?>
<?
    include('template/footer.php')
?>