<div class = "darkWrapper2">
	<div class = "wrapperTitle">VINN EN FILM</div>
	<div class = "darkWrapper">
		<?php
			//$_SESSION["games"]["dice100"] = null;
			$gameHandeler = (isset($_SESSION["games"]["dice100"]) ? $_SESSION["games"]["dice100"] : new CDice100Game(true));
			$gameHandeler->play();
			$_SESSION["games"]["dice100"] = $gameHandeler;
			
			$db = new CDatabase($database); 
			$query = "
				SELECT *
				FROM projectmovies
				WHERE projectmovies.published <= NOW() AND (projectmovies.deleted IS NULL OR projectmovies.deleted >= NOW())
				ORDER BY RAND()
				LIMIT 1
				;
			";
			$randomMovie = $db->ExecuteSelectQueryAndFetchAll($query);
			$gameHandeler->SetAdditionalWinMessage("<h2>Du har vunnit en film!</h2> <p>Följ <a href = \"" . WORKSPACE_SERVERPATH . "movies/" . $randomMovie[0]->slug . "\">länken</a> för att se vilken!</p>");
		?>
	</div>
</div>