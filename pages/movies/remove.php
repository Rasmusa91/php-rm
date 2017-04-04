<?php
	if(isset($_POST["removeMovieSubmit"]))
	{
		$db = new CDatabase($database);
		$removeMovieID = ValidatePost("removeMovieID");
		
		if(isset($removeMovieID))
		{
			$query = "	UPDATE projectmovies 
						SET deleted = NOW()
						WHERE id = ?";		
			$res = $db->ExecuteQuery($query, array($removeMovieID));
			
			echo "<script>window.location.replace(\"" . WORKSPACE_SERVERPATH . "movies/\");</script>";
		}
		
	/*
		$baseDir = __dir__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "movie" . DIRECTORY_SEPARATOR;
		
		$removeMovieID = ValidatePost("removeMovieID");
		
		// Delete all additional images
		$db = new CDatabase($database);
		$query = "	SELECT path 
					FROM projectmovieimages
					WHERE movie_id = ?
				;";
		$removeMovieAdditionalImages = $db->ExecuteSelectQueryAndFetchAll($query, array($removeMovieID)); 
		
		foreach($removeMovieAdditionalImages as $removeMovieAdditionalImagesValue)
		{
			unlink($baseDir . $removeMovieAdditionalImagesValue->path);
		}
		
		// Delete the poster file
		$db = new CDatabase($database);
		$query = "	SELECT poster 
					FROM projectmovies
					WHERE id = ?
				;";
		$removeMoviePoster = $db->ExecuteSelectQueryAndFetchAll($query, array($removeMovieID));		
		
		foreach($removeMoviePoster as $removeMoviePosterValue)
		{
			unlink($baseDir . $removeMoviePosterValue->poster);
		}

		// Delete everything in the database associated from this movie
		$query = "
			DELETE FROM projectmovieimages WHERE movie_id = ?				
		";
		$res = $db->ExecuteQuery($query, array($removeMovieID));
		
		$query = "
			DELETE FROM projectmoviegenrerelation WHERE movie_id = ?				
		";
		$res = $db->ExecuteQuery($query, array($removeMovieID));	

		$query = "
			DELETE FROM projectmovies WHERE id = ?				
		";
		$res = $db->ExecuteQuery($query, array($removeMovieID));	
*/		
	}
?>