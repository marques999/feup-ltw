<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Forms</title>
<meta name="author" content="ink, cookbook, recipes">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="shortcut icon" href="img/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/ink-flex.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/overrides.css">
<script type="text/javascript" src="js/modernizr.js"></script>
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
Modernizr.load({
test: Modernizr.flexbox,
nope : '../css/ink-legacy.min.css'
});
</script>
<script type="text/javascript" src="js/holder.js"></script>
<script type="text/javascript" src="js/ink-all.min.js"></script>
<script type="text/javascript" src="js/autoload.js"></script>
</head>
<body>
<?session_start()?>
<header class="red">
<h1>Cascading Events<small>my first website</small></h1>
<nav class="ink-navigation">
<ul class="menu horizontal red">
<li id="nav_index"><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
<li id="nav_browse"><a href="#"><i class="fa fa-search"></i> Browse</a></li>
<li id="nav_events"><a href="manage_events.php"><i class="fa fa-gears"></i> Manage Events</a></li>
<li id="nav_forum"><a href="register.php"><i class="fa fa-comment"></i> Forum</a></li>
<?if(isset($_SESSION['username'])){?>
<li id="nav_profile"><a href="view_profile.php?id=<?=$_SESSION['userid']?>"><i class="fa fa-user"></i> <?=$_SESSION['username']?></a></li>
<?}else{?>
<li id="nav_profile"><a href="login.php"><i class="fa fa-user"></i> Login</a></li>
<?}?>
</ul>
</nav>
</header>