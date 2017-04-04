<?php
	if(!isset($currentSubPage) && $user->IsLoggedIn())
	{
		$userID = $user->GetID();
		$userUsername = $user->GetUsername();
		$userFirstName = $user->GetFirstName();
		$userLastName = $user->GetLastName();
		$userFullName = $user->GetFullName();
		$userEmail = $user->GetEmail();
		$userCountry = $user->GetCountry();
		$userAccess = ($user->GetAccess());
		$userDeletedAt = $user->GetDeletedAt();
		$userAccessType = ($user->HasAdminAccess() ? "Admin" : "Normal");
	}
	else 
	{
		$db = new CDatabase($database); 
		$query = "
			SELECT * 
			FROM projectaccounts
			WHERE username = ?
			LIMIT 1;
		";
		$userData = $db->ExecuteSelectQueryAndFetchAll($query, array($currentSubPage)); 
		
		if(count($userData) > 0)
		{
			$userData = $userData[0];
			
			$userID = $userData->id;
			$userUsername = $userData->username;
			$userFirstName = $userData->first_name;
			$userLastName = $userData->last_name;
			$userFullName = $userData->first_name . " " . $userData->last_name;
			$userEmail = $userData->email;
			$userCountry = $userData->country;
			$userAccess = $userData->access;
			$userDeletedAt = $userData->deleted;
			$userAccessType = ($userData->access == 1 ? "Admin" : "Normal");		
		}
	}
	
	$isDeleted = false;
	if(isset($userID))
	{
		$isDeleted = (isset($userDeletedAt) || strtotime($userDeletedAt) > time());
	}
	
	if(isset($userID) && !isset($currentSubSubPage) && (!$isDeleted || $user->HasAdminAccess())) {
		include("profile/viewSingle.php");
	}
	else if(isset($userID) && $currentSubSubPage == "edit" && $user->HasAccess($userID)) {
		include("profile/edit.php");
	}
	else {
		include("error.php");
	}
?>