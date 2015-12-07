<?
	include_once('database/connection.php');
	include_once('database/events.php');
	include_once('database/users.php');
	include('template/header.php');

	if (isset($_GET['tp'])) {
		$sortType = $_GET['tp'];
	}
	else {
		$sortType = 'name';
	}

	if ($sortType != 'date' && $sortType != 'type' && $sortType != 'popularity') {
		$sortType = 'name';
	}
?>
<script src="event_browse.js"></script>
<div class="ink-grid all-100">
<div class="column-group gutters half-vertical-space">
	<div class="column align-center all-15 large-15 medium-20 small-25">
		<nav class="ink-navigation bottom-space">
			<h4 class>Sort By</h4>
			<ul class="menu vertical">
				<li><a id="sort-name">Name&nbsp;&nbsp;</a></li>
				<li><a id="sort-date">Date&nbsp;&nbsp;</a></li>
				<li><a id="sort-popularity">Popularity&nbsp;&nbsp;</a></li>
				<li><a id="sort-type">Type&nbsp;&nbsp;</a></li>
			</ul>
		</nav>
		<button id="sort-ascending" class="ink-button">
			<i class="fa fa-sort-amount-asc"></i>
		</button>
		<button id="sort-descending" class="ink-button">
			<i class="fa fa-sort-amount-desc"></i>
		</button>
	</div>
	<div id="container" class="column all-85 large-85 medium-80 small-75 tiny-100">
	</div>
</div>
</div>
<?
	include('template/footer.php')
?>