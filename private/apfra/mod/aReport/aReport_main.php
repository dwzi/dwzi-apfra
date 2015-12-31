<?php

if (in_array($sort, $datasql_table_fields)) {
	$datasql_table_orderby = array($sort." ".$dirsort);
}

$pages = 1;
$count = 0;
$pagination = array();

$where = "";
if ($search) {
	if (count($datasql_search_fields)) {
		$where .= " where (";
		foreach ($datasql_search_fields as $field) {
			$where .= $field." like '%".$search."%' or ";
		}
		$where = substr($where, 0, strlen($where) - 3);
		$where .= ")";
	}
}

if ($result = $db->Execute("select count(*) as anz from ".$datasql_table." ".$where)) {
	if (!$result->EOF) {
		$count = $result->fields["anz"];
	}
}
$pages = ceil($count / $datasql_perpage);

if ($page < 1) {

	$page = 1;
}
if ($page > $pages) {

	$page = $pages;
}

if ($result = $db->Execute("select id, ".implode(",",$datasql_table_fields)." from ".$datasql_table." ".$where." order by ".implode(",",$datasql_table_orderby)." limit ".(($page - 1) * $datasql_perpage).", ".$datasql_perpage)) {

	while (!$result->EOF) {

		$datatmp = array();
		$datatmp["id"] = $result->fields["id"];
		
		foreach ($datasql_table_fields as $field) {
			$field = str_replace("`","",$field);
			if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
			$datatmp[$field] = $result->fields[$field];
		}
		
		$data[] = $datatmp;

		$result->MoveNext();
	}
}

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

?>