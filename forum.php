<?
    include_once('database/connection.php');
    include_once('database/forum.php');
    include_once('database/users.php');
    include('template/header.php');

    $allThreads = forum_allThreads();
    $numberReplies = forum_countReplies();
    $lastReplies = forum_lastReplies();
?>

<script>
    $(document).ready(function() {
        $('#nav_forum').addClass('active');
    });
</script>

<?if($loggedIn){?>
<div class="ink-grid all-100">
<table class="ink-table alternating hover">
	<thead>
		<th>Topics</th>
		<th>Replies</th>
		<th>Author</th>
		<th>Views</th>
		<th>Last Post</th>
	</thead>
	<tbody>
	<?foreach($allThreads as $currentThread){
		$threadId = $currentThread['idThread'] - 1;
		$hasReplies = isset($lastReplies[$threadId]);
		$threadUser = $currentThread['idUser'];	
		$thisOP = $allUsers[$threadUser];

		if ($hasReplies) {
			$lastPosterId = $lastReplies[$threadId]['idUser'];
			$lastPoster = $allUsers[$lastPosterId];
		}
		else {
			$lastPosterId = $threadUser;
			$lastPoster = $allUsers[$lastPosterId];
		}

		if ($threadId < 0){
			$threadId = 0;
		}?>
		<tr>
			<td>
				<a href="<?=forum_viewThread($currentThread)?>"><?=$currentThread['title']?></a>
				<p><small><?=forum_printDate($currentThread)?></small></p>
			</td>
			<?if($hasReplies){?>
				<td class="align-center"><?=$numberReplies[$threadId]['count']?></td>
			<?}else{?>
				<td class="align-center">0</td>
			<?}?>
			
			<td>
				<a href="<?=users_viewProfile($threadUser)?>">
					<img src="<?=users_getSmallAvatar($threadUser)?>">
				</a>
				<span class="half-horizontal-space vertical-space">
					<a class="red" href="<?=users_viewProfile($threadUser)?>"><?=$thisOP['username']?></a>
				</span>
			</td>
			<td class="align-center"><?=$currentThread['hits']?></td>
			<td>
				<i class="fa fa-user"></i>
				<a href="<?=users_viewProfile($lastPosterId)?>"><?=$lastPoster['username']?></a>
				<p><small><?=forum_printDate($currentThread)?></small></p>
			</td>
		</tr>
	<?}?>
	</tbody>
</table>
</div>
<?}else{?>
<div class="ink-grid all-45 large-60 medium-80 small-100 tiny-100">
	<div class="column ink-alert block error">
		<h4>Forbidden</h4>
		<p>You don't have permission to access this page!</p>
		<p>Please <a href="login.php">log in</a> with your account first.</p>
	</div>
</div>
<?}?>

<?
	include('template/footer.php');
?>