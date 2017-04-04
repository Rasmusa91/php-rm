<div class = "customDropdown" id = "topNavLogin">
	<ul>
		<?php if(!$user->IsLoggedIn()): ?>
		<li>
			<a href = "#">
				Login
			</a>
			<ul>
				<li>
					<?php include("loginForm.php"); ?>
				</li>
			</ul>
		</li>
		<?php else: ?>
		<li>
			<a href = "#">
				<?= ucfirst($user->GetUsername()); ?>
			</a>
			<ul>
				<li>
					<a href = "<?= WORKSPACE_SERVERPATH . "profile/"; ?>">Profil</a>
				</li>		
				<?php if($user->HasAdminAccess()): ?>
				<li>
					<a href = "<?= WORKSPACE_SERVERPATH . "managemembers/"; ?>">Hantera användare</a>
				</li>					
				<?php endif; ?>
				<li>
					<form name = "logoutForm" method = "post" <?php if($currentPage == "profile"): ?> action = "<?= WORKSPACE_SERVERPATH . "home/"; ?>" <?php endif; ?>>
						<input type = "hidden" name = "logoutSubmit">
						<a href = "logout" onclick = "document.logoutForm.submit(); return false;">Logga ut</a>
					</form>
				</li>
			</ul>
		</li>
		<?php endif; ?>
	</ul>
</div>

<div class = "clear"></div>