<?php
	if(isset($_POST["loginSubmit"]))
	{
		$username = (isset($_POST["loginUsername"]) && !empty($_POST["loginUsername"]) ? $_POST["loginUsername"] : null);
		$password = (isset($_POST["loginPassword"]) && !empty($_POST["loginPassword"]) ? $_POST["loginPassword"] : null);
		$loginErrorMessage = $appelicious["user"]->LoginDatabase($username, $password, $appelicious["database"], "projectaccounts", true);
	}
	
	if(isset($_POST["logoutSubmit"]))
	{
		$appelicious["user"]->Logout();
	}	
?>