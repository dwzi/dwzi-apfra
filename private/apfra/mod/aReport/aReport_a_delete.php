<?php

$datetime = date("Y-m-d H:i:s");

if ($id > 0) {

	$delta = array();
	if ($result = $db->Execute("select * from ".$datasql_table." where id = '".$id."' limit 1")) {
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
	$apfra_log_db->delete($datasql_table, $id, $delta);
	
	$result = $db->Execute("delete from ".$datasql_table." where id = '".$id."' limit 1");

	reload_page("?mod=".$module."&p=".$page."&s=".$search."&sort=".$sort."&dir=".$dirsort);
}

?>