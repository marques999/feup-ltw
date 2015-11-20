<?
    include_once('database/connection.php');
    include_once('database/events.php');
    include('template/header.php');

    $featuredEvent = events_randomEvent();
    $result = events_listTopEvents(4);
?>
<script>
    $(document).ready(function() {
        $('#nav_index').addClass('active');
    });
</script>
<div class="ink-grid vertical-space">
<div class="panel half-vertical-space">   
    <div id="car3" class="ink-carousel xlarge-100 large-100 medium-100 small-100 tiny-100">
        <img class="half-bottom-space" src="http://lorempixel.com/1400/675/nightlife/1">
        <h4 class="no-margin"><a href="view_event.php?id=<?=$featuredEvent['idEvent']?>"><?=$featuredEvent['name']?></a></h4>
        <h5 class="slab"><?=date("l, d/m/Y H:i", $featuredEvent['date'])?></h5>
        <p><?=$featuredEvent['description']?></p>      
    </div>  
</div>
<div class="panel half-vertical-space">
    <div id="car2" class="ink-carousel">
        <ul class="stage column-group half-gutters unstyled">
            <?foreach($result as $row){?>
            <li class="slide xlarge-25 large-25 medium-33 small-50 tiny-100">
                <img class="half-bottom-space" src="http://lorempixel.com/400/200/city/1">
                <h4 class="no-margin"><a href="view_event.php?id=<?=$row['idEvent']?>"><?=$row['name']?></a></h4>
                <h5 class="slab"><?=date("l, d/m/Y H:i", $row['date'])?></h5>
                <p><?=$row['description']?></p>
            </li>
           <?}?>
        </ul>
    </div>
</div>
</div>
<?include('template/footer.php')?>