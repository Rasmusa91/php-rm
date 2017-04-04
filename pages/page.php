<?php	
	if(file_exists(__dir__ . "/" . $selectedPage . ".php")) {
		include $selectedPage . ".php";
	}
	else {
		include("error.php");		
	}
?>