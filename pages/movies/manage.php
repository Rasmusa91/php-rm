<?php
	$movieEditMode = ($currentSubSubPage == "edit");

	$movieTitle = ValidatePost("movieTitle", ValidateValue(isset($imageData->title) ? $imageData->title : null));
	$movieYear = ValidatePost("movieYear", ValidateValue(isset($imageData->year) ? $imageData->year : null));
	$movieLength = ValidatePost("movieLength", ValidateValue(isset($imageData->length) ? $imageData->length : null));
	$movieDirector = ValidatePost("movieDirector", ValidateValue(isset($imageData->director) ? $imageData->director : null));
	$movieActors = ValidatePost("movieActors", ValidateValue(isset($imageData->actors) ? $imageData->actors : null));
	$moviePlot = ValidatePost("moviePlot", ValidateValue(isset($imageData->plot) ? $imageData->plot : null));
	$moviePosterFile = ValidateFile("moviePosterFile");
	$moviePosterURL = ValidatePost("moviePosterURL", ValidateValue(isset($imageData->poster) ? (!empty($imageData->poster) ? WORKSPACE_SERVERPATH . "img/movie/" : "") . $imageData->poster : null));
	$movieTrailer = ValidatePost("movieTrailer", ValidateValue(isset($imageData->trailer) ? $imageData->trailer : null));
	$movieRelativeLink = ValidatePost("movieRelativeLink", ValidateValue(isset($imageData->relativeLink) ? $imageData->relativeLink : null));
	$movieGenre = ValidatePost("movieGenre", ValidateValue(isset($imageData->genre) ? $imageData->genre : null));
	$moviePrice = ValidatePost("moviePrice", ValidateValue(isset($imageData->price) ? $imageData->price : null));
	$movieAdditionalImagesCounter = ValidatePost("movieImageCounter", 0);
		
	$movieAdditionalImages = array();
	if(isset($additionalImages))
	{
		$i = 1;
		foreach($additionalImages as $addImgVal)
		{
			$movieAdditionalImages[$i]["url"] = WORKSPACE_SERVERPATH . "img/movie/" . $addImgVal;
			$i++;
		}
	}
	
	for($i = 1; $i <= $movieAdditionalImagesCounter; $i++)
	{
		$movieAdditionalImages[$i]["url"] = ValidatePost("manageMoviesAdditionalImageURL" . $i);
		$movieAdditionalImages[$i]["file"] = ValidateFile("manageMoviesAdditionalImageFile" . $i);
	}
	
	if(count($movieAdditionalImages) <= 0) {
		$movieAdditionalImages[1]["url"] = "";
	}
	
	$searchIMDbInput = new CFormHelperInputText("IMDb eller namn", "movieFromIMDb", ValidatePost("movieFromIMDb"), FORMHELPER_VALIDATE_NOTEMPTY);
	if(isset($_POST["movieFromIMDbSubmit"]))
	{
		if($searchIMDbInput->Validate() <= 0)
		{
			if(ValidateURL("http://www.omdbapi.com/"))
			{
				$movieFromIMDb = ValidatePost("movieFromIMDb");
				$imdbData = (json_decode(file_get_contents("http://www.omdbapi.com/?i=" . str_replace(' ', '+', $movieFromIMDb) . "&y=&plot=full&r=json")));

				if($imdbData->Response != "True" || $imdbData->Type != "movie") {
					$imdbData = (json_decode(file_get_contents("http://www.omdbapi.com/?t=" . str_replace(' ', '+', $movieFromIMDb) . "&y=&plot=full&r=json")));
				}
				
				if($imdbData->Response == "True" && $imdbData->Type == "movie")
				{
					$movieTitle = $imdbData->Title;
					$movieYear = $imdbData->Year;
					$movieLength = str_replace(" min", "", $imdbData->Runtime);
					$movieDirector = $imdbData->Director;
					$movieActors = $imdbData->Actors;
					$moviePlot = $imdbData->Plot;
					$moviePosterURL = $imdbData->Poster;
					$movieRelativeLink = "http://www.imdb.com/title/" . $imdbData->imdbID . "/";
					$movieGenre = $imdbData->Genre;
				}
				else {
					$searchIMDbInput->SetError("Ingen film hittades");
				}
			}
			else {
				$searchIMDbInput->SetError("Servern verkar nere, försök igen lite senare");
			}
		}
	}		

	$inputs = array(
		new CFormHelperInputText("Titel", "movieTitle", $movieTitle, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Årtal", "movieYear", $movieYear, FORMHELPER_VALIDATE_NOTEMPTY | FORMHELPER_VALIDATE_YEAR),
		new CFormHelperInputText("Speltid (minuter)", "movieLength", $movieLength, FORMHELPER_VALIDATE_NOTEMPTY | FORMHELPER_VALIDATE_DIGIT | FORMHELPER_VALIDATE_GREATERTHANZERO),
		new CFormHelperInputText("Regissör", "movieDirector", $movieDirector, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Skådespelare", "movieActors", $movieActors, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputTextArea("Handling", "moviePlot", $moviePlot, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputFile("Poster (lokal fil)", "moviePosterFile", $moviePosterFile["name"], FORMHELPER_VALIDATE_IMAGE),
		new CFormHelperInputText("Poster (URL)", "moviePosterURL", $moviePosterURL, FORMHELPER_VALIDATE_URL | FORMHELPER_VALIDATE_IMAGE),
		new CFormHelperInputText("Trailer", "movieTrailer", $movieTrailer),
		new CFormHelperInputText("Relativ länk", "movieRelativeLink", $movieRelativeLink),
		new CFormHelperInputText("Genre (skilj med ',')", "movieGenre", $movieGenre, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Pris (SEK)", "moviePrice", $moviePrice, FORMHELPER_VALIDATE_NOTEMPTY | FORMHELPER_VALIDATE_DIGIT | FORMHELPER_VALIDATE_GREATERTHANZERO),
		new CFormHelperInputButton(null, "movieSubmit", ($movieEditMode ? "Ändra" : "Lägg till"), null, "button")
	);	
	$form = new CFormHelper($inputs, "manageMoviesForm");
		
	$additionalImagesInputs = array();
	$movieAdditionalImagesCounter = 0;
	foreach($movieAdditionalImages as $movieAdditionalImagesValue)
	{
		$additionalImagesInputs[$movieAdditionalImagesCounter] = array(
			"file" => new CFormHelperInputFile("Poster (lokal fil)", "manageMoviesAdditionalImageFile" . ($movieAdditionalImagesCounter + 1), null, FORMHELPER_VALIDATE_IMAGE),
			"url" => new CFormHelperInputText("Poster (URL)", "manageMoviesAdditionalImageURL" . ($movieAdditionalImagesCounter + 1), $movieAdditionalImages[$movieAdditionalImagesCounter + 1]["url"], FORMHELPER_VALIDATE_URL | FORMHELPER_VALIDATE_IMAGE)
		);
		
		$movieAdditionalImagesCounter++;
	}
		
	if(isset($_POST["movieSubmit"]))
	{
		$errors = $form->Validate();

		foreach($additionalImagesInputs as $additionalImagesInputsValue)
		{
			$errors += $additionalImagesInputsValue["url"]->Validate();
			$errors += $additionalImagesInputsValue["file"]->Validate();
		}

		if($errors <= 0)
		{
			$db = new CDatabase($database);
			$sluggedTitle = slugify($movieTitle) . time();
			$additionImageCounter = 0;
			
			// Get poster
			$posterPath = "";
			$baseDir = __dir__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "movie" . DIRECTORY_SEPARATOR;
			$baseServerPath = WORKSPACE_SERVERPATH . "img/movie/";
			
			if($movieEditMode)
			{
				if((is_file($baseDir . $imageData->poster) && isset($moviePoster) && !$baseDir . $imageData->poster == $moviePoster)) {
					unlink($baseDir . $imageData->poster);
				}
				
				$removedAdditionalImages = array();
				foreach($additionalImages as $addImgVal)
				{
					$removeAddImg = true;
					
					foreach($movieAdditionalImages as $newAddImgValKey => $newAddImgVal)
					//for($i = 1; $i <= count($movieAdditionalImages); $i++)
					{
						if(isset($newAddImgVal) && !empty($newAddImgVal) && $baseServerPath . $addImgVal == $newAddImgVal["url"]) 
						{
							unset($movieAdditionalImages[$newAddImgValKey]);
							$i--;
							$removeAddImg = false;
							$additionImageCounter++;
						}
					}
					
					if($removeAddImg)
					{
						unlink($baseDir . $addImgVal);
						$removedAdditionalImages[] = $addImgVal;
					}
				}
				
				foreach($removedAdditionalImages as $raiValue)
				{
					$query = "
						DELETE FROM projectmovieimages WHERE path = ?					
					";
					$res = $db->ExecuteQuery($query, array($raiValue));
				}
				
				$query = "
					DELETE FROM projectmoviegenrerelation WHERE movie_id = ?					
				";
				$res = $db->ExecuteQuery($query, array($imageData->id));
				
				$sluggedTitle = $imageData->slug;
			}
			
			if(isset($moviePosterFile))
			{
				$posterPath = $sluggedTitle . "." . pathinfo($moviePosterFile["name"], PATHINFO_EXTENSION);
				$uploadedFile = $baseDir . $posterPath;

				move_uploaded_file($moviePosterFile["tmp_name"], $uploadedFile);
			}
			else if(isset($moviePosterURL) && !empty($moviePosterURL))
			{
				if(!isset($imageData) || !($baseServerPath . $imageData->poster == $moviePosterURL))
				{
					$posterPath = slugify($movieTitle) . time() . "." . pathinfo($moviePosterURL, PATHINFO_EXTENSION);
					$uploadedFile = $baseDir . $posterPath;
					
					file_put_contents($uploadedFile, fopen($moviePosterURL, 'r'));
				}
				else {
					$posterPath = $imageData->poster;
				}
			}

			// Add the movie to the db
			if($movieEditMode)
			{
				$query = "	UPDATE projectmovies 
							SET slug = ?, title = ?, year = ?, length = ?, director = ?, actors = ?, plot = ?, poster = ?, trailer = ?, relativeLink = ?, price = ?, updated = NOW()
							WHERE id = ?";
				$res = $db->ExecuteQuery($query, array($sluggedTitle, $movieTitle, $movieYear, $movieLength, $movieDirector, $movieActors, $moviePlot, $posterPath, $movieTrailer, $movieRelativeLink, $moviePrice, $imageData->id));
				$movieInsertID = $imageData->id;				
			}
			else 
			{
				$query = "INSERT INTO projectmovies (slug, title, year, length, director, actors, plot, poster, trailer, relativeLink, price, created, published) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
				$res = $db->ExecuteQuery($query, array($sluggedTitle, $movieTitle, $movieYear, $movieLength, $movieDirector, $movieActors, $moviePlot, $posterPath, $movieTrailer, $movieRelativeLink, $moviePrice));
				$movieInsertID = $db->GetLastID();			
			}
			
			// Get genre
			$genres = explode(",", str_replace(" ", "", strtolower($movieGenre)));
			$genres = array_unique($genres);		
			foreach($genres as $value)
			{
				$query = "	INSERT INTO projectmoviegenre (name) 
							VALUE(?)";
				$res = $db->ExecuteQuery($query, array($value));
				
				$query = "	INSERT INTO projectmoviegenrerelation (movie_id, genre_id) 
							SELECT ?, id 
							FROM projectmoviegenre 
							WHERE name = ?";
							
				$res = $db->ExecuteQuery($query, array($movieInsertID, $value));	
			}
						
			// Get additional images
			foreach($movieAdditionalImages as $movieAdditionalImagesKey => $movieAdditionalImagesValue)
			{
				$additionalImageFound = false;
				$additionImageCounter++;
				$additionalImagePath = $sluggedTitle . "-additionalImage" . $additionImageCounter;
				
				if(isset($movieAdditionalImagesValue["file"]))
				{
					$additionalImagePath .= "." . pathinfo($movieAdditionalImagesValue["file"]["name"], PATHINFO_EXTENSION);
					$uploadedFile = $baseDir . $additionalImagePath;

					move_uploaded_file($movieAdditionalImagesValue["file"]["tmp_name"], $uploadedFile);
					//$movieAdditionalImages[$movieAdditionalImagesKey]["url"] = $uploadedFile;
					
					$additionalImageFound = true;
				}
				else if(isset($movieAdditionalImagesValue["url"]) && !empty($movieAdditionalImagesValue["url"]))
				{
					$additionalImagePath .= "." . pathinfo($movieAdditionalImagesValue["url"], PATHINFO_EXTENSION);
					$uploadedFile = $baseDir . $additionalImagePath;
					
					file_put_contents($uploadedFile, fopen($movieAdditionalImagesValue["url"], 'r'));
					
					$additionalImageFound = true;
				}
				
				if($additionalImageFound)
				{
					$query = "INSERT INTO projectmovieimages (movie_id, path) VALUES(?, ?)";
					$res = $db->ExecuteQuery($query, array($movieInsertID, $additionalImagePath));	
				}				
			}
			
			echo "Omdirigerar till <a href = \"" . WORKSPACE_SERVERPATH . "movies/" . $sluggedTitle . "\"/>" . WORKSPACE_SERVERPATH . "movies/" . $sluggedTitle . "</a>";
			echo "<script>window.location.replace(\"" . WORKSPACE_SERVERPATH . "movies/" . $sluggedTitle . "/\");</script>";
		}
	}
?> 

<div class = "manageMoviesWrapper">
	<div class = "manageMoviesTitle"><?= ($movieEditMode ? "Ändra" : "Lägg till") ?> film</div>
	<div class = "manageMoviesInnerWrapper">
		<form method = "post" enctype="multipart/form-data">
			<?= $form->Render(); ?>

			<div class = "manageMovieIMDBScannerWrapper">
				<div class = "manageMoviesTitle">
					Hämta från IMDb
				</div>
				
				<div class = "manageMovieIMDBScannerWrapperInner">
					<form method = "post">
						<?= $searchIMDbInput->Render(); ?>
						<input name = "movieFromIMDbSubmit" type = "submit" value = "Sök" class = "button">
					</form>
				</div>
				
				<div class = "manageMoviesTitle">
					Lägg till fler bilder
				</div>
				
				<div class = "manageMovieIMDBScannerWrapperInner">
					<div id = "manageMovieAdditionalImages">
						<?php $newAddImgCounter = 0; ?>
						<?php foreach($additionalImagesInputs as $additionalImagesInputsValue): ?>
						<?php $newAddImgCounter++; ?>
							<p>Bild <?= $newAddImgCounter; ?></p>
							<div class = "manageMovieIMDBScannerWrapperInnerInner">
								<?php
									echo $additionalImagesInputsValue["file"]->Render();
									echo $additionalImagesInputsValue["url"]->Render();
								?>								
							</div>
						<?php endforeach; ?>
					</div>
					<input type = "hidden" id = "movieImageCounter" name = "movieImageCounter" value = "<?= count($movieAdditionalImages) ?>">
					<a class = "manageMovieIMDBScannerWrapperInnerInner" href = "AddImage" onclick = "AddImageContainer(); return false;">+</a>
					<div class = "clear"></div>
				</div>	
			</div>
		</form>
		<div class = "clear"></div>
	</div>
</div>

<script>
	var imageCounter = document.getElementById("movieImageCounter").value;
	
	function AddImageContainer()
	{
		imageCounter++;		
		document.getElementById("movieImageCounter").value = imageCounter;
		
		var imageContainer = document.createElement("div");
		imageContainer.innerHTML = '<p>Bild ' + imageCounter + '</p><div class = "manageMovieIMDBScannerWrapperInnerInner"><p>Lokal fil</p><input type = "file" name = "manageMoviesAdditionalImageFile' + imageCounter + '"><p>URL</p><input name = "manageMoviesAdditionalImageURL' + imageCounter + '"></div>';
		document.getElementById("manageMovieAdditionalImages").appendChild(imageContainer);
		document.getElementById("manageMovieAdditionalImages").appendChild(imageContainer);
	}
</script>
