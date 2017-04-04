<?php
	$newsCategory = ValidateGet("c", "");
	$db = new CDatabase($database);

	$query = "	SELECT news.* FROM 
				(
					SELECT projectnews.*, projectaccounts.username AS authorName, GROUP_CONCAT(\" \", projectnewscategories.name) AS categories
					FROM projectnews
					LEFT OUTER JOIN projectnewscategoryrelations
					ON projectnews.id = projectnewscategoryrelations.news_id
					LEFT OUTER JOIN projectnewscategories
					ON projectnewscategories.id = projectnewscategoryrelations.cat_id
					JOIN projectaccounts
					ON projectaccounts.id = projectnews.author_id
					WHERE projectnews.published <= ? AND (projectnews.deleted IS NULL OR projectnews.deleted >= ?)
					GROUP BY projectnews.id
					ORDER BY published DESC
				) news
				WHERE categories LIKE ? OR ? = ?
				;";	

	$params = array("NOW()", "NOW()", "%" . $newsCategory . "%", $newsCategory, "");
	$res = $db->ExecuteSelectQueryAndFetchAll($query, $params);
	$res = array_map("get_object_vars", $res);
		
	$posts = array();
	foreach($res as $value) 
	{
		$posts[] = new CBlog($value, $database, $contentTableStructure);
	}

	$contentViewer = new CContentViewer($database, $posts, WORKSPACE_SERVERPATH . "news/", $user);
?>

<div id = "blog">
	<?php
		$content = "";
		
		if(isset($currentSubPage) && !empty($currentSubPage)) 
		{			
			if(isset($currentSubSubPage) && !empty($currentSubSubPage)) 
			{
				if($user->HasAdminAccess($contentViewer->FindPost($currentSubPage)->mAuthor) && $currentSubSubPage == "remove") {
					$content = $contentViewer->ShowSingle($currentSubPage, true);
				}
				else if($user->HasAdminAccess($contentViewer->FindPost($currentSubPage)->mAuthor) && $currentSubSubPage == "edit") {
					$content = $contentViewer->Edit($currentSubPage);
				}
			}
			else if($currentSubPage == "add")
			{
				if($user->HasAdminAccess())
				{
					$content = $contentViewer->Add(function($pData) { 
						global $database;
						global $contentTableStructure;
						global $user;

						return CBlog::AddPost(array_merge($pData, array("type" => "post", "author" => $user->GetID())), $database, $contentTableStructure); 
					});
				}
			}
			else {
				$content = $contentViewer->ShowSingle($currentSubPage);
			}	

			if(empty($content))
			{
				include("error.php");
			}
		}
		else {
			$content = $contentViewer->ShowAll();
		}
		
		echo $content;
	?>
</div>