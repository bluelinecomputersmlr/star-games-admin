<?php
	require_once "support/web_browser.php";
	require_once "support/tag_filter.php";

	// Retrieve the standard HTML parsing array for later use.
	$htmloptions = TagFilter::GetHTMLOptions();

	// Retrieve a URL (emulating Firefox by default).
	$url = "https://www.superfastking.com/";
	$web = new WebBrowser();
	$result = $web->Process($url);

	// Check for connectivity and response errors.
	if (!$result["success"])
	{
		echo "Error retrieving URL.  " . $result["error"] . "\n";
		exit();
	}

	if ($result["response"]["code"] != 200)
	{
		echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
		exit();
	}

	// Get the final URL after redirects.
	$baseurl = $result["url"];

	// Use TagFilter to parse the content.
	$html = TagFilter::Explode($result["body"], $htmloptions);

	// Retrieve a pointer object to the root node.
	$root = $html->Get();

	// Find all anchor tags.
//	echo "All the URLs:\n";
	$rows = $root->Find(".text_2");
	foreach ($rows as $row)
	{
	    $da = str_replace('<img src="images/www.gif" alt="satta King">','',$row);
	    $da = str_replace('</p>','',$da);
	    $data['data'][] = $da;
// 		echo "\t" . $row->href . "\n";
// 		echo "\t" . HTTP::ConvertRelativeToAbsoluteURL($baseurl, $row->href) . "\n";
	}

// 	// Find all table rows that have 'th' tags.
// 	$rows = $root->Find("tr")->Filter("th");
// 	foreach ($rows as $row)
// 	{
// 		echo "\t" . $row->GetOuterHTML() . "\n\n";
// 	}

echo json_encode($data);
?>