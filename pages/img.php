<?php
	$dir = __dir__ . DIRECTORY_SEPARATOR  . ".." . DIRECTORY_SEPARATOR  . "img" . DIRECTORY_SEPARATOR;
	
	$src = isset($_GET["src"]) ? $_GET["src"] : null;
	$type = isset($_GET["type"]) ? $_GET["type"] : null;
	$width = isset($_GET["width"]) ? $_GET["width"] : null;
	$height = isset($_GET["height"]) ? $_GET["height"] : null;
	$cropFit = isset($_GET["crop-fit"]) ? $_GET["crop-fit"] === "true" : false;
	$quality = isset($_GET["quality"]) ? $_GET["quality"] : null;
	$sharpen = isset($_GET["sharpen"]);
	$filter = isset($_GET["filter"]) ? $_GET["filter"] : null;
	$ignoreCache = isset($_GET["ignore-chache"]);
	$verbose = isset($_GET["verbose"]);

	if(!is_file($dir . $src)) {
		$src = "image404.jpg";
	}
	
	$image = new CImage($dir, $src, $type, $width, $height, $cropFit, $quality, $sharpen, $filter, $ignoreCache, $verbose);
	$image->Render();