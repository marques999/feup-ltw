<?
    include_once('database/connection.php');
    include_once('database/events.php');
    include_once('database/salt.php');
    include_once('database/users.php');
    include_once('database/session.php');
    include('template/header.php');

    $currentDate = time();
    $featuredEvent = events_randomEvent();
    
    if ($loggedIn) {
        $upcomingEvents = users_listFutureEvents($thisUser, $currentDate);
    }
    else {
        $upcomingEvents = events_listTopEvents(10);
    }
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
            <?foreach($upcomingEvents as $currentEvent){?>
            <li class="slide xlarge-25 large-25 medium-33 small-50 tiny-100">
                <img class="half-bottom-space" src="http://lorempixel.com/400/200/city/1">
                <h4 class="no-margin"><a href="view_event.php?id=<?=$currentEvent['idEvent']?>"><?=$currentEvent['name']?></a></h4>
                <h5 class="slab"><?=date("l, d/m/Y H:i", $currentEvent['date'])?></h5>
                <p><?=$currentEvent['description']?></p>
            </li>
           <?}?>
        </ul>
    </div>
</div>
</div>

<?
    include('template/footer.php')
?>