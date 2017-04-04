<?php 
    $orderBy = (isset($_GET["orderBy"]) ? $_GET["orderBy"] : null); 
    $orderType = (isset($_GET["orderType"]) ? $_GET["orderType"] : null); 
    $limit = (isset($_GET["limit"]) ? $_GET["limit"] : 8); 
    $pagination = (isset($_GET["pagination"]) ? $_GET["pagination"] : 1); 
    $titleSearch = (isset($_GET["title"]) ? $_GET["title"] : ""); 
    $genreSearch = (isset($_GET["genre"]) ? $_GET["genre"] : ""); 
    $yearStartSearch = (isset($_GET["yearStart"]) ? $_GET["yearStart"] : ""); 
    $yearEndSearch = (isset($_GET["yearEnd"]) ? $_GET["yearEnd"] : ""); 
     
    $db = new CDatabase($database); 
    $query = "	SELECT SQL_CALC_FOUND_ROWS m.* 
				FROM (
					SELECT projectmovies.slug, projectmovies.title, projectmovies.year, projectmovies.length, projectmovies.director, projectmovies.actors, projectmovies.plot, projectmovies.poster, projectmovies.price, 
					GROUP_CONCAT(\" \", projectmoviegenre.name) AS genre FROM projectmovies, projectmoviegenre, projectmoviegenrerelation 
					WHERE projectmovies.id = projectmoviegenrerelation.movie_id AND projectmoviegenre.id = projectmoviegenrerelation.genre_id AND published <= NOW() AND (deleted IS NULL OR deleted >= NOW())
					GROUP BY projectmovies.id
				) m
				"; 
     
    $queryConditions = array(); 

    if(!empty($titleSearch)) { 
        $queryConditions[] = "m.title LIKE \"%" . $titleSearch . "%\""; 
    }     
     
    if(!empty($genreSearch)) { 
        $queryConditions[] = "m.genre LIKE \"%" . $genreSearch . "%\""; 
    } 
     
    if(!empty($yearStartSearch)) { 
        $queryConditions[] = "m.YEAR >= " . $yearStartSearch; 
    } 
     
    if(!empty($yearEndSearch)) { 
        $queryConditions[] = "m.YEAR <= " . $yearEndSearch; 
    }     
     
    if(count($queryConditions) > 0) 
    { 
        $query .= " WHERE"; 
         
        for($i = 0; $i < count($queryConditions); $i++)  
        { 
            $query .= " " . $queryConditions[$i]; 
             
            if($i < count($queryConditions) - 1) { 
                $query .= " AND"; 
            } 
        } 
    } 

    if(isset($orderBy))  
    { 
        $query .= " ORDER BY " . $orderBy; 
         
        if(isset($orderType)) { 
            $query .= " " . $orderType; 
        } 
    } 
     
    if(isset($limit)) { 
        $query .= " LIMIT " . (($pagination - 1) * $limit) . ", " . $limit; 
    } 

    $res = $db->ExecuteSelectQueryAndFetchAll($query); 
    $count = $db->ExecuteSelectQueryAndFetchAll("SELECT FOUND_ROWS() as counter;")[0]->counter; 
    $genres = $db->ExecuteSelectQueryAndFetchAll("SELECT name FROM projectmoviegenre"); 
	
	if($user->HasAdminAccess())
	{
		echo "<div class = \"moviesAdd\"><a href = \"" . WORKSPACE_SERVERPATH . "movies/add" . "\">Lägg till film</a></div><div class = \"clear\"></div>";
	}
    $movieSearch = new CMovieSearch(array_column(array_map("get_object_vars", $genres), "name"),  
                                    array("orderBy" => $orderBy, "orderType" => $orderType, "limit" => $limit, "pagination" => $pagination, "genre" => $genreSearch,),  
                                    array("title" => $titleSearch, "genre" => $genreSearch, "yearStart" => $yearStartSearch, "yearEnd" => $yearEndSearch)); 
	$movieSearch->Render(); 
    $HTMLTable = new CHTMLTableMovie(	array_map("get_object_vars", $res), 
										array("slug" => "slug", "title" => "title", "year" => "year", "length" => "length", "director" => "director", "actors" => "actors", "plot" => "plot", "image" => "poster", "genre" => "genre", "price" => "price"), 
										$count, 
										$limit, 
										$pagination, 
										WORKSPACE_SERVERPATH . "movies/",
										array("title" => "Titel", "year" => "Årtal", "length" => "Speltid", "price" => "Pris")); 
	$HTMLTable->SetUseImageArrows(true);
    $HTMLTable->Render(); 
     
    //echo $db->Dump(); 
?> 