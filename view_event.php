<?
	include_once('database/connection.php');
	include_once('database/comments.php');
	include_once('database/events.php');
    include_once('database/users.php');
	include('template/header.php');

	$thisEvent = array(
			'idEvent' => -1,
			'name' => 'Procrastinate',
			'date' => 0,
			'location' => 'World',
			'description' => 'This is just a dummy event...',
			'type' => 'Dummy',
			'idUser' => -1);

	$isParticipating = false;
	$isOwner = false;
	$eventId = 0;
	$loggedIn = false;
	$allUsers = users_listAll();

	if (isset($_GET['id'])) {

		$eventId = $_GET['id'];
		$getEvent = events_listById($eventId);

		if (count($getEvent) > 0) {
			$thisEvent = $getEvent[0];
		}

		$thisParticipants = events_listParticipants($eventId);
		$thisComments = events_listTopComments($eventId, 5);
        $thisOwner = users_listById($thisEvent['idUser'])[0];
        $loggedIn = (isset($_SESSION['username']) == true);
        
        if ($loggedIn) {
        	$isParticipating = users_isParticipating($_SESSION['userid'], $eventId);
        	$isOwner = ($thisOwner['idUser'] == $_SESSION['userid']);
        }
	}

	$numberParticipants = count($thisParticipants);
	$numberComments = count($thisComments);
?>

<script>
    $(document).ready(function() {
        $('#nav_browse').addClass('active');
    });
</script>
<section class="column-group gutters article">
<div class="xlarge-15 large-15 medium-10 small-5 tiny-5">
</div>
<div class="xlarge-70 large-70 medium-80 small-90 tiny-90">
	<article>
		<h1 class="slab no-margin all-100"><?=$thisEvent['name']?></h1>
		<?if($isOwner) {?>
		<nav id="#nav" class="ink-navigation half-vertical-space">
		    <ul class="pagination pills red">
		    <!-- check if user is already participating on the event -->
		        <?if($isParticipating){?>
				<li class="active"><a href="#nav"><i class="fa fa-check"></i> Following</a></li>
				<?}else{?>
		        <li><a href="#"><i class="fa fa-edit"></i></a></li>
		        <?}?>
		        <li><a href="#"><i class="fa fa-user-plus"></i></a></li>
		        <li><a href="#"><i class="fa fa-trash"></i></a></li>
		    </ul>
		</nav>
		<?}?>
        <div class="panel push-right">
            <img src="images/avatars/<?=$thisOwner['idUser']?>_small.png"/>
            <b class="quarter-space">
            <a href="view_profile.php?id=<?=$thisOwner['idUser']?>"><?=$thisOwner['username']?></a>
        </div>
        <div>
            <h5 class="slab"><?=$thisEvent['type']?></h5>
            <p class="no-margin"><b>Date: </b><?=date("l, d/m/Y H:i", $thisEvent['date'])?></p>
            <p><b>Location: </b><?=$thisEvent['location']?></p>
        </div>
		<figure class="ink-image half-vertical-space">
			<img src="holder.js/1200x600/auto/ink" alt="">
			<figcaption class="condensed-regular">
            <?=$thisEvent['description']?>
			</figcaption>
		</figure>
		<?if($loggedIn){?>
		<nav id="#nav" class="ink-navigation half-vertical-space">
		    <ul class="pagination pills red">
		    <!-- check if user is already participating on the event -->
		        <?if($isParticipating){?>
				<li class="active"><a href="#nav"><i class="fa fa-check"></i> Following</a></li>
				<?}else{?>
		        <li><a href="#"><i class="fa fa-check"></i> Follow</a></li>
		        <?}?>
		        <li><a href="#"><i class="fa fa-comment"></i> Comment</a></li>
		        <?if($isParticipating){?>
		        <li><a href="#"><i class="fa fa-upload"></i> Upload Photos</a></li>
		        <?}else{?>
		        <li class="disabled"><a href="#nav"><i class="fa fa-upload"></i> Upload Photos</a></li>
		        <?}?>
		    </ul>
		</nav>
		<?}?>

	<!-- BEGIN PARTICIPANTS SECTION -->
	<h2>Participants (<?=$numberParticipants?>) </h2>
	<div class="all-100 half-vertical-space">
		<?if ($numberParticipants > 0) {
		for ($i = 0; $i < $numberParticipants; $i++) {

			$currentParticipant = $thisParticipants[$i];

			if ($i % 3 == 0) {
				echo '<div class="column-group half-gutters">';
			}?>

			<div class="all-30 large-35 medium-40 small-50 tiny-50">
            <img src="images/avatars/<?=$currentParticipant['idUser']?>_small.png"/>
			<b class="quarter-space">
			<a href="view_profile.php?id=<?=$currentParticipant['idUser']?>"><?=$currentParticipant['username']?></a>
			</b></div>

			<?if ($i == count($thisParticipants) - 1) {
				echo '</div>';
			}
		}
		} else {?>
			<p>This event has no participants :(</p>
		<?}?>
	</div>
	<!-- END PARTICIPANTS SECTION -->

	<!-- BEGIN COMMENTS SECTION -->
	<h2 id="comments">Comments (<?=$numberComments?>) </h2>
	<div class="column-group vertical-space">
	<?if ($numberComments > 0){foreach($thisComments as $currentComment){?>
	 <div class="column all-100">
		<img class="push-left half-right-space" src="images/avatars/<?=$currentComment['idUser']?>_small.png"/>   
			<a href="view_profile.php?id=<?=$currentComment['idUser']?>">
			<?=$allUsers[$currentComment['idUser'] - 1]['username']?></a>
			<small><?=date("l, d/m/Y H:i", $currentComment['timestamp'])?></small>
			<p class="fw-medium"><?=$currentComment['message']?></p>	
		</div>
	<?}?>
	<?}else{?>
		<p>This event has no comments :(</p>
	<?}?>
	</div>
	<!-- END COMMENTS SECTION -->

	<!-- BEGIN DYNAMIC SECTION -->
	<form action="action_comment.php" method="POST" class="ink-form ink-formvalidation all-100">
		<input type="hidden" name="idUser" value="<?=$_SESSION['userid']?>"></input>
		<input type="hidden" name="idEvent" value="<?=$eventId?>"></input>
		<div class="control-group column-group half-gutters">
			<div class="control required all-100">
				<textarea name="message" rows="4" cols="80" placeholder="Insert your comment here..."></textarea>
			</div>
		</div>
		<div class="control-group column-group half-gutters">
			<div class="control all-100">
				<button type="submit" name="sub" class="ink-button red success">
				<i class="fa fa-share"></i> Send
				</button>
				<button type="reset" name="sub" value="Clear" class="ink-button red">
				<i class="fa fa-eraser"></i> Clear
				</button>
			</div>
		</div>
	</form>
	<!-- END DYNAMIC SECTION -->

	</article>
</div>
</section>

<?include('template/footer.php')?>