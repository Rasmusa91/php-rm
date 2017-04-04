<?php
	$registerUsername = ValidatePost("registerUsername");
	$registerPassword = ValidatePost("registerPassword");
	$registerRePassword = ValidatePost("registerRePassword");
	$registerFirstName = ValidatePost("registerFirstName");
	$registerLastName = ValidatePost("registerLastName");
	$registerEmail = ValidatePost("registerEmail");
	$registerCountry = ValidatePost("registerCountry");
	
	$inputs = array
	(
		new CFormHelperInputText("Användarnamn", "registerUsername", $registerUsername, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputPassword("Lösenord", "registerPassword", $registerPassword, FORMHELPER_VALIDATE_NOTEMPTY),
		$retypePasswordInput = new CFormHelperInputPassword("Ange lösenordet igen", "registerRePassword", $registerRePassword, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Förnamn", "registerFirstName", $registerFirstName, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Efternamn", "registerLastName", $registerLastName, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Email", "registerEmail", $registerEmail, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputText("Land", "registerCountry", $registerCountry, FORMHELPER_VALIDATE_NOTEMPTY),
		new CFormHelperInputButton(null, "registerSubmit", "Registrera", $registerCountry, "button")
	);	
	
	$form = new CFormHelper($inputs, "registerUserForm");
	
	if(isset($_POST["registerSubmit"]))
	{
		$errors = 0;
		
		if($registerPassword != $registerRePassword) {
			$errors++;
			$retypePasswordInput->SetError("Lösenorden matchar inte");
		}
		
		$errors += $form->Validate();
		
		if($errors <= 0) 
		{
			$db = new CDatabase($database);
			$query = "	INSERT INTO projectaccounts 
						(username, password, first_name, last_name, email, country) 
						VALUES(?, ?, ?, ?, ?, ?)";
			$res = $db->ExecuteQuery($query, array($registerUsername, sha1($registerPassword), $registerFirstName, $registerLastName, $registerEmail, $registerCountry));
			
			if($res) {
				$user->LoginDatabase($registerUsername, $registerPassword, $database, "projectAccounts");
				echo "<script>location.replace('" . WORKSPACE_SERVERPATH . "profile/')</script>";
			}
			else {
				$additionalErrors = "<p>Användarnamnet eller Email-addressen är inte unik</p>";
			}
		}
	}
?>

<div class = "darkWrapper2 formStyle">
	<div class = "wrapperTitle">Registrera</div>
	<div class = "darkWrapper">
		<form method = "post">
			<?= $form->Render(); ?>
		</form>
		<div class = "clear"></div>
	</div>
	<?= (isset($additionalErrors) ? $additionalErrors : ""); ?>
</div>