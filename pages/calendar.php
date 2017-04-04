<?php
	$calendar = new CCalendar();
	$currentDate = $calendar->GetCalendarData()->GetCurrentMonthAndYear();	

	$db = new CDatabase($database); 
	
	// Check if there is already a movie at this date
	$query = "
		SELECT *
		FROM projectmovieimages
		LEFT JOIN projectmovies
		ON projectmovies.id = projectmovieimages.movie_id
		LEFT JOIN projectmonthlymovie
		ON projectmonthlymovie.movie_id = projectmovies.id
		WHERE projectmovies.published <= NOW() AND (projectmovies.deleted IS NULL OR projectmovies.deleted >= NOW()) AND projectmonthlymovie.month = ? AND projectmonthlymovie.year = ?
		ORDER BY RAND()
		LIMIT 1
		;
	";
	$monthlyMovie = $db->ExecuteSelectQueryAndFetchAll($query, array($currentDate["month"], $currentDate["year"]));
	
	// If tehre wasnt a movie at this date
	if(count($monthlyMovie) <= 0)
	{
		// Find a random movie (with posters)
		$query = "
			SELECT *
			FROM projectmovieimages
			LEFT JOIN projectmovies
			ON projectmovies.id = projectmovieimages.movie_id
			WHERE projectmovies.published <= NOW() AND (projectmovies.deleted IS NULL OR projectmovies.deleted >= NOW())
			ORDER BY RAND()
			LIMIT 1
			;
		";
		$monthlyMovie = $db->ExecuteSelectQueryAndFetchAll($query); 
		
		// Insert this to be the movie of the date
		$query = "
			INSERT INTO projectmonthlymovie
			(movie_id, month, year)
			VALUES (?, ?, ?)
		";
		$res = $db->ExecuteQuery($query, array($monthlyMovie[0]->movie_id, $currentDate["month"], $currentDate["year"]));
	}
	
	$calendar->GetCalendarData()->SetCustomImage("<a href = \"" . WORKSPACE_SERVERPATH . "movies/" . $monthlyMovie[0]->slug . "\"/><img src = \"" . WORKSPACE_SERVERPATH . "img/?src=movie/" . $monthlyMovie[0]->path . "&width=932&height=500&crop-fit=true\"></a>");
	
	echo $calendar->render();