<?php

$datasql_perpage = 10;

$datasql_search_fields = array("aModuleType");

$datasql_table_fields = array("aModuleType");
$datasql_table_orderby = array("aModuleType");

$datasql_edit_field_legend = array("aModuleType");
$datasql_edit_fields = array(
	array('tab' => 'aModuleType',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'aModuleType', 'type' => 'text', 'required' => '1')
			))
	))
);

$datasql_export1_fields = array("aModuleType");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("aModuleType");
$datasql_exportn_orderby = array("aModuleType");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
