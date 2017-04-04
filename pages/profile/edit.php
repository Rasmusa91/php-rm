<?php
	$db = new CDatabase($database);
	
	//new contact
	$editProfileUsername = ValidatePost("editProfileUsername", ValidateValue(isset($userUsername) ? $userUsername : null));
	$editProfileFirstName = ValidatePost("editProfileFirstName", ValidateValue(isset($userFirstName) ? $userFirstName : null));
	$editProfileLastName = ValidatePost("editProfileLastName", ValidateValue(isset($userLastName) ? $userLastName : null));
	$editProfileEmail = ValidatePost("editProfileEmail", ValidateValue(isset($userEmail) ? $userEmail : null));
	$editProfileCountry = ValidatePost("editProfileCountry", ValidateValue(isset($userCountry) ? $userCountry : null));
	$editProfileContactOldPassword = ValidatePost("editProfileContactOldPassword");
		
	$inputs = array(
		new CFormHelperInputText("Användarnamn", "editProfileUsername", $editProfileUsername, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Förnamn", "editProfileFirstName", $editProfileFirstName, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Efternamn", "editProfileLastName", $editProfileLastName, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Email", "editProfileEmail", $editProfileEmail, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Country", "editProfileCountry", $editProfileCountry, FORMHELPER_VALIDATE_NOTEMPTY),
		$editProfileContactOldPWInput = new CFormHelperInputPassword("Nuvarande lösenord för kontroll", "editProfileContactOldPassword", $editProfileContactOldPassword, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputButton(null, "editUserSubmit", "Ändra", null, "button")
	);	
	$contactInfoForm = new CFormHelper($inputs, "editUserForm");
	
	// new pw
	$editProfileNewPassword = ValidatePost("editProfileNewPassword");
	$editProfileNewPasswordRepeat = ValidatePost("editProfileNewPasswordRepeat");
	$editProfilePasswordOldPassword = ValidatePost("editProfilePasswordOldPassword");
	
	$inputs = array(
		new CFormHelperInputPassword("Nytt lösenord", "editProfileNewPassword", $editProfileNewPassword, FORMHELPER_VALIDATE_NOTEMPTY),
		$editPasswordRepeatInput = new CFormHelperInputPassword("Upprepa lösenordet", "editProfileNewPasswordRepeat", $editProfileNewPasswordRepeat, FORMHELPER_VALIDATE_NOTEMPTY),
		$editProfilePasswordOldPWInput = new CFormHelperInputPassword("Nuvarande lösenord för kontroll", "editProfilePasswordOldPassword", $editProfilePasswordOldPassword, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputButton(null, "editUserPasswordSubmit", "Ändra", null, "button")
	);	
	$changePasswordForm = new CFormHelper($inputs, "editUserPasswordForm");	
	
	// edit access
	$editProfileAccessNewAccess = ValidatePost("editProfileAccessNewAccess", ValidateValue(isset($userAccess) ? $userAccess : null));
	$editProfileAccessOldPassword = ValidatePost("editProfileAccessOldPassword");
	
	$inputs = array(
		new CFormHelperInputText("Åtkomst (0 = normal, 1 = admin)", "editProfileAccessNewAccess", $editProfileAccessNewAccess, FORMHELPER_VALIDATE_NOTEMPTY),
		$editProfileAccesstOldPWInput = new CFormHelperInputPassword("Nuvarande lösenord för kontroll", "editProfileAccessOldPassword", $editProfileAccessOldPassword, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputButton(null, "editUserAccessSubmit", "Ändra", null, "button")
	);	
	$changeAccessForm = new CFormHelper($inputs, "changeAccessForm");		
	
	// remove
	$editProfileRemoveOldPassword = ValidatePost("editProfileRemoveOldPassword");
	
	$inputs = array(
		$editProfileRemoveOldPWInput = new CFormHelperInputPassword("Nuvarande lösenord för kontroll", "editProfileRemoveOldPassword", $editProfileRemoveOldPassword, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputButton(null, "editUserDeleteSubmit", (!$isDeleted ? "Ta bort" : "Återställ"), null, "button")
	);	
	$deleteUserForm = new CFormHelper($inputs, "editUserRemoveForm");
	
	//check new contact info
	if(isset($_POST["editUserSubmit"]))
	{
		$errors = $contactInfoForm->Validate();
		
		if($errors <= 0)
		{
			if(sha1($editProfileContactOldPassword) == $user->GetPassword()) 
			{
				$db = new CDatabase($database);
				$query = "	UPDATE projectaccounts 
							SET username = ?, first_name = ?, last_name = ?, email = ?, country = ?
							WHERE id = ?";
				$res = $db->ExecuteQuery($query, array($editProfileUsername, $editProfileFirstName, $editProfileLastName, $editProfileEmail, $editProfileCountry, $userID));
				
				if($res) 
				{
					if($user->GetID() == $userID)
					{
						$user->LoginDatabase($editProfileUsername, $editProfileContactOldPassword, $database, "projectAccounts");
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/')</script>";
					}
					else 
					{
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/" . $editProfileUsername . "/')</script>";				
					}
				}
				else {
					$editProfileAdditionalError = "<p>Användarnamnet och email-adressen måste vara unik</p>";
				}
			}
			else {
				$editProfileContactOldPWInput->SetError("Lösenordet matchar inte ditt nuvarande lösenord");
			}
		}
	}
	
	//check new password
	if(isset($_POST["editUserPasswordSubmit"]))
	{
		$errors = $changePasswordForm->Validate();
		
		if($editProfileNewPassword != $editProfileNewPasswordRepeat)
		{
			$errors++;
			$editPasswordRepeatInput->SetError("Lösenorden matchar inte");
		}
		
		if($errors <= 0)
		{
			if(sha1($editProfilePasswordOldPassword) == $user->GetPassword()) 
			{
				$db = new CDatabase($database);
				$query = "	UPDATE projectaccounts 
							SET password = ?
							WHERE id = ?";
				$res = $db->ExecuteQuery($query, array(sha1($editProfileNewPassword), $userID));
				
				if($res) 
				{
					if($user->GetID() == $userID)
					{
						$user->LoginDatabase($user->GetUsername(), $editProfileNewPassword, $database, "projectAccounts");
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/')</script>";
					}
					else 
					{
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/" . $userUsername . "/')</script>";				
					}
				}
			}
			else {
				$editProfilePasswordOldPWInput->SetError("Lösenordet matchar inte ditt nuvarande lösenord");
			}
		}
	}	
	
	//check edit access
	if(isset($_POST["editUserAccessSubmit"]))
	{
		$errors = $changeAccessForm->Validate();
		
		if($errors <= 0)
		{
			if(sha1($editProfileAccessOldPassword) == $user->GetPassword())
			{
				$db = new CDatabase($database);
				$query = "	UPDATE projectaccounts 
							SET access = ?
							WHERE id = ?";
				$res = $db->ExecuteQuery($query, array($editProfileAccessNewAccess, $userID));
				
				if($res) 
				{
					if($user->GetID() == $userID)
					{
						$user->LoginDatabase($user->GetUsername(), $user->GetPassword(), $database, "projectAccounts");
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/')</script>";
					}
					else 
					{
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/" . $userUsername . "/')</script>";				
					}
				}
			}
			else {
				$editProfileAccesstOldPWInput->SetError("Lösenordet matchar inte ditt nuvarande lösenord");
			}
		}
	}
		
	//check edit access
	if(isset($_POST["editUserDeleteSubmit"]))
	{
		$errors = $deleteUserForm->Validate();
		
		if($errors <= 0)
		{
			if(sha1($editProfileRemoveOldPassword) == $user->GetPassword())
			{
				$db = new CDatabase($database);
				if(!$isDeleted)
				{
					$query = "	UPDATE projectaccounts 
								SET deleted = NOW()
								WHERE id = ?";
				}
				else
				{
					$query = "	UPDATE projectaccounts 
								SET deleted = NULL
								WHERE id = ?";
				}
				$res = $db->ExecuteQuery($query, array($userID));
				
				if($res) 
				{
					echo $res . "aa";
					
					if($user->GetID() == $userID)
					{
						$user->Logout();
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "')</script>";
					}
					else 
					{
						echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/')</script>";				
					}
				}
			}
			else {
				$editProfileRemoveOldPWInput->SetError("Lösenordet matchar inte ditt nuvarande lösenord");
			}
		}
	}	
?>

<div class = "darkWrapper2 formStyle">
	<div class = "wrapperTitle">Redigera kontaktuppgifter</div>
	<div class = "darkWrapper">
		<form method = "post">
			<?= $contactInfoForm->Render(); ?>
		</form>
	</div>
	<?= (isset($editProfileAdditionalError) ? $editProfileAdditionalError : ""); ?>
</div>

<div class = "darkWrapper2 formStyle">
	<div class = "wrapperTitle">Ändra lösenord</div>
	<div class = "darkWrapper">
		<form method = "post">
			<?= $changePasswordForm->Render(); ?>
		</form>
	</div>
	<?= (isset($editProfileAdditionalError) ? $editProfileAdditionalError : ""); ?>
</div>

<?php if($user->HasAdminAccess()): ?>
	<div class = "darkWrapper2 formStyle">
		<div class = "wrapperTitle">Ändra åtkomst</div>
		<div class = "darkWrapper">
			<form method = "post">
				<?= $changeAccessForm->Render(); ?>
			</form>
		</div>
		<?= (isset($editProfileAdditionalError) ? $editProfileAdditionalError : ""); ?>
	</div>
<?php endif; ?>

<div class = "darkWrapper2 formStyle">
	<?php if(!$isDeleted): ?>
		<div class = "wrapperTitle">Ta bort profil</div>
	<?php else: ?>		
		<div class = "wrapperTitle">Återställ profil</div>
	<?php endif; ?>	
	
	<div class = "darkWrapper">
		<form method = "post">
			<?= $deleteUserForm->Render(); ?>
		</form>
	</div>
	<?= (isset($editProfileAdditionalError) ? $editProfileAdditionalError : ""); ?>
</div>