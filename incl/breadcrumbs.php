<?php
	$crumbs = GetDefaultCrumbs();

	$img = "<a href = \"" . WORKSPACE_SERVERPATH . "\"><img src = \"" . WORKSPACE_SERVERPATH . "img/?src=breadcrumb.png\"></a>";
	$breadcrumbs = new CBreadcrumbs($crumbs, $img);
	echo $breadcrumbs->Render();

	/**
	* Create some default breadcrumbs (page, subpage, subsubpage)
	*/
	function GetDefaultCrumbs()
	{
		global $selectedPage;
		global $currentSubPage;
		global $currentSubSubPage;
	
		$crumbs = array();

		if($selectedPage != null) {
			$crumbs[] = array("name" => ucfirst($selectedPage), "url" => WORKSPACE_SERVERPATH . $selectedPage, "current" => ($currentSubPage == null && $currentSubSubPage == null ? "true" : null));
		}
		
		if($currentSubPage != null) {
			$crumbs[] = array("name" => ucfirst($currentSubPage), "url" => WORKSPACE_SERVERPATH . $selectedPage . "/" . $currentSubPage, "current" => ($currentSubSubPage == null ? "true" : null));
		}
		
		if($currentSubSubPage != null) {
			$crumbs[] = array("name" => ucfirst($currentSubSubPage), "url" => WORKSPACE_SERVERPATH . $selectedPage . "/" . $currentSubPage . "/" . $currentSubSubPage, "current" => "true");
		}
	
		return $crumbs;
	}
