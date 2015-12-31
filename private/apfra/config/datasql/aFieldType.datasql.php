<?php

$datasql_perpage = 10;

$datasql_search_fields = array("aFieldType");

$datasql_table_fields = array("aFieldType");
$datasql_table_orderby = array("aFieldType");

$datasql_edit_field_legend = array("aFieldType");
$datasql_edit_fields = array(
	array('tab' => 'aFieldType',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'aFieldType', 'type' => 'text', 'required' => '1')
			))
	))
);

$datasql_export1_fields = array("aFieldType");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("aFieldType");
$datasql_exportn_orderby = array("aFieldType");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
