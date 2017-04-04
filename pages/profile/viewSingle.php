<div class = "darkWrapper2">
	<div class = "wrapperTitle left"><?= $userUsername; ?></div>
	
	<?php if($user->HasAccess($userID)): ?>
		<div class = "right"><a href = "<?= WORKSPACE_SERVERPATH . "profile/" . $userUsername . "/edit/";?>">Redigera profil</a></div>
	<?php endif; ?>
	
	<div class = "clear"></div>
	<div class = "darkWrapper">
		<p><b>Name:</b> <?= $userFullName; ?></p>
		<p><b>Email:</b> <?= $userEmail; ?></p>
		<p><b>Land:</b> <?= $userCountry; ?></p>
		<p><b>Tillgång:</b> <?= $userAccessType; ?></p>
	</div>
</div>