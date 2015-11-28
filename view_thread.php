<?
    include_once('database/connection.php');
    include_once('database/forum.php');
    include_once('database/users.php');
    include('template/header.php');

    if (isset($_GET['id'])) {

        $userId = intval($_GET['id']);
        $thisThread = thread_listById($_GET['id']);
        $thisPosts = thread_listPosts($_GET['id']);

        if (count($thisThread) > 0) {
            $thisThread = $thisThread[0];
        }
    }

    $numberPosts = count($thisPosts);
    $threadOP = $thisThread['idUser'];
    $thisOP = $allUsers[$threadOP];
?>

<script>
$(function() {
    $('#nav_forum').addClass('active');
});
</script>

<?if($loggedIn){?>
<div class="ink-grid all-100">
<div class="column-group gutters half-vertical-space">
    
<!-- BEGIN CURRENT EVENTS SECTION -->
<div class="all-100">
    <h3 class="slab half-vertical-space">My Events</h3>

    <div class="column-group panel half-vertical-space">
        
        <div class="column all-15">
            <p><a href="<?=users_viewProfile($threadOP)?>">
                <b><?=$thisOP['username']?></b>
            </a></p>
            <img class="half-bottom-space" src="<?=users_getSmallAvatar($threadOP)?>">
            <small>
            	<p><b>Posts:</b> 3</p>
            </small>
        </div>

        <div class=" column all-85">
        <b><?=$thisThread['title']?></b>
        <p class="no-margin">
            <small class="slab">
            <i class="fa fa-calendar"></i>
            <?=forum_printDate($thisThread)?>
            </small>
        </p>
        <p class="vertical-space"><?=$thisThread['message']?></p>
            <p class="no-margin align-right">
            <small><button class="ink-button"><i class="fa fa-pencil"></i> Reply</button></small>
            <small><button class="ink-button"><i class="fa fa-quote-right"></i> Quote</button></small>
            <?if($threadOP==$thisUser){?>
            <small><button class="ink-button"><i class="fa fa-remove"></i> Delete</button></small>
            <?}?>
        </p>
    </div>
    </div>


    <?if($numberPosts>0) {                  
        foreach($thisPosts as $currentPost) {           
        $postId = $currentPost['idPost'] - 1;
        $thisPoster = $currentPost['idUser'];
        if ($postId < 0){
            $postId = 0;
        }?>
        <div class="column-group panel half-vertical-space">
            <div class="column all-15">
                <p><a href="<?=users_viewProfile($thisPoster)?>">
                    <b><?=$currentPost['username']?></b>
                </a></p>
                <img class="half-bottom-space" src="<?=users_getSmallAvatar($thisPoster)?>">
                <small>
					<p><b>Posts:</b> 3</p>
           		</small>
            </div>
            <div class=" column all-85">
            <b>Re: <?=$thisThread['title']?></b>
            <p class="no-margin">
                <small class="slab">
                <i class="fa fa-calendar"></i>
                <?=forum_printDate($currentPost)?>
                </small>
            </p> 
            <?if(isset($currentPost['idQuote'])){
                $quoteId = $currentPost['idQuote'];

                if ($quoteId==0) {
                    $quotedPost = $thisThread;
                }
                else {
                    $quotedPost = $thisPosts[$quoteId];
                }
            ?>
            <p class="half-vertical-space">
                <small class="no-margin"><?=$quotedPost['username']?> wrote:</small>
                <blockquote class="no-margin">
                    <?=$quotedPost['message']?>
                </blockquote>
            </p>
            <?}?>
            <p class="vertical-space"><?=$currentPost['message']?></p>
                <p class="no-margin align-right">
                <small><a href="#write-comment" class="ink-button"><i class="fa fa-pencil"></i> Reply</a></small>
                <?if($currentPost['idUser']==$thisUser){?>
                <small><a class="ink-button"><i class="fa fa-remove"></i> Delete</a></small>
                <?}else{?>
                <small><a class="ink-button"><i class="fa fa-quote-right"></i> Quote</a></small>
                <?}?>
            </p>
            </div>
        </div>
        <?}?>
    <?}else{?>
        <li class="panel all-100">
            <span>This forum thread has no replies :(</span>
        </li>
    <?}?>
<!-- BEGIN DYNAMIC SECTION -->
	<div class="all-50"id="write-comment">
	<b>Write a reply</b>
	<form action="actions/action_reply.php" method="POST" class="ink-form ink-formvalidation all-100">
		<input type="hidden" name="idUser" value="<?=$_SESSION['userid']?>"></input>
		<input type="hidden" name="idEvent" value="<?=$eventId?>"></input>
		<div class="control-group column-group">
			<div class="control required all-100">
				<textarea name="message" rows="4" cols="80" placeholder="Insert your message here..."></textarea>
			</div>
		</div>
		<div class="no-margin all-100">
			<button type="submit" name="sub" class="ink-button success">
			<i class="fa fa-share"></i> Reply
			</button>
			<button type="reset" name="sub" value="Clear" class="ink-button">
			<i class="fa fa-eraser"></i> Clear
			</button>
		</div>
	</form>
	</div>
	<!-- END DYNAMIC SECTION -->
</div>
<!-- END CURRENT EVENTS SECTION -->





</div>
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