<?php

$datetime = date("Y-m-d H:i:s");
$subaction = isset($_SESSION["psd"]["sa"]) && $_SESSION["psd"]["sa"] ? $_SESSION["psd"]["sa"] : "";

switch ($subaction) {

/*
checkField 3: fieldTypes in database and apfra-table different; update apfra
 */
case "ua":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfield = isset($_SESSION["psd"]["dfield"]) && $_SESSION["psd"]["dfield"] ? $_SESSION["psd"]["dfield"] : "";
	$tmpftype = isset($_SESSION["psd"]["dftype"]) && $_SESSION["psd"]["dftype"] ? $_SESSION["psd"]["dftype"] : "";
	
	$tmpftypeid = 0;
	$query = "select id from aFieldType where aFieldType = '".$tmpftype."'";
	if ($result = $db->Execute($query)) {
		if (!$result->EOF) {
			$tmpftypeid = $result->fields["id"];
		}
	}
	if ($tmpftypeid == 0) {
		$query = "insert into aFieldType (aFieldType, aLastUpdate, refid_aUser_update) values ('".$tmpftype."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
		if ($result = $db->Execute($query)) {
			$tmpftypeid = $db->Insert_ID();
		}		
	}
	
	$query = "update aField set refid_aFieldType = '".$tmpftypeid."', aLastUpdate = '".$datetime."', refid_aUser_update = '".$_SESSION["psu"]["id"]."' where aField = '".$tmpfield."' and refid_aTable = (select id from aTable where aTable = '".$tmptable."')";
	$db->Execute($query);

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();
	
	reload_page("?mod=".$module);		
	break;

/* 
checkField 3: fieldTypes in database and apfra-table different; update database
*/
case "ud":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfield = isset($_SESSION["psd"]["dfield"]) && $_SESSION["psd"]["dfield"] ? $_SESSION["psd"]["dfield"] : "";
	$tmpftype = isset($_SESSION["psd"]["dftype"]) && $_SESSION["psd"]["dftype"] ? $_SESSION["psd"]["dftype"] : "";
	
	$query = "alter table ".$tmptable." change ".$tmpfield." ".$tmpfield." ".$tmpftype." null default null";
	$db->Execute($query);
	
	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();
		
	reload_page("?mod=".$module);		
	break;

/*
checkTable 1: table is only in database; insert table in apfra
*/
case "ca":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfields = array();

	if ($fresult = $db->Execute("show fields from ".$tmptable)) {
	
		while (!$fresult->EOF) {
	
			$tmpfields[$fresult->fields[0]] = array(
					"field" => $fresult->fields[0],
					"fieldType" => $fresult->fields[1]
			);
	
			$fresult->MoveNext();
		}
	}

	$found_id = 0;
	$found_aLastUpdate = 0;
	$found_refid_aUser_update = 0;
	foreach ($tmpfields as $field => $fieldarr) {
		if ($fieldarr["field"] == "id") $found_id = 1;
		if ($fieldarr["field"] == "aLastUpdate") $found_aLastUpdate = 1;
		if ($fieldarr["field"] == "refid_aUser_update") $found_refid_aUser_update = 1;
	}
	if ($found_id == 0) unset($tmpfields["id"]);
	if ($found_aLastUpdate == 0) unset($tmpfields["aLastUpdate"]);
	if ($found_refid_aUser_update == 0) unset($tmpfields["refid_aUser_update"]);
	
	$tmptableid = 0;
	$query = "insert into aTable (aTable, aTableDesc, aLastUpdate, refid_aUser_update) values ('".$tmptable."', '".$tmptable."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
	if ($result = $db->Execute($query)) {
		$tmptableid = $db->Insert_ID();
	}
	
	foreach ($tmpfields as $field => $fieldarr) {

		$tmpftypeid = 0;
		$query = "select id from aFieldType where aFieldType = '".$fieldarr["fieldType"]."'";
		if ($result = $db->Execute($query)) {
			if (!$result->EOF) {
				$tmpftypeid = $result->fields["id"];
			}
		}
		if ($tmpftypeid == 0) {
			$query = "insert into aFieldType (aFieldType, aLastUpdate, refid_aUser_update) values ('".$fieldarr["fieldType"]."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
			if ($result = $db->Execute($query)) {
				$tmpftypeid = $db->Insert_ID();
			}
		}
		
		$query = "insert into aField (refid_aTable, aField, aFieldDesc, refid_aFieldType, aLastUpdate, refid_aUser_update) values ('".$tmptableid."', '".$fieldarr["field"]."', '".$fieldarr["field"]."', '".$tmpftypeid."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
		$db->Execute($query);
	}

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();
		
	reload_page("?mod=".$module);		
	break;
	
/*
checkTable 2: table is only in apfra-table; create table in database
*/
case "cd":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfields = array();
	
	if ($result = $db->Execute("select id from aTable where aTable = '".$tmptable."' limit 1")) {
	
		if (!$result->EOF) {
	
			if ($fresult = $db->Execute("select aField.id, aField, aFieldDesc, aFieldType from aField, aFieldType where aField.refid_aTable = '".$result->fields["id"]."' and aField.refid_aFieldType = aFieldType.id")) {
	
				while (!$fresult->EOF) {
	
					$tmpfields[$fresult->fields["aField"]] = array(
							"field" => $fresult->fields["aField"],
							"fieldType" => $fresult->fields["aFieldType"]
					);
	
					$fresult->MoveNext();
				}
			}
		}
	}

	$found_id = 0;
	$found_aLastUpdate = 0;
	$found_refid_aUser_update = 0;
	foreach ($tmpfields as $field => $fieldarr) {
		if ($fieldarr["field"] == "id") $found_id = 1;
		if ($fieldarr["field"] == "aLastUpdate") $found_aLastUpdate = 1;
		if ($fieldarr["field"] == "refid_aUser_update") $found_refid_aUser_update = 1;
	}
	if ($found_id == 0) unset($tmpfields["id"]);
	if ($found_aLastUpdate == 0) unset($tmpfields["aLastUpdate"]);
	if ($found_refid_aUser_update == 0) unset($tmpfields["refid_aUser_update"]);
	
	$query = "";
	$query .= "create table ".$tmptable." (";
	$query .= "id int(11) unsigned not null AUTO_INCREMENT,";
	$query .= "primary key (id),";
	foreach ($tmpfields as $field => $fieldarr) {
		$query .= $fieldarr["field"]." ".$fieldarr["fieldType"]." default null,";
	}
	$query .= "aLastUpdate datetime default null,";
	$query .= "refid_aUser_update int(11) unsigned default null";
	$query .= ")";
	$db->Execute($query);

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();
		
	reload_page("?mod=".$module);		
	break;

/*
checkField 1: field is only in database; insert field in apfra
*/
case "ia":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfield = isset($_SESSION["psd"]["dfield"]) && $_SESSION["psd"]["dfield"] ? $_SESSION["psd"]["dfield"] : "";
	$tmpftype = isset($_SESSION["psd"]["dftype"]) && $_SESSION["psd"]["dftype"] ? $_SESSION["psd"]["dftype"] : "";

	$tmptableid = 0;
	$query = "select id from aTable where aTable = '".$tmptable."'";
	if ($result = $db->Execute($query)) {
		$tmptableid = $result->fields["id"];
	}
	
	$tmpftypeid = 0;
	$query = "select id from aFieldType where aFieldType = '".$tmpftype."'";
	if ($result = $db->Execute($query)) {
		if (!$result->EOF) {
			$tmpftypeid = $result->fields["id"];
		}
	}
	if ($tmpftypeid == 0) {
		$query = "insert into aFieldType (aFieldType, aLastUpdate, refid_aUser_update) values ('".$tmpftype."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
		if ($result = $db->Execute($query)) {
			$tmpftypeid = $db->Insert_ID();
		}
	}
	
	$query = "insert into aField (refid_aTable, aField, aFieldDesc, refid_aFieldType, aLastUpdate, refid_aUser_update) values ('".$tmptableid."', '".$tmpfield."', '".$tmpfield."', '".$tmpftypeid."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
	$db->Execute($query);

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();
		
	reload_page("?mod=".$module);
	break;
	
/*
checkField 2: field is only in apfra-table; insert field in database
checkField 5: field is missing in database
*/
case "id":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfield = isset($_SESSION["psd"]["dfield"]) && $_SESSION["psd"]["dfield"] ? $_SESSION["psd"]["dfield"] : "";
	$tmpftype = isset($_SESSION["psd"]["dftype"]) && $_SESSION["psd"]["dftype"] ? $_SESSION["psd"]["dftype"] : "";

	if ($tmpfield == "id") {

		$query = "alter table ".$tmptable." add ".$tmpfield." ".$tmpftype." not null auto_increment primary key first";

	} else {
		
		$tmpafter = "";
		if ($fresult = $db->Execute("show fields from ".$tmptable)) {
		
			while (!$fresult->EOF) {
	
				if (!in_array($fresult->fields[0], array('aLastUpdate', 'refid_aUser_update')) || ($tmpfield == "refid_aUser_update")) {
					
					$tmpafter = $fresult->fields[0];
				}
		
				$fresult->MoveNext();
			}
		}
		
		$query = "alter table ".$tmptable." add ".$tmpfield." ".$tmpftype." null";
		if ($tmpafter) {
			
			$query .= " after ".$tmpafter;
		}
	}

	$db->Execute($query);

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();

	reload_page("?mod=".$module);
	break;
	
/*
 checkField 2: field is only in apfra-table; delete field from apfra
*/
case "da":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfield = isset($_SESSION["psd"]["dfield"]) && $_SESSION["psd"]["dfield"] ? $_SESSION["psd"]["dfield"] : "";

	$query = "delete from aField where aField = '".$tmpfield."' and refid_aTable = (select id from aTable where aTable = '".$tmptable."') limit 1";
	$db->Execute($query);

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();
		
	reload_page("?mod=".$module);
	break;
	
/*
checkTable 1: table is only in database; delete table in database
checkField 1: field is only in database; delete field in database
*/	
case "dd":
	$tmptable = isset($_SESSION["psd"]["dtable"]) && $_SESSION["psd"]["dtable"] ? $_SESSION["psd"]["dtable"] : "";
	$tmpfield = isset($_SESSION["psd"]["dfield"]) && $_SESSION["psd"]["dfield"] ? $_SESSION["psd"]["dfield"] : "";
	
	if (!$tmpfield) {
	/*
	 checkTable 1: table is only in database; delete table in database
	 */
		$query = "drop table ".$tmptable;
		
	} else {
	/*
	checkField 1: field is only in database; delete field in database
	 */
		$query = "alter table ".$tmptable." drop ".$tmpfield;
	}

	$db->Execute($query);

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();
		
	reload_page("?mod=".$module);
	break;
}

$data_db = array();
if ($result = $db->Execute("show tables")) {

	while (!$result->EOF) {

		$tmpfields = array();
		if ($fresult = $db->Execute("show fields from ".$result->fields[0])) {

			while (!$fresult->EOF) {

				$tmpfields[$fresult->fields[0]] = array(
								"dbField" => $fresult->fields[0],
								"dbFieldType" => $fresult->fields[1]
				);
				
				$fresult->MoveNext();
			}
		}

		$data_db[$result->fields[0]] = $tmpfields;
		
		$result->MoveNext();
	}
}

$data = array();
if ($result = $db->Execute("select id, aTable, aTableDesc from aTable where aTable not in ('".implode("','", $apfra_tables)."')")) {

	while (!$result->EOF) {

		$tmpfields = array();
		if ($fresult = $db->Execute("select aField.id, aField, aFieldDesc, aFieldType from aField, aFieldType where aField.refid_aTable = '".$result->fields["id"]."' and aField.refid_aFieldType = aFieldType.id")) {
		
			while (!$fresult->EOF) {
		
				$tmpfields[$fresult->fields["aField"]] = array(
						"id" => $fresult->fields["id"],
						"aField" => $fresult->fields["aField"],
						"aFieldDesc" => $fresult->fields["aFieldDesc"],
						"aFieldType" => $fresult->fields["aFieldType"]
				);

				$fresult->MoveNext();
			}
		}
		
		$data[] = array(
				"id" => $result->fields["id"],
				"table" => $result->fields["aTable"],
				"aTableDesc" => $result->fields["aTableDesc"],
				"fields" => $tmpfields
		);

		$result->MoveNext();
	}
}

if (count($data)) {

	for ($i=0; $i<count($data); $i++) {
		if (array_key_exists($data[$i]["table"], $data_db)) {
			if (count($data_db[$data[$i]["table"]])) {
				foreach ($data_db[$data[$i]["table"]] as $field => $fieldarr) {
					$data[$i]["fields"][$field] = isset($data[$i]["fields"][$field]) ? array_merge($data[$i]["fields"][$field], $fieldarr) : $fieldarr;
				}
			}
			$data[$i]["checkTable"] = 0;
			unset($data_db[$data[$i]["table"]]);
		} else {
			$data[$i]["checkTable"] = 2;
		}
	}
}

if (count($data_db)) {
	foreach ($data_db as $table => $fields) {
		if (!in_array($table, $apfra_tables) && substr($table,0,6) != "ref1n_") {
			$data[] = array(
					"table" => $table,
					"fields" => $fields,
					"checkTable" => 1
			);
		} else {
			unset($data_db[$table]);
		}
	}
}

for ($i=0; $i<count($data); $i++) {
	foreach ($data[$i]["fields"] as $field => $fieldarr) {
		if (isset($fieldarr["dbField"]) && isset($fieldarr["aField"]) && $fieldarr["dbField"] == $fieldarr["aField"]) {
			$fieldarr["field"] = $fieldarr["dbField"];
			if ($fieldarr["dbFieldType"] == $fieldarr["aFieldType"]) {
				$fieldarr["fieldType"] = $fieldarr["dbFieldType"];
				unset($fieldarr["dbFieldType"]);
				unset($fieldarr["aFieldType"]);
				$fieldarr["checkField"] = 0;
			} else {
				
				$fieldarr["checkField"] = 3;
			}
			unset($fieldarr["dbField"]);
			unset($fieldarr["aField"]);
		} elseif (isset($fieldarr["dbField"])) {
			$fieldarr["field"] = $fieldarr["dbField"];
			$fieldarr["fieldType"] = $fieldarr["dbFieldType"];
			unset($fieldarr["dbField"]);
			unset($fieldarr["dbFieldType"]);
			if (in_array($fieldarr["field"], array("id", "aLastUpdate", "refid_aUser_update"))) {
				$fieldarr["checkField"] = 4;
			} else {
				$fieldarr["checkField"] = 1;
			}
		} else {	
			$fieldarr["field"] = $fieldarr["aField"];
			$fieldarr["fieldType"] = $fieldarr["aFieldType"];
			unset($fieldarr["aField"]);
			unset($fieldarr["aFieldType"]);
			$fieldarr["checkField"] = 2;
		}
		$data[$i]["fields"][$field] = $fieldarr;
	}
	$found_id = 0;
	$found_aLastUpdate = 0;
	$found_refid_aUser_update = 0;
	foreach ($data[$i]["fields"] as $field => $fieldarr) {
		if ($fieldarr["field"] == "id") $found_id = 1;
		if ($fieldarr["field"] == "aLastUpdate") $found_aLastUpdate = 1;
		if ($fieldarr["field"] == "refid_aUser_update") $found_refid_aUser_update = 1;
	}
	if ($found_id == 0) {
		$data[$i]["fields"]["id"] = array(
				"field" => "id",
				"fieldType" => "int(11) unsigned",
				"checkField" => 5
		);
	}
	if ($found_aLastUpdate == 0) {
		$data[$i]["fields"]["aLastUpdate"] = array(
				"field" => "aLastUpdate",
				"fieldType" => "datetime",
				"checkField" => 5
		);
	}
	if ($found_refid_aUser_update == 0) {
		$data[$i]["fields"]["refid_aUser_update"] = array(
				"field" => "refid_aUser_update",
				"fieldType" => "int(11) unsigned",
				"checkField" => 5
		);
	}
}

/*
checkTable
0: table in database + apfra-table (ok)
1: table is only in database
2: table is only in apfra-table

checkField
0: field and type is in database + apfra-table (ok)
1: field is only in database
2: field is only in apfra-table
3: fieldTypes in database and apfra-table different
4: apfra-fields (id, aLastUpdate, refid_aUser_update), only in database (ok)
5: field is missing in database
*/
$smarty->assign("data", $data);

?>