<?
	include_once('database/connection.php');
	include_once('database/users.php');
	include_once('database/session.php');
	include('template/header.php');
?>
<div class="ink-grid push-center all-60 large-70 medium-85 small-100 tiny-100">
<div class="column-group half-vertical-space">
<h2 class="slab">Sitemap</h2>
<ul class="column all-50"><li><strong>User Events</strong><ul>
<li><a href="event_create.php">Create Event</a></li>
<li><a href="manage_events.php">Manage Events</a></li>
</ul></li>
<li><strong>User Profile</strong><ul>
<li><a href="register.php">Register Account</a></li>
<li><a href="delete_user.php?id=<?=$thisUser?>">Delete Account</a></li>
<li><a href="update_profile.php?field=1">Change Password</a></li>
<li><a href="update_profile.php?field=2">Change Name</a></li>
<li><a href="update_profile.php?field=3">Change E-mail</a></li>
<li><a href="update_profile.php?field=4">Change Location</a></li>
<li><a href="update_profile.php?field=5">Change Avatar</a></li>
<li><a href="view_profile.php?id=<?=$thisUser?>">View Profile</a></li>
<li><a href="manage_invites.php">Manage Invites</a></li>
</ul></li></ul>
<ul class="column all-50"><li><strong>Forum</strong><ul>
<li><a href="forum.php">View Forum</a></li>
<li><a href="create_thread.php">Create Thread</a></li>
</ul></li>
<li><strong>Browse Events</strong><ul>
<li><a href="event_search.php">Event Search</a></li>
<li><a href="event_browse.php?tp=name">Sort By Name</a></li>
<li><a href="event_browse.php?tp=date">Sort By Date</a></li>
<li><a href="event_browse.php?tp=popularity">Sort By Popularity</a></li>
<li><a href="event_browse.php?tp=type">Sort By Type</a></li>
</ul></li>
<li><strong>Others</strong><ul>
<li><a href="login.php">Login Account</a></li>
<li><a href="logout.php">Terminate Session</a></li>
<li><a href="about.php">About</a></li>
<li><a href="contacts.php">Contacts</a></li>
</ul></li></ul>
</div>
</div>
<?
	include('template/footer.php');
?>