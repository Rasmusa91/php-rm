<?php
	$genimageData = explode(",", str_replace(" ", "", $imageData->genre));
	$genre = "";

	foreach($genimageData as $genreValue)
	{
		$genre .= "<a href = \"" . WORKSPACE_SERVERPATH . "movies/?genre=" . $genreValue . "\">" . $genreValue . "</a>, ";
	}

	$genre = substr($genre, 0, -2);
?>

<div class = "darkWrapper2">
	<div class = "movieSingleTitle">
		<?= $imageData->title; ?> (<?= $imageData->year; ?>)
	</div>
	<div class = "movieSingleAdmin">
		<?php if($user->HasAdminAccess()): ?>
			<?php if($currentSubSubPage != "remove"): ?>
				<a href = "<?= WORKSPACE_SERVERPATH . "movies/" . $imageData->slug . "/remove/" ?>">Ta bort</a> | <a href = "<?= WORKSPACE_SERVERPATH . "movies/" . $imageData->slug . "/edit/" ?>">Redigera</a>
			<?php else: ?>
				Är du säker?&nbsp;
				<form method = "post" action = "<?= WORKSPACE_SERVERPATH . "movies/"; ?>">
					<input type = "hidden" name = "removeMovieID" value = "<?= $imageData->id; ?>">
					<input type = "submit" class = "button" value = "Ja" name = "removeMovieSubmit">
				</form>
				<form method = "post" action = "<?= WORKSPACE_SERVERPATH . "movies/" . $imageData->slug . "/"; ?>">
					<input type = "submit" class = "button" value = "Nej">
				</form>
			<?php endif?>
		<?php endif?>
	</div>
	<div class = "clear"></div>
	
	<div class = "darkWrapper">
		<div class = "darkWrapper2 movieSingleImage">
			<a href = "<?= WORKSPACE_SERVERPATH; ?>img/movie/<?= $imageData->poster; ?>"><img src = "<?= WORKSPACE_SERVERPATH ?>img/?src=movie/<?= $imageData->poster; ?>&width=250&height=400&crop-fit=true"></a>
		</div>
		
		<div class = "movieSingleRight">
			<div class = "movieSinglePriceGenre">
				<div class = "movieSingleGenre">
					<b><?= $imageData->length; ?> min</b> - <?= $genre; ?>
				</div>
				<div class = "movieSinglePrice">
					Pris: <?= $imageData->price; ?> SEK
				</div>
				<div class = "clear"></div>
			</div>
					
			<div class = "movieSinglePlot">
				<?= $imageData->plot; ?>
			</div>		
			
			<div class = "movieSingleExtra">
				<p><b>Director:</b> <?= $imageData->director; ?></p>
				<p><b>Stars:</b> <?= $imageData->actors; ?></p>
				
				<?php if(isset($imageData->trailer) && !empty($imageData->trailer)): ?>
					<p><b>Trailer:</b> <a href = "<?= $imageData->trailer; ?>"><?= $imageData->trailer; ?></a></p>
				<?php endif; ?>
				
				<?php if(isset($imageData->relativeLink) && !empty($imageData->relativeLink)): ?>
					<p><b>More information:</b> <a href = "<?= $imageData->relativeLink; ?>"><?= $imageData->relativeLink; ?></a></p>
				<?php endif; ?>
			</div>
		</div>
				
		<div class = "clear"></div>
		
		<?php foreach($additionalImages as $additionalImageValue): ?>
			<div class = "darkWrapper2 movieSingleAdditionalImage">
				<a href = "<?= WORKSPACE_SERVERPATH; ?>img/movie/<?= $additionalImageValue; ?>"><img src = "<?= WORKSPACE_SERVERPATH ?>img/?src=movie/<?= $additionalImageValue; ?>&width=150&height=150&crop-fit=true"></a>
			</div>
		<?php endforeach; ?>
	</div>
</div>