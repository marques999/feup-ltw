<?
    include_once('database/connection.php');
    include_once('database/events.php');
    include('template/header.php');

    $eventTypes = event_listTypes();
?>
<script>
    $(document).ready(function() {
        $('#nav_browse').addClass('active');
    });
</script>
<div class="ink-grid vertical-space">
<div class="column-group">
<nav class="ink-navigation">
    <ul class="menu vertical black">
    	<?foreach($eventTypes as $currentType){?>
			<li><a href="#"><?=$currentType['type']?></a></li>
		<?}?>
    </ul>
</nav>
</div>
</div>

<?include('template/footer.php')?>