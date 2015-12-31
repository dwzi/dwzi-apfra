<?php

$tab = isset($_SESSION["psd"]["t"]) ? $_SESSION["psd"]["t"] : "";
$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";

$backmod = isset($_SESSION["psd"]["backmod"]) ? $_SESSION["psd"]["backmod"] : "";
$backid = isset($_SESSION["psd"]["backid"]) ? $_SESSION["psd"]["backid"] : "";
$backt = isset($_SESSION["psd"]["backt"]) ? $_SESSION["psd"]["backt"] : "";

$errors = 0;
$datetime = date("Y-m-d H:i:s");

$datasql_fields_edit = array();
$data_convert_datetime = array();
$data_convert_date = array();

foreach ($datasql_edit_fields as $fields_tab) {
	foreach ($fields_tab["row"] as $fields_row) {
		foreach ($fields_row["col"] as $fields_col) {
			$datasql_fields_edit[] = $fields_col["field"];				
		}
	}
}

if (substr($formaction,0,4) == "del_") {
	
	$did = isset($_SESSION["psd"]["did"]) ? $_SESSION["psd"]["did"] : "";
	if ($did) {

		$delta = array();
		if ($result = $db->Execute("select * from ".substr($formaction,4,255)." where id = '".$did."' limit 1")) {
			if (!$result->EOF) {
				foreach ($result->fields as $key => $field) {
					if (!is_numeric($key)) {
						$delta[$key] = $field;
					}
				}
				$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
				unset($delta["id"]);
			}
		}

		$query = "delete from ".substr($formaction,4,255)." where id = '".$did."' limit 1";
		if (!$result = $db->Execute($query)) {
echo $query;
die();
		} else {

			$apfra_log_db->delete(substr($formaction,4,255), $did, $delta);
		}
		reload_page("?mod=".$module."&p=".$page."&s=".$search."&a=".$action."&id=".$id."&t=".$tab);		
	}
}

if ($formaction == "save" || $formaction == "saveback") {

//	$fields = "";
	$fieldarr = array();
	$filterarr = array();
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,2) == "f_") {
			$fieldarr[substr($key,2,255)] = $value;
		}
	}
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,11) == "sfl_filter_") {
			$tmp = substr($key,11,255);
			$tmpnr = substr($tmp, 0, strpos($tmp, "_"));
			$tmpkey = substr($tmp, strpos($tmp, "_")+1, 255);
			$filterarr[$tmpnr][$tmpkey] = $value;
		}
	}

	if (count($filterarr)) {
		$fieldarr["filter"] = serialize($filterarr);
	}

	if (count($fieldarr)) {
		foreach ($datasql_fields_edit as $field) {
			if (!isset($fieldarr[$field])) {
				foreach ($datasql_fields_add_default as $fieldadd => $value) {
					if ($field == $fieldadd) {
						$fieldarr[$fieldadd] = $value;
					}
				}
			}
		}
		
		if (count($data_convert_datetime)) {
			foreach ($data_convert_datetime as $field) {
				if ($fieldarr[$field]) {
					$value = $fieldarr[$field];
					$fieldarr[$field] = date_format(date_create_from_format('d.m.Y H:i', $value), 'Y-m-d H:i:s');
				}
			}
		}

		if (count($data_convert_date)) {
			foreach ($data_convert_date as $field) {
				if ($fieldarr[$field]) {
					$value = $fieldarr[$field];
					$fieldarr[$field] = date_format(date_create_from_format('d.m.Y', $value), 'Y-m-d');
				}
			}
		}
	}

	if (!$errors) {

//		$fields = implode(", ", $fieldarr);
		
		$fieldarr["aLastUpdate"] = $datetime;
		$fieldarr["refid_aUser_update"] = $_SESSION["psu"]["id"];
		
		if ($id > 0) {
			$query = "update ".$datasql_table." set ";
			if (count($fieldarr)) {
				foreach ($fieldarr as $key => $value) {
					if ($value == '')
						$query .= $key." = NULL, ";
					else $query .= $key." = '".$value."', ";
				}
				$query = substr($query, 0, -2);
			}
			$query .= " where id = '".$id."'";
		} else {
			$query = "insert into ".$datasql_table." (";
			if (count($fieldarr)) {
				foreach ($fieldarr as $key => $value) {
					$query .= "`".$key."`, ";
				}
				$query = substr($query, 0, -2);
				$query .= ") values (";
				foreach ($fieldarr as $key => $value) {
					if ($value == '')
						$query .= "NULL, ";
					else $query .= "'".$value."', ";
				}
				$query = substr($query, 0, -2);
				$query .= ")";
			}
		}
		
		$delta = array();
		if ($id > 0) {		
			if ($result = $db->Execute("select aLastUpdate, ".implode(",",$datasql_fields_edit)." from ".$datasql_table." where id = '".$id."' limit 1")) {
				if (!$result->EOF) {
					$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
					foreach ($datasql_fields_edit as $field) {
						if ($fieldarr[$field] != $result->fields[$field]) {
							$delta[$field] = $result->fields[$field];
						}
					}
				}
			}
		}
		
		if (!$result = $db->Execute($query)) {
echo $query;
die();
			$errors++;
		} else {

			if ($id > 0) {

				$apfra_log_db->update($datasql_table, $id, $delta);
			}

			if ($formaction == "save") {
				
				if ($id == 0) {	
					if ($tmpid = $db->Insert_ID()) {
						reload_page("?mod=".$module."&p=".$page."&s=".$search."&a=".$action."&id=".$tmpid."&t=".$tab);
					} else {
						reload_page("?mod=".$module."&p=".$page."&s=".$search);
					}
				} else {
					reload_page("?mod=".$module."&p=".$page."&s=".$search."&a=".$action."&id=".$id."&t=".$tab);
				}
			}
		}

		if ($formaction == "saveback") {
				if ($backmod && $backid) {	
					reload_page("?mod=".$backmod."&a=edit&id=".$backid."&t=".$backt);
				} else {
					reload_page("?mod=".$module."&p=".$page."&s=".$search);
				}	
		}
	}
}


$data = array();
$data_filter = array();

if (($id > 0) && ($result = $db->Execute("select aLastUpdate, (select aUser from aUser where id = ".$datasql_table.".refid_aUser_update) as ref_benutzer, ".implode(",",$datasql_fields_edit)." from ".$datasql_table." where id = '".$id."' limit 1"))) {

	if (!$result->EOF) {

		$data["aLastUpdate"] = $result->fields["aLastUpdate"];
		$data["ref_benutzer"] = $result->fields["ref_benutzer"];
		foreach ($datasql_fields_edit as $field) {
			if ($field != "filter") {
				$field = str_replace("`","",$field);
				if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
				$data[$field] = $result->fields[$field];				
			} else {
				$data[$field] = "";
				if ($result->fields[$field] != "") {
					$data_filter = unserialize($result->fields[$field]);
				}
			}
		}
	}
} else {

	$data["aLastUpdate"] = "";
	$data["ref_benutzer"] = "";
	foreach ($datasql_fields_edit as $field) {
		if ($field <> "id") {
			$data[$field] = "";
		}
	}
	foreach ($datasql_fields_add_default as $field => $value) {
		$data[$field] = $value;
	}
	
	if (file_exists(DEF_PATH_PRIVATE."mod/".$module."/".$module."_pre_insert.inc.php")) {
		require(DEF_PATH_PRIVATE."mod/".$module."/".$module."_pre_insert.inc.php");
	}
}

$smarty->assign("data_filter", $data_filter);

$smarty->assign("errors", $errors);
$smarty->assign("tab", $tab);

$smarty->assign("backmod", $backmod);
$smarty->assign("backid", $backid);
$smarty->assign("backt", $backt);

?>