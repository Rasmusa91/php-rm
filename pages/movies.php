<?php
	if(isset($_POST["removeMovieSubmit"])) {
		include("movies/remove.php");
	}
	
	if(!isset($currentSubPage)) 
	{
		include("movies/overview.php");
	}
	else if($currentSubPage == "add" && $user->HasAdminAccess())
	{
		include("movies/manage.php");
	}
	else 
	{
		$db = new CDatabase($database);
		
		$query = "	SELECT m.*, GROUP_CONCAT(\" \", projectmovieimages.path) AS additionalImages 
					FROM (
						SELECT projectmovies.*, GROUP_CONCAT(\" \", projectmoviegenre.name) AS genre
						FROM projectmovies
						LEFT JOIN projectmoviegenrerelation
						ON projectmovies.id = projectmoviegenrerelation.movie_id
						LEFT JOIN projectmoviegenre
						ON projectmoviegenre.id = projectmoviegenrerelation.genre_id
						GROUP BY id
					) m
					
					LEFT JOIN projectmovieimages
					ON m.id = projectmovieimages.movie_id
					
					WHERE m.slug = ?
					GROUP BY m.id
				;";
		
		$imageData = $db->ExecuteSelectQueryAndFetchAll($query, array($currentSubPage)); 

		if(count($imageData) > 0) {
			$imageData = $imageData[0];
			$additionalImages = array_filter(explode(",", str_replace(" ", "", $imageData->additionalImages)));
		}
				
		if(isset($imageData->slug)) 
		{
			if($currentSubSubPage == "edit" && $user->HasAdminAccess()) {
				include("movies/manage.php");
			}
			else if($currentSubSubPage == "remove" && $user->HasAdminAccess()) {
				include("movies/single.php");
			}
			else {
				include("movies/single.php");
			}
		}
		else {
			include("error.php");
		}
	}
?> 