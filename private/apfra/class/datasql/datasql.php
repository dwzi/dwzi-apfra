<?php

$id = isset($_SESSION["psd"]["id"]) ? $_SESSION["psd"]["id"] : 0;

if (!$perpage) {
	$perpage = isset($datasql_perpage) && $datasql_perpage ? $datasql_perpage : 10;
} else {
	$datasql_perpage = $perpage;
}

if ($datasql_perpage < 1) $datasql_perpage = 1;
if ($datasql_perpage > 50) $datasql_perpage = 50;
if (!in_array($datasql_perpage, array(10,20,30,40,50))) $datasql_perpage = 10;

$data_filter = array();
$tmparr = array();
foreach ($_SESSION["psd"] as $key => $value) {
	if (substr($key,0,3) == "ff_") {
		if ($value) {
			$data_filter[substr($key,3,255)] = $value;
			$tmparr[] = $key."=".$value;
		}
	}
}
$smarty->assign("data_filter_url", (count($tmparr) ? "&".implode("&", $tmparr) : ""));
$smarty->assign("data_filter", $data_filter);

$datasql_table = $module;

$data = array();

$datasql_reference1n = array();

$datasql_fields_add_default = array();

if ($result = $db->Execute("select aField, aFieldDesc from aField, aTable where aField.refid_aTable = aTable.id and aTable = '".$datasql_table."'")) {

	while (!$result->EOF) {

		if (substr($result->fields["aField"],0,6) == "refid_") {

			if ($sresult = $db->Execute("select aRefDesc from aRef where aRef = '".$result->fields["aField"]."' limit 1")) {

				if (!$sresult->EOF) {

					$apfra_db_desc[$datasql_table.".".$result->fields["aField"]] = $sresult->fields["aRefDesc"];
				}
			}

		} else {

			$apfra_db_desc[$datasql_table.".".$result->fields["aField"]] = $result->fields["aFieldDesc"];
		}

		$datasql_fields_add_default[$result->fields["aField"]] = "";
		$result->MoveNext();
	}
}
$apfra_db_desc[$datasql_table.".id"] = "ID";
$apfra_db_desc[$datasql_table.".refid_aUser_update"] = "Benutzer";
$datasql_fields_add_default["id"] = "";
$datasql_fields_add_default["refid_aUser_update"] = "";

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

if ($action == "edit") {

	$tmpef = array();
	foreach ($datasql_edit_fields as $fields_tab) {

		$tmprow = array();
		foreach ($fields_tab["row"] as $fields_row) {

			$tmpcol = array();
			foreach ($fields_row["col"] as $fields_col) {

				if ($fields_col["type"] == "reference1n") {
					$tmparr = explode("_",substr($fields_col["field"], 6, 255));
					if ($apfra_rights[$tmparr[0]]["sum"] && $apfra_rights[$tmparr[1]]["sum"]) {
						$tmpcol[] = $fields_col;
					}
				} else {
					$tmpcol[] = $fields_col;
				}
			}
			if (count($tmpcol)) {
				$fields_row["col"] = $tmpcol;
				$tmprow[] = $fields_row;
			}
		}
		if (count($tmprow)) {
			$fields_tab["row"] = $tmprow;
			$tmpef[] = $fields_tab;
		}
	}
	$datasql_edit_fields = $tmpef;

	if ($result = $db->Execute("select (select aTable from aTable where id = refid_aTable_1) as aTable1, (select aTable from aTable where id = refid_aTable_n) as aTablen from aRef1n")) {

		while (!$result->EOF) {

			if (isset($apfra_rights[$result->fields["aTable1"]]) && $apfra_rights[$result->fields["aTable1"]]["sum"] && isset($apfra_rights[$result->fields["aTablen"]]) && $apfra_rights[$result->fields["aTablen"]]["sum"]) {

				$datasql_reference1n["ref1n_".$result->fields["aTable1"]."_".$result->fields["aTablen"]] = array();
			}
			$result->MoveNext();
		}
	}
}

switch ($action) {

case "word":
case "excel":
case "edit":
case "delete":
case "export":
case "print":
case "json":
case "file":
	if (file_exists(DEF_PATH_PRIVATE."mod".DS.$module.DS.$module."_a_".$action."_pre.inc.php")) {
		require(DEF_PATH_PRIVATE."mod".DS.$module.DS.$module."_a_".$action."_pre.inc.php");
	}
	require(DEF_PATH_PRIVATE."apfra".DS."class".DS."datasql".DS."datasql_a_".$action.".php");
	break;

/* main */
case "":
default:
	if (file_exists(DEF_PATH_PRIVATE."mod".DS.$module.DS.$module."_main_pre.inc.php")) {
		require(DEF_PATH_PRIVATE."mod".DS.$module.DS.$module."_main_pre.inc.php");
	}
	require(DEF_PATH_PRIVATE."apfra".DS."class".DS."datasql".DS."datasql_main.php");
	if (file_exists(DEF_PATH_PRIVATE."mod".DS.$module.DS.$module."_main_post.inc.php")) {
		require(DEF_PATH_PRIVATE."mod".DS.$module.DS.$module."_main_post.inc.php");
	}
	break;
}

$smarty->assign("data", $data);
$smarty->assign("id", $id);

$smarty->assign("datasql_table", $datasql_table);

$smarty->assign("datasql_table_fields", $datasql_table_fields);

$smarty->assign("datasql_reference1n", $datasql_reference1n);

$smarty->assign("datasql_edit_field_legend", $datasql_edit_field_legend);
if ($action == "edit") {
	$smarty->assign("datasql_edit_fields", $datasql_edit_fields);
}

?>
