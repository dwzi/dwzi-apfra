<?php

$datasql_perpage = 10;

$datasql_search_fields = array("aTheme");

$datasql_table_fields = array("aTheme");
$datasql_table_orderby = array("aTheme");

$datasql_edit_field_legend = array("aTheme");
$datasql_edit_fields = array(
	array('tab' => 'aTheme',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'aTheme', 'type' => 'text', 'required' => '1')
			))
	))
);

$datasql_export1_fields = array("aTheme");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("aTheme");
$datasql_exportn_orderby = array("aTheme");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
