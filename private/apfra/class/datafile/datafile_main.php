<?php

$pages = 1;
$count = 0;
$pagination = array();

if ($fpath != "/") {
	$fpatharr = array_merge(array(""), explode("/", substr($fpath,1,4096)));
} else {
	$fpatharr = array("");
}

$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
$filearr = glob($datafile_path.$fpath."/".$datafile_search);

$count = count($filearr);
$pages = ceil($count / $datafile_perpage);

if ($page < 1) {

	$page = 1;
}
if ($page > $pages) {

	$page = $pages;
}

$data_dir = array();
$data_file = array();

foreach ($filearr as $file) {

	if (is_dir($file)) {
		$data_dir[] = array(
				"dirname" => substr(dirname($file), strlen($datafile_path)+1, 4096),
				"filename" => basename($file)
		);
	} else {
		$data_file[] = array(
				"dirname" => substr(dirname($file), strlen($datafile_path)+1, 4096),
				"filename" => basename($file),
				"filesize" => filesize($file),
				"fileinfo" => finfo_file($fileinfo, $file)
		);
	}
}

sort($data_dir);
sort($data_file);

$pagination[] = array(
					"class" => "first".($page == 1 ? " disabled" : ""),
					"page" => 1,
					"text" => "<span class=\"glyphicon glyphicon-fast-backward\"></span>"
				);

$pagination[] = array(
					"class" => "prev".($page == 1 ? " disabled" : ""),
					"page" => ($page > 1 ? $page - 1 : 1),
					"text" => "<span class=\"glyphicon glyphicon-backward\"></span>"
				);

if ($pages > 10) {

	if ($page > 5) {
	
		$pagination[] = array(
							"class" => ($page == 1 ? "active" : ""),
							"page" => 1,
							"text" => "1"
						);
		$pagination[] = array("class" => "disabled", "page" => "", "text" => "...");
	}
	
	$tmp_start = $page > 5 ? ($page > $pages - 5 ? $pages - 4 : $page - 2) : 1;

	for ($i = $tmp_start; $i < $tmp_start + 5; $i++) {

		if ($i <= $pages) {
			
			$pagination[] = array(
								"class" => ($page == $i ? "active" : ""),
								"page" => $i,
								"text" => $i
							);
		}
	}

	if ($page <= $pages - 5) {

		if ($page < $pages - 4) {
			$pagination[] = array("class" => "disabled", "page" => "", "text" => "...");
		} else {
			$pagination[] = array(
								"class" => ($page == $pages-1 ? "active" : ""),
								"page" => $pages-1,
								"text" => $pages-1
							);
					}
		$pagination[] = array(
				"class" => ($page == $pages ? "active" : ""),
				"page" => $pages,
				"text" => $pages
		);
	}
	

} else {

	for ($i = 1; $i <= $pages; $i++) {

		$pagination[] = array(
							"class" => ($page == $i ? "active" : ""),
							"page" => $i,
							"text" => $i
						);
	}
}

$pagination[] = array(
					"class" => "next".($page == $pages ? " disabled" : ""),
					"page" => ($page < $pages ? $page + 1 : $pages),
					"text" => "<span class=\"glyphicon glyphicon-forward\"></span>"
				);

$pagination[] = array(
					"class" => "last".($page == $pages ? " disabled" : ""),
					"page" => $pages,
					"text" => "<span class=\"glyphicon glyphicon-fast-forward\"></span>"
				);
				
$smarty->assign("count", $count);
$smarty->assign("pages", $pages);
$smarty->assign("pagination", $pagination);

$smarty->assign("data_dir", $data_dir);
$smarty->assign("data_file", $data_file);

$smarty->assign("fpatharr", $fpatharr);

?>