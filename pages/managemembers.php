<?php
	if($user->HasAdminAccess())
	{
		$limit = (isset($_GET["limit"]) ? $_GET["limit"] : 8);
		$pagination = (isset($_GET["pagination"]) ? $_GET["pagination"] : 1);
		
		$db = new CDatabase($database);
		$query = "
			SELECT SQL_CALC_FOUND_ROWS username AS 'Användarnamn', first_name AS 'Förnamn', last_name AS 'Efternamn', email, country AS 'Land', access AS 'Åtkomst', deleted AS 'Bortagen' 
			FROM projectaccounts
		";
		
		if(isset($limit)) {
			$query .= " LIMIT " . (($pagination - 1) * $limit) . ", " . $limit;
		}
		
		$res = $db->ExecuteSelectQueryAndFetchAll($query);
		$count = $db->ExecuteSelectQueryAndFetchAll("SELECT FOUND_ROWS() as counter;")[0]->counter;
		
		$resArray = array_map("get_object_vars", $res);

		foreach($resArray as $resArrayKey => $resArrayValue)
		{
			foreach($resArrayValue as $resArrayValueKey => $resArrayValueValue)
			{			
				if($resArrayValueKey == "Användarnamn")
				{
					$resArray[$resArrayKey][$resArrayValueKey] = "<a href = \"" . WORKSPACE_SERVERPATH . "profile/" . $resArrayValueValue . "/\">" . $resArrayValueValue . "</a>";
					
				}
				if($resArrayValueKey == "Åtkomst")
				{
					$resArray[$resArrayKey][$resArrayValueKey] = ($resArrayValueValue > 0 ? "Admin" : "Normal");
				}
				if($resArrayValueKey == "Bortagen")
				{
					$resArray[$resArrayKey][$resArrayValueKey] = (isset($resArrayValueValue) || strtotime($resArrayValueValue) > time() ? "Ja" : "Nej");
				}			
			}
		}
		
		$HTMLTable = new CHTMLTable($resArray, $count, $limit, $pagination, WORKSPACE_SERVERPATH);
		$HTMLTable->SetUseImageArrows(true);
		$HTMLTable->Render();
	}
	else {
		include("error.php");
	}
?>