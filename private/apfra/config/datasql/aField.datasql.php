<?php

$datasql_perpage = 10;

$datasql_search_fields = array("refid_aTable", "aField", "aFieldDesc", "refid_aFieldType");

$datasql_table_fields = array("refid_aTable", "aField", "aFieldDesc");
$datasql_table_orderby = array("refid_aTable", "aField");

$datasql_edit_field_legend = array("aField");
$datasql_edit_fields = array(
	array('tab' => 'aField',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'refid_aTable', 'type' => 'reference_select', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aField', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aFieldDesc', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'refid_aFieldType', 'type' => 'reference_select', 'required' => '1')
			))
	))
);

$datasql_export1_fields = array("refid_aTable", "aField", "aFieldDesc", "refid_aFieldType");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("refid_aTable", "aField", "aFieldDesc", "refid_aFieldType");
$datasql_exportn_orderby = array("refid_aTable", "aField");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
