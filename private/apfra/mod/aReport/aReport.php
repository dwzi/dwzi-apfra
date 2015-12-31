<?php
// TODO review aReport_edit (remove datetime, etc, remove def-file)

require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module.".def.php");

$id = isset($_SESSION["psd"]["id"]) ? $_SESSION["psd"]["id"] : 0;
$page = isset($_SESSION["psd"]["p"]) ? $_SESSION["psd"]["p"] : 1;
$search = isset($_SESSION["psd"]["s"]) ? $_SESSION["psd"]["s"] : "";
$sort = isset($_SESSION["psd"]["sort"]) ? $_SESSION["psd"]["sort"] : "";
$dirsort = isset($_SESSION["psd"]["dir"]) ? $_SESSION["psd"]["dir"] : "asc";

$datasql_table = $module;

$data = array();

$datasql_fields_add_default = array();

if ($result = $db->Execute("select aTable, aField, aFieldDesc from aField, aTable where aField.refid_aTable = aTable.id")) {

	while (!$result->EOF) {

		if (substr($result->fields["aField"],0,6) == "refid_") {

			if ($sresult = $db->Execute("select aRefDesc from aRef where aRef = '".$result->fields["aField"]."' limit 1")) {

				if (!$sresult->EOF) {

					$apfra_db_desc[$result->fields["aTable"].".".$result->fields["aField"]] = $sresult->fields["aRefDesc"];
				}
			}

		} else {

			$apfra_db_desc[$result->fields["aTable"].".".$result->fields["aField"]] = $result->fields["aFieldDesc"];
		}

		$datasql_fields_add_default[$result->fields["aField"]] = "";
		$result->MoveNext();
	}
}

$datareport_def = array();
$datareport_ref = array();
if (($result = $db->Execute("show tables"))) {

	while (!$result->EOF) {

		$tmptable = $result->fields[0];

		if (!in_array($tmptable, $apfra_tables) && substr($tmptable, 0, 6) != "ref1n_") {

			$tmpfields = array();
			if (($resultf = $db->Execute("show fields from ".$tmptable))) {
			
				while (!$resultf->EOF) {
			
					$tmpfield = $resultf->fields[0];
			
					if (!in_array($tmpfield, array('id', 'aLastUpdate', 'refid_aUser_update'))) {
			
						$tmpfields[] = array('field' => $tmpfield, 'desc' => '');
						
						if (substr($tmpfield,0,6) == "refid_") {
							$datareport_ref[$tmptable][substr($tmpfield,6,255)] = $tmpfield;
						}
					}
					$resultf->MoveNext();
				}
			}
				
			$datareport_def[] = array('table' => $tmptable, 'fields' => $tmpfields);
		}
		$result->MoveNext();
	}
}

if ($action == "export" || $action == "print") {
	
	$datasql_reference1n = array();
	if ($result = $db->Execute("select (select aTable from aTable where id = refid_aTable_1) as aTable1, (select aTable from aTable where id = refid_aTable_n) as aTablen from aRef1n")) {
	
		while (!$result->EOF) {
	
			$datasql_reference1n[] = "ref1n_".$result->fields["aTable1"]."_".$result->fields["aTablen"];
			$result->MoveNext();
		}
	}	
}

switch ($action) {

case "edit":
case "delete":
case "export":
case "print":	
	require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_a_".$action.".php");
	break;
	
/* main */
case "":
default:
	require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_main.php");
	break;
}

$smarty->assign("data", $data);
$smarty->assign("id", $id);
$smarty->assign("page", $page);
$smarty->assign("search", $search);
$smarty->assign("sort", $sort);
$smarty->assign("dirsort", $dirsort);

$smarty->assign("datareport_def", $datareport_def);

$smarty->assign("datasql_table", $datasql_table);

$smarty->assign("datasql_table_fields", $datasql_table_fields);

if ($action == "edit") {

	$smarty->assign("datasql_edit_field_legend", $datasql_edit_field_legend);
	$smarty->assign("datasql_edit_fields", $datasql_edit_fields);
}

?>