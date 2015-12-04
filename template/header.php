<?
	if(!isset($_SESSION)){
		session_start();
	}
	include('database/session.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cascading Events</title>
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="shortcut icon" href="img/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="css/font-roboto.css">
<link rel="stylesheet" type="text/css" href="css/ink.css">
<link rel="stylesheet" type="text/css" href="css/my-defaults.css">
<link rel="stylesheet" type="text/css" href="css/my-fonts.css">
<link rel="stylesheet" type="text/css" href="css/my-navigation.css">
<link rel="stylesheet" type="text/css" href="css/my-forms.css">
<noscript>
<meta http-equiv="refresh" content="0; url=message_noscript.php" />
</noscript>
<script type="text/javascript" src="js/modernizr.js"></script>
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
Modernizr.load({
	test:Modernizr.flexbox,
	nope:'../css/ink-legacy.min.css'
});
$(function() {
	var header_container = $('#header-container>h1');
	var header_navigation = $('nav#header-menu');
	var header_height = header_container.outerHeight();
	var document_window = $(window);

	document_window.scroll(function(){
		if (document_window.scrollTop() > header_height) {
		   header_navigation.addClass('fixed-header');
		}
		else {
		   header_navigation.removeClass('fixed-header');
		}
	});

	document_window.resize(function() {
		header_height = header_container.outerHeight();
	});
});
</script>
<script type="text/javascript" src="js/holder.min.js"></script>
<script type="text/javascript" src="js/ink-all.min.js"></script>
<script type="text/javascript" src="js/autoload.min.js"></script>
</head>
<body>
<header id="header-container">
<h1>Cascading Events<small>social events network</small></h1>
<nav id="header-menu" class="ink-navigation">
<ul class="menu horizontal">
	<li id="nav_index"><a href="index.php"><i class="fa fa-home"></i></a></li>
	<li id="nav_browse">
		<a href="#"><i class="fa fa-search"></i> Browse Events</a>
		<ul class="submenu">
			<li><a href="event_search.php"><strong>Advanced Search</strong></a></li>
			<li><a href="event_browse.php?tp=name">Sort By Name</a></li>
			<li><a href="event_browse.php?tp=date">Sort By Date</a></li>
			<li><a href="event_browse.php?tp=popularity">Sort By Popularity</a></li>
			<li><a href="event_browse.php?tp=type">Sort By Type</a></li>
		</ul>
	</li>
	<li id="nav_events"><a href="manage_events.php"><i class="fa fa-gears"></i> Manage Events</a></li>
	<li id="nav_forum"><a href="forum.php"><i class="fa fa-comment"></i> Forum</a></li>
	<div class="push-right fw-300">
		<li class="align-right" id="nav_profile">
		<?if(isset($_SESSION['username'])){?>
			<a><i class="fa fa-user"></i> <?=$_SESSION['username']?></a>
			<ul class="submenu all-100">
				<li><a href="view_profile.php?id=<?=$_SESSION['userid']?>">My Profile</a></li>
				<?if($numberInvites>0){?>
					<li><strong><a href="manage_invites.php">My Invites (<?=$numberInvites?>)</a></strong></li>
				<?}else{?>
					<li><a href="manage_invites.php">My Invites (<?=$numberInvites?>)</a></li>
				<?}?>
				<li><a href="action_logout.php">Logout</a></li>
			</ul>
		<?}else{?>
		<a href="login.php"><i class="fa fa-user"></i> Login</a>
		<?}?>
		</li>
	</div>
</ul>
</nav>
</header>