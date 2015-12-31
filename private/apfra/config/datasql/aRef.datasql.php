<?php

$datasql_perpage = 10;

$datasql_search_fields = array("refid_aTable", "aRef", "aRefDesc", "aField");

$datasql_table_fields = array("refid_aTable", "aRef", "aRefDesc", "aField");
$datasql_table_orderby = array("refid_aTable", "aRef");

$datasql_edit_field_legend = array("aRef");
$datasql_edit_fields = array(
	array('tab' => 'aRef',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'refid_aTable', 'type' => 'reference_select', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aRef', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aRefDesc', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aField', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aSearch', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aOrder', 'type' => 'text', 'required' => '1')
			))
	))
);

$datasql_export1_fields = array("refid_aTable", "aRef", "aRefDesc", "aField", "aSearch", "aOrder");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("refid_aTable", "aRef", "aRefDesc", "aField", "aSearch", "aOrder");
$datasql_exportn_orderby = array("refid_aTable", "aRef");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
