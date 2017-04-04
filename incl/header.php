<div id = "headerLogo">
	<div id = "headerLogoInner">
		<div class = "left">
			<img src = "<?= WORKSPACE_SERVERPATH; ?>img/?src=logo.png&height=50">
		</div>
		<div class = "left">			
			<p class = "logoTitle">RM Rental Movies</p>
			<p class = "logoSlogan">"Time to rewind"</p>
		</div>
		<div class = "clear"></div>
	</div>
</div>

<div id = "headerNavbar">
	<?php		
		echo getLinks(getSitemapByCat($sitemap, "menuNav"), "current", $selectedPage);
	?>
</div>