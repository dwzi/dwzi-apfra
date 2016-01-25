<?php

$col = isset($_SESSION["psd"]["col"]) ? $_SESSION["psd"]["col"] : "";
$id = isset($_SESSION["psd"]["id"]) ? $_SESSION["psd"]["id"] : "";

$query = "select ".$col.", ".$col."_fileinfo from ".$datasql_table." where id = '".$id."' limit 1";
if ($result = $db->Execute($query)) {

	if (!$result->EOF) {

		$tmpinfo = unserialize($result->fields[$col."_fileinfo"]);

		header('Content-Type: '.$tmpinfo["type"]);
		echo $result->fields[$col];
	}
}

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");
die();

?>
