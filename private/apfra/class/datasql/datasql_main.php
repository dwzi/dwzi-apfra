<?php

$delerr = isset($_SESSION["psd"]["delerr"]) ? $_SESSION["psd"]["delerr"] : "";
$rowid = isset($_SESSION["psd"]["rowid"]) ? $_SESSION["psd"]["rowid"] : "";
$did = isset($_SESSION["psd"]["did"]) ? $_SESSION["psd"]["did"] : "";

$delarr = array();
if ($delerr) {

	$tmparr = unserialize(base64_decode($delerr));
	if (is_array($tmparr)) {
		$delarr = $tmparr;
	}
}
$smarty->assign("delarr", $delarr);
$smarty->assign("rowid", $rowid);
$smarty->assign("did", $did);

if (in_array($sort, $datasql_table_fields)) {
	$datasql_table_orderby = array($sort." ".$dirsort);
}
if (!$sort && count($datasql_table_orderby) == 1) {
	if (strpos($datasql_table_orderby[0], " ") !== false) {
		$tmparr = explode(" ", $datasql_table_orderby[0]);
		$sort = $tmparr[0];
		$dirsort = $tmparr[1];
	} else {
		$sort = $datasql_table_orderby[0];
	}
}

$datasql_field_type = array();
foreach ($datasql_edit_fields as $fields_tab) {

	$tmprow = array();
	foreach ($fields_tab["row"] as $fields_row) {

		$tmpcol = array();
		foreach ($fields_row["col"] as $fields_col) {

			if ($fields_col["type"] != "") {
				$datasql_field_type[$fields_col["field"]] = $fields_col;
			}
		}
	}
}
/*
foreach ($datasql_table_fields as $field) {
	if (!array_key_exists($field, $datasql_field_type)) {
		$datasql_field_type[$field] = array("field" => $field);
	}
}
*/
$smarty->assign("datasql_field_type", $datasql_field_type);

$pages = 1;
$count = 0;
$pagination = array();

$where = "";
if (in_array($module, array("aModule", "aTable"))) {
	$where = " where ".$module." not in ('".implode("','", $apfra_tables)."')";
} elseif (in_array($module, array("aField", "aRef"))) {
	$where = " where ((select aTable from aTable where id = refid_aTable) not in ('".implode("','", $apfra_tables)."'))";
} elseif (in_array($module, array("aRef1n"))) {
	$where = " where ( ((select aTable from aTable where id = refid_aTable_1) not in ('".implode("','", $apfra_tables)."')) and ((select aTable from aTable where id = refid_aTable_n) not in ('".implode("','", $apfra_tables)."')) )";
}

$datasql_table_fields_ref1n = array();
$datasql_table_fields_sql = array();
$datasql_table_fields_tables = array();
for ($i = 0; $i < count($datasql_table_fields); $i++) {
	if (strpos($datasql_table_fields[$i], ".") !== false) {
		$tmparr = explode(".", $datasql_table_fields[$i]);
		if (!in_array($tmparr[0], $datasql_table_fields_tables)) {
			$datasql_table_fields_tables[] = $tmparr[0];
		}
		$datasql_table_fields_sql[] = $datasql_table_fields[$i];
		if (strpos($datasql_table_fields[$i], " as ") !== false) {
			$datasql_table_fields[$i] = substr($datasql_table_fields[$i], strpos($datasql_table_fields[$i], " as ")+4, strlen($datasql_table_fields[$i]));
		}
	} elseif (substr($datasql_table_fields[$i],0,6) == "ref1n_" && substr($datasql_table_fields[$i],6,strrpos($datasql_table_fields[$i],"_")-6) == $datasql_table) {
		$datasql_table_fields_ref1n[] = $datasql_table_fields[$i];
		$apfra_db_desc[$datasql_table.".".$datasql_table_fields[$i]] = $apfra_db_desc[substr($datasql_table_fields[$i],strrpos($datasql_table_fields[$i],"_")+1,255)];
	} elseif ($datasql_table_fields[$i] != "refid_aUser_update") {
			$datasql_table_fields_sql[] = $datasql_table_fields[$i];
	}
}

if ($search) {
	if (count($datasql_search_fields)) {
		$where .= !$where ? " where (" : " and (";
		foreach ($datasql_search_fields as $field) {

			if (strpos($field,".") === false) {
				$tmpsql_sfield = $datasql_table.".".$field;
			} else {
				$tmpsql_sfield = $field;
			}

			if (in_array($field, $datasql_reference11_fields)) {

				$tmpwhere = "";
				foreach ($datasql_reference11[$field]["search"] as $svalue) {
					$tmpwhere .= $tmpsql_sfield." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$search."%') or ";
				}

				$where .= $tmpwhere;

			} elseif (in_array($field, $datasql_table_fields_ref1n)) {

				$tmp_ref1n = substr($field,strrpos($field,"_")+1,255);

				$tmpidarr = array();
				if ($sresult = $db->Execute("select id from ".$tmp_ref1n." where ".$tmp_ref1n." like '%".$search."%'")) {
					while (!$sresult->EOF) {
						$tmpidarr[] = $sresult->fields["id"];
						$sresult->MoveNext();
					}
				}

				if (count($tmpidarr)) {

					$where .= "(select count(*) from ".$field." where ".$field.".refid_".$datasql_table." = ".$datasql_table.".id and ".$field.".refid_".$tmp_ref1n." in ('".implode("','", $tmpidarr)."') > '0') or ";
				}

			} else {

				$where .= $tmpsql_sfield." like '%".$search."%' or ";
			}
		}
		if (substr($where,-3) == "or ") {
			$where = substr($where, 0, strlen($where) - 3);
			$where .= ")";
		} else {
			$where = substr($where, 0, strlen($where) - 6);
		}
	}
}

if (count($data_filter)) {

	$where .= !$where ? " where (" : " and (";
	foreach ($data_filter as $field => $value) {
		if (in_array($field, $datasql_reference11_fields)) {

			$tmpwhere = "(";
			foreach ($datasql_reference11[$field]["search"] as $svalue) {
				$tmpwhere .= $field." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$value."%') or ";
			}
			$tmpwhere = substr($tmpwhere, 0, strlen($tmpwhere) - 3);
			$tmpwhere .= ") and ";
			$where .= $tmpwhere;

		} elseif (in_array($field, $datasql_table_fields_ref1n)) {

			$tmp_ref1n = substr($field,strrpos($field,"_")+1,255);

			$tmpidarr = array();
			if ($sresult = $db->Execute("select id from ".$tmp_ref1n." where ".$tmp_ref1n." like '%".$value."%'")) {
				while (!$sresult->EOF) {
					$tmpidarr[] = $sresult->fields["id"];
					$sresult->MoveNext();
				}
			}

			if (count($tmpidarr)) {

				$where .= "(select count(*) from ".$field." where ".$field.".refid_".$datasql_table." = ".$datasql_table.".id and ".$field.".refid_".$tmp_ref1n." in ('".implode("','", $tmpidarr)."') > '0') and ";
			}

		} else {

			$tmp_field = $field;
			foreach ($datasql_table_fields_sql as $fvalue) {
				if (strpos($fvalue, " as ") !== false) {
					$tmp_field = substr($fvalue, 0, strpos($fvalue, " as "));
				}
			}

			$where .= $tmp_field." like '%".$value."%' and ";
		}
	}
	$where = substr($where, 0, strlen($where) - 4);
	$where .= ")";
}

if (count($datasql_table_fields_tables)) {

	$where .= !$where ? " where (" : " and (";
	foreach ($datasql_table_fields_tables as $tab) {
		$where .= $tab.".id = ".$datasql_table.".refid_".$tab." and ";
	}
	$where = substr($where, 0, strlen($where) - 4);
	$where .= ")";
}


if (!isset($datasql_table_sum)) {
	$datasql_table_sum = array();
}

$datasql_table_sum_data = array();
foreach ($datasql_table_fields_sql as $field) {

	if (in_array($field, $datasql_table_sum)) {

		$tmp_sum = 0;

		$query = "select sum(".$field.") as Summe from ".$datasql_table.(count($datasql_table_fields_tables) ? ",".implode(",", $datasql_table_fields_tables) : "")." ".$where;
		if ($result = $db->Execute($query)) {
			if (!$result->EOF) {
				$tmp_sum = $result->fields["Summe"];
			}
		}
		$datasql_table_sum_data[] = $tmp_sum;

	} else {

		$datasql_table_sum_data[] = "";
	}
}
$smarty->assign("datasql_table_sum", count($datasql_table_sum));
$smarty->assign("datasql_table_sum_data", $datasql_table_sum_data);

$query = "select count(*) as anz from ".$datasql_table.(count($datasql_table_fields_tables) ? ",".implode(",", $datasql_table_fields_tables) : "")." ".$where;
if ($result = $db->Execute($query)) {
	if (!$result->EOF) {
		$count = $result->fields["anz"];
	}
}
$pages = ceil($count / $datasql_perpage);
if ($pages < 1) {
	$pages = 1;
}

if ($page < 1) {
	$page = 1;
} elseif ($page > $pages) {
	$page = $pages;
}

$pagearr = array();
if ($pages <= 50) {
	$pagearr = range(1,$pages);
} else {
	$pagearr = array_merge(range(1,25), range(26,$pages,floor($pages/25)));
	if (!in_array($pages, $pagearr)) {
		$pagearr[] = $pages;
	}
}

$tmp_sql_fields = array();
for ($i=0; $i<count($datasql_table_fields_sql);$i++) {
	if (strpos($datasql_table_fields_sql[$i], ".") === false) {
		$tmp_sql_fields[] = $datasql_table.".".$datasql_table_fields_sql[$i];
	} else {
		$tmp_sql_fields[] = $datasql_table_fields_sql[$i];
	}
}

$tmp_sql_order = array();
for ($i=0; $i<count($datasql_table_orderby);$i++) {
	if (strpos($datasql_table_orderby[$i], ".") === false) {
		$tmp_sql_order[] = $datasql_table.".".$datasql_table_orderby[$i];
	} else {
		$tmp_sql_order[] = $datasql_table_orderby[$i];
	}
}

$query = "select ".$datasql_table.".id, (select aUser from aUser where id = ".$datasql_table.".refid_aUser_update) as refid_aUser, ".implode(",",$tmp_sql_fields)." from ".$datasql_table.(count($datasql_table_fields_tables) ? ",".implode(",", $datasql_table_fields_tables) : "")." ".$where." order by ".implode(",",$tmp_sql_order)." limit ".(($page - 1) * $datasql_perpage).", ".$datasql_perpage;

if ($result = $db->Execute($query)) {

	while (!$result->EOF) {

		$datatmp = array();
		$datatmp["id"] = $result->fields["id"];
		$datatmp["refid_aUser_update"] = $result->fields["refid_aUser"];

		foreach ($datasql_table_fields_sql as $field) {

			$field = str_replace("`","",$field);
			if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
			if (in_array($field, $datasql_reference11_fields)) {
				$stmp = "";
				if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."' limit 1")) {
					if (!$sresult->EOF) {
						$sfield = $datasql_reference11[$field]["field"];
						if (strpos($sfield, " as ")) $sfield = substr($sfield, strpos($sfield, " as ")+4, strlen($sfield));
						$stmp = $sresult->fields[$sfield];
					}
				}
				$datatmp[$field] = $stmp;
			} else {
//				$str = mb_convert_encoding($str, "UTF-8", "UTF-8" );
//				$datatmp[$field] = mb_convert_encoding($result->fields[$field], "UTF-8", "UTF-8" );
				$datatmp[$field] = $result->fields[$field];
			}

			if (isset($datasql_field_type[$field]["link"]) && $datasql_field_type[$field]["link"] == "#www#" && $datatmp[$field] != "") {

				if (substr($datatmp[$field],0,7) != "http://" && substr($datatmp[$field],0,8) != "https://") {
					$datatmp[$field] = "http://".$datatmp[$field];
				}
			}
		}

		if (count($datasql_table_fields_ref1n)) {

			foreach ($datasql_table_fields_ref1n as $value) {

				$tmp_ref1n = substr($value,strrpos($value,"_")+1,255);
				$tmpvaluearr = array();

				if ($sresult = $db->Execute("select ".$tmp_ref1n." from ".$tmp_ref1n." where id in (select refid_".$tmp_ref1n." from ".$value." where refid_".$datasql_table." = '".$result->fields["id"]."' group by refid_".$tmp_ref1n.") order by ".$tmp_ref1n)) {
					while (!$sresult->EOF) {
						$tmpvaluearr[] = $sresult->fields[$tmp_ref1n];
						$sresult->MoveNext();
					}
				}

				$datatmp[$value] = implode(", ", $tmpvaluearr);
			}
		}

		$data[] = $datatmp;

		$result->MoveNext();
	}
}

$pagination[] = array(
					"class" => "first".($page == 1 || $pages == 0 ? " disabled" : ""),
					"page" => 1,
					"text" => "<span class=\"glyphicon glyphicon-fast-backward\"></span>"
				);

$pagination[] = array(
					"class" => "prev".($page == 1 || $pages == 0 ? " disabled" : ""),
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
					"class" => "next".($page == $pages || $pages == 0 ? " disabled" : ""),
					"page" => ($page < $pages ? $page + 1 : $pages),
					"text" => "<span class=\"glyphicon glyphicon-forward\"></span>"
				);

$pagination[] = array(
					"class" => "last".($page == $pages || $pages == 0 ? " disabled" : ""),
					"page" => $pages,
					"text" => "<span class=\"glyphicon glyphicon-fast-forward\"></span>"
				);

$smarty->assign("count", $count);
$smarty->assign("pages", $pages);
$smarty->assign("pagearr", $pagearr);
$smarty->assign("pagination", $pagination);

?>
