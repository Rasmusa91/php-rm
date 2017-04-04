<?php
	include(__DIR__ . "/incl/config.php");
	include(__DIR__ . "/incl/postHandler.php");
	
	if($appelicious["selectedPage"] != "img") {
		include(APPELICIOUS_RENDER_PATH);
	}
	else {
		include("pages/img.php");
	}
?>