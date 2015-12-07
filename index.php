<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/salt.php');
	include_once('database/users.php');
	include('template/header.php');

	$featuredEvent=events_randomEvent();
	$upcomingEvents=events_listTopEvents(4);
?>
<script>
$(function(){
	$('#nav_index').addClass('active');
});
</script>
<div class="ink-grid vertical-space">
<h3 class="slab">Featured Event</h3>
<div class="panel half-vertical-space">
	<div class="stage">
		<img class="all-100 half-bottom-space" src="<?=events_getImage($featuredEvent,'original')?>">
		<h4 class="no-margin"><a href="<?=events_viewEvent($featuredEvent)?>">
				<?=events_getName($featuredEvent)?>
		</a></h4>
		<h5 class="slab"><?=events_getDate($featuredEvent)?></h5>
		<p><?=$featuredEvent['description']?></p>
	</div>
</div>
<h3 class="slab half-vertical-space">Popular Events</h3>
<div class="panel half-vertical-space">
	<ul class="stage column-group half-gutters unstyled">
		<?foreach($upcomingEvents as $currentEvent){?>
		<li class="slide xlarge-25 large-25 medium-50 small-50 tiny-100">
			<img class="all-100 half-bottom-space" src="<?=events_getImage($currentEvent,'medium')?>">
			<h4 class="no-margin"><a href="<?=events_viewEvent($currentEvent)?>">
				<?=events_getName($currentEvent)?>
			</a></h4>
			<h5 class="slab"><?=events_getDate($currentEvent)?></h5>
			<p><?=$currentEvent['description']?></p>
		</li>
	   <?}?>
	</ul>
</div>
</div>
<?
	include('template/footer.php')
?>