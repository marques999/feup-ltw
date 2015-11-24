<script>
$(document).ready(function() {
	var header_container  = $('#header-container>h1');
	var header_navigation = $('nav#header-menu');
	var header_height = header_container.outerHeight();
	var document_window = $(window);

	document_window.scroll(function(){
	    if (document_window.scrollTop() > header_height) {
	       header_navigation.addClass('fixed-header');
	    } else {
	       header_navigation.removeClass('fixed-header');
	    }
	});

	document_window.resize(function() {
		header_height = header_container.outerHeight() + 1;
	})
});
</script>
<footer class="clearfix">
<div class="ink-grid">
<ul class="unstyled inline quarter-vertical-space">
<li class="active"><a href="about.php">About</a></li>
<li><a href="#">Sitemap</a></li>
<li><a href="contacts.php">Contacts</a></li>
</ul>
<p class="note"><i class="fa fa-copyright"></i> 2015 Carlos Samouco, Diana Pinto, Diogo Marques</p>
</div>
</footer>
</body>
</html>