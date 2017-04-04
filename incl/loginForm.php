<div id = "loginForm">
	<form method = "post">
		<div>
			<p class = "noSpaces"><b>Username</b></p>
			<input name = "loginUsername">
		</div>
		<div>
			<p class = "noSpaces"><b>Password</b></p>
			<input type = "password" name = "loginPassword">
		</div>	
		
		<div>
			<p class = "topNavLoginRegister noSpaces left"><a href = "<?= WORKSPACE_SERVERPATH; ?>register/">Registrera</a></p>
			<input name = "loginSubmit" class = "button right" type = "submit">
		</div>
	</form>
	
	<?php
		global $loginErrorMessage;
	?>

	<?php if(!empty($loginErrorMessage)): ?>
	<p class = "clear error noSpaces">
		<em><?= $loginErrorMessage; ?></em>
	</p>
	<?php endif; ?>
</div>