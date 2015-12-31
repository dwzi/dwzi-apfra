<?php

require("../config/config.inc.php");

$db = mysqli_connect(DEF_DB_HOST, DEF_DB_USER, DEF_DB_PASS);
mysqli_select_db($db, DEF_DB);

//foreach (glob(DEF_PATH_PRIVATE."apfra/config/datasql/*") as $file) {
foreach (glob(DEF_PATH_PRIVATE."config/datasql/*") as $file) {

	$module = str_replace(".datasql.php", "", basename($file));
	
	echo "reading file: ".$file;
	echo "\n";
		
	$moduleid = 0;
	
	$query = "select id from aModule where aModule = '".$module."' limit 1";
	if ($result = mysqli_query($db, $query)) {
		if ($row = mysqli_fetch_array($result)) {	
			$moduleid = $row["id"];
			echo "found module ".$module." with id ".$moduleid;
			echo "\n";
		}
	}	

	if ($moduleid == 0) {
		echo "ERROR: no module id found, please check manually!\n";
	} else {

		require($file);

		$moduletypeid = 1; /* 1 = sql */

		$query = "update aModule set";
		$query .= " sql_table_perpage = '".$datasql_perpage."',";
		$query .= " sql_table_search = '".implode(",",$datasql_search_fields)."',";
		$query .= " sql_table_fields = '".implode(",",$datasql_table_fields)."',";
		$query .= " sql_table_order = '".implode(",",$datasql_table_orderby)."',";
		$query .= " sql_edit_legend = '".implode(",",$datasql_edit_field_legend)."',";
		$query .= " sql_edit_fields = '".serialize($datasql_edit_fields)."',";
		$query .= " sql_export_fields = '".implode(",",$datasql_export_fields)."',";
		$query .= " sql_export_order = '".implode(",",$datasql_export_orderby)."',";
		$query .= " sql_print_fields = '".implode(",",$datasql_print_fields)."',";
		$query .= " sql_print_order = '".implode(",",$datasql_print_orderby)."',";
		$query .= " refid_aModuleType = '".$moduletypeid."'";
		$query .= " where id = '".$moduleid."' limit 1";
		$result = mysqli_query($db, $query);
	}

	echo "------------";
	echo "\n";

}

mysqli_close($db);

?>