<script>
$(function(){
	footerLinks = $("ul#footer-links");
	footerElem = $("#footer");
	footerLinks.hide();
	footerElem.hover(function(){
		footerLinks.stop(true,true).slideToggle('fast');
	});
});
</script>
<footer id="footer" class="clearfix">
<div class="ink-grid fw-regular">
<ul id="footer-links" class="unstyled small inline quarter-vertical-space">
<li class="active"><a href="about.php">About</a></li>
<li><a href="#">Sitemap</a></li>
<li><a href="contacts.php">Contacts</a></li>
</ul>
<p class="note medium no-margin half-top-padding half-bottom-padding"><i class="fa fa-copyright"></i> 2015 Carlos Samouco, Diana Pinto, Diogo Marques</p>
</div>
</footer>
</body>
</html>