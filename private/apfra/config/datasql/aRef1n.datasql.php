<?php

$datasql_perpage = 10;

$datasql_search_fields = array("refid_aTable_1", "refid_aTable_n");

$datasql_table_fields = array("refid_aTable_1", "refid_aTable_n");
$datasql_table_orderby = array("refid_aTable_1", "refid_aTable_n");

$datasql_edit_field_legend = array("refid_aTable_1", "refid_aTable_n");
$datasql_edit_fields = array(
	array('tab' => 'aRef1n',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'refid_aTable_1', 'type' => 'reference_select', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'refid_aTable_n', 'type' => 'reference_select', 'required' => '1')
			))
	))
);

$datasql_export1_fields = array("refid_aTable_1", "refid_aTable_n");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("refid_aTable_1", "refid_aTable_n");
$datasql_exportn_orderby = array("refid_aTable_1", "refid_aTable_n");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
