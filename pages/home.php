<?php
	$db = new CDatabase($database); 

	// Movies
	$query = "
		SELECT * 
		FROM projectmovies
		WHERE published <= NOW() AND (deleted IS NULL OR deleted >= NOW())
		ORDER BY published DESC
		LIMIT 4;
	";
		
	$movies = $db->ExecuteSelectQueryAndFetchAll($query); 
	
	// Movies genres
	$query = "
		SELECT * 
		FROM projectmoviegenre;
	";
		
	$moviesgenres = $db->ExecuteSelectQueryAndFetchAll($query); 	
	
	// News
	$query = "
		SELECT * 
		FROM projectnews
		WHERE published <= NOW() AND (deleted IS NULL OR deleted >= NOW())
		ORDER BY published DESC
		LIMIT 4;
	"; 

	$news = $db->ExecuteSelectQueryAndFetchAll($query); 
	
	// News categories
	$query = "
		SELECT * 
		FROM projectnewscategories;
	";
	$newscategories = $db->ExecuteSelectQueryAndFetchAll($query); 
	
	// Most popuular and rented
	$query = "
		SELECT * 
		FROM projectmovies
		WHERE published <= NOW() AND (deleted IS NULL OR deleted >= NOW())
		ORDER BY RAND()
		LIMIT 2;
	";
		
	$randomMovies = $db->ExecuteSelectQueryAndFetchAll($query); 
?>

<div id = "home">
	<div class = "darkWrapper2">
		<div class = "wrapperTitle left">Filmer</div>
		<div class = "genres">
			<?php 
				$output = "";
				foreach($moviesgenres as $moviesgenresValue)
				{
					$output .= "<a href = \"" . WORKSPACE_SERVERPATH . "movies/?genre=" .$moviesgenresValue->name . "\">" . $moviesgenresValue->name. "</a>, ";
				}
				$output = substr($output, 0, -2);
				echo $output;
			?>

		</div>
		<div class = "clear"></div>
		<div class = "darkWrapper">
			<?php foreach($movies as $moviesValue): ?>
				<div class = "darkWrapper2 movieSingleImage">
					<div class = "moviePrice"><?= $moviesValue->price ?>:-</div>
					<a href = "<?= WORKSPACE_SERVERPATH; ?>movies/<?= $moviesValue->slug; ?>"/><img src = "<?= WORKSPACE_SERVERPATH ?>img/?src=movie/<?= $moviesValue->poster; ?>&width=222&height=300&crop-fit=true"></a>
				</div>		
			<?php endforeach; ?>
			<div class = "clear"></div>
		</div>	
			
		<div class = "view">
			<a href = "<?= WORKSPACE_SERVERPATH; ?>movies/">Visa alla filmer</a> »
		</div>
	</div>
	
	<div class = "seperator"></div>
	
	<div class = "darkWrapper2">
		<div class = "wrapperTitle left">SENASTE NYHETERNA</div>
		<div class = "genres">
			<?php 
				$output = "";
				foreach($newscategories as $newscategoriesValue)
				{
					$output .= "<a href = \"" . WORKSPACE_SERVERPATH . "news/?c=" .$newscategoriesValue->name . "\">" . $newscategoriesValue->name. "</a>, ";
				}
				$output = substr($output, 0, -2);
				echo $output;
			?>

		</div>	
		<div class = "clear"></div>
		<div class = "darkWrapper">
			<?php foreach($news as $newsValue): ?>
				<div class = "darkWrapper2 newsInnerWrapper">
					<div class = "newsTitle"><a href = "<?= WORKSPACE_SERVERPATH; ?>news/<?= $newsValue->slug; ?>"/"><?= ValidateValue($newsValue->title) ?></a> »</div>
					<div class = "newsContent"><?= ValidateValue($newsValue->content) ?></div>
				</div>		
			<?php endforeach; ?>
			<div class = "clear"></div>
		</div>		
			
		<div class = "view">
			<a href = "<?= WORKSPACE_SERVERPATH; ?>news/">Visa alla Nyheter</a> »
		</div>
	</div>
	
	<div class = "seperator"></div>
	
	<div class = "darkWrapper2 lastRented">
		<div class = "wrapperTitle">Senaste hyrda film</div>
		<div class = "darkWrapper">
			<div class = "darkWrapper2 movieSingle">
				<div class = "moviePrice"><?= $randomMovies[1]->price ?>:-</div>
				<a href = "<?= WORKSPACE_SERVERPATH; ?>movies/<?= $randomMovies[0]->slug; ?>"/><img src = "<?= WORKSPACE_SERVERPATH ?>img/?src=movie/<?= $randomMovies[0]->poster; ?>&width=447&height=600&crop-fit=true&type=jpg&sharpen&quality=100"></a>
			</div>
		</div>
	</div>
	
	<div class = "darkWrapper2 mostPopular">
		<div class = "wrapperTitle">Mest populära film</div>
		<div class = "darkWrapper">
			<div class = "darkWrapper2 movieSingle">
				<div class = "moviePrice"><?= $randomMovies[1]->price ?>:-</div>
				<a href = "<?= WORKSPACE_SERVERPATH; ?>movies/<?= $randomMovies[1]->slug; ?>"/><img src = "<?= WORKSPACE_SERVERPATH ?>img/?src=movie/<?= $randomMovies[1]->poster; ?>&width=447&height=600&crop-fit=true&type=jpg&sharpen&quality=100"></a>
			</div>	
		</div>
	</div>	
	
	<div class = "clear"></div>
</div>