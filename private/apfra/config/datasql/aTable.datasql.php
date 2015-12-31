<?php

$datasql_perpage = 10;

$datasql_search_fields = array("aTable", "aTableDesc");

$datasql_table_fields = array("aTable", "aTableDesc");
$datasql_table_orderby = array("aTable");

$datasql_edit_field_legend = array("aTable");
$datasql_edit_fields = array(
	array('tab' => 'aTable',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'aTable', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aTableDesc', 'type' => 'text', 'required' => '1')
			))
	)),
	array('tab' => 'felder',
		'desc' => 'Datenfelder',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_aField', 'type' => 'reference1nsub',
					'ref_value' => array("aField", "aFieldDesc", "refid_aFieldType"),
					'ref_order' => array("aField"))
			))
	)),
	array('tab' => 'referenz',
		'desc' => 'Referenzen',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_aRef', 'type' => 'reference1nsub',
					'ref_value' => array("aRef", "aRefDesc", "aField"),
					'ref_order' => array("aRef"))
			))
	))
);

$datasql_export1_fields = array("aTable", "aTableDesc");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("aTable", "aTableDesc");
$datasql_exportn_orderby = array("aTable");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
