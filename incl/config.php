<?php
	// https://github.com/Rasmusa91/php-template
	include(__DIR__ . "/../../appelicious/install.php");

	define("WORKSPACE_SERVERPATH", getServerPath("project"));
	
	$appelicious["theme"] = "project";

	$appelicious["database"]["dsn"]            = "mysql:host=localhost;dbname=raap11;";
	$appelicious["database"]["username"]       = "root";
	$appelicious["database"]["password"]       = "";
	$appelicious["database"]["driver_options"] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES \"UTF8\"");	
		
	$_SESSION["user"] = (isset($_SESSION["user"]) ? $_SESSION["user"] : new CUser());
	$appelicious["user"] = $_SESSION["user"];

	$appelicious["stylesheets"][] = WORKSPACE_SERVERPATH . "style/stylesheet.css";
	
	$appelicious["sitemap"] = array(
		"home" => array("name" => "Hem", "url" => WORKSPACE_SERVERPATH . "home/", "cat" => "menuNav"),
		"about" => array("name" => "Om", "url" => WORKSPACE_SERVERPATH . "about/", "cat" => "menuNav"),
		"movies" => array("name" => "Filmer", "url" => WORKSPACE_SERVERPATH . "movies/", "cat" => "menuNav"),
		"blog" => array("name" => "Nyheter", "url" => WORKSPACE_SERVERPATH . "news/", "cat" => "menuNav"),
		"calendar" => array("name" => "Månadens film", "url" => WORKSPACE_SERVERPATH . "calendar/", "cat" => "menuNav"),
		"games" => array("name" => "Tävling", "url" => WORKSPACE_SERVERPATH . "game/", "cat" => "menuNav"),
		"source" => array("name" => "Source", "url" => WORKSPACE_SERVERPATH . "source/", "cat" => "menuNav")
	);
	
	$appelicious["defaultPage"] = "home";
	$appelicious["selectedPage"] = (isset($appelicious["currentPage"]) ? $appelicious["currentPage"] : $appelicious["defaultPage"]);
	
	$appelicious["titleExtension"] = " - Filmdatabas";
	$appelicious["favicon"] = WORKSPACE_SERVERPATH . "img/favicon.png";

	$appelicious["content"] = array();
	$appelicious["content"]["topNav"] = new CContent("topNav", __DIR__ . "/topNav.php", true);
	$appelicious["content"]["wrapper"] = new CContent("wrapper");
	$appelicious["content"]["wrapper"]->addChildren(new CContent("header",  __DIR__ . "/header.php", true));
	$appelicious["content"]["wrapper"]->addChildren(new CContent("breadcrumbs",  __DIR__ . "/breadcrumbs.php", true));
	$appelicious["content"]["wrapper"]->addChildren(new CContent("main", __DIR__ . "/../pages/page.php", true));
	$appelicious["content"]["wrapper"]->addChildren(new CContent("footer",  __DIR__ . "/footer.php", true));
	
	$appelicious["contentTableStructure"] = array	
	(	
		"categoriesTable" => "projectnewscategories",
		"categoriesID" => "id",
		"categoriesName" => "name",
		"categoryContentRelationsTable" => "projectnewscategoryrelations",
		"categoryContentRelationsID" => "id",
		"categoryContentRelationsContentID" => "news_id",
		"categoryContentRelationsIDCategoryID" => "cat_id",
		"tableName" 	=> "projectnews", 
		"id" 			=> "id", 
		"slug" 			=> "slug",
		"title" 		=> "title",
		"content"		=> "content",
		"filter" 		=> "filter",
		"publishedDate" => "published",
		"updatedDate" 	=> "updated",
		"createdDate" 	=> "created",
		"deletedDate" 	=> "deleted",
		"author" 		=> "author_id",
		"authorName"	=> "authorName",
		"categories"	=> "categories"
	);
?>