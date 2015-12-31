<?php

$datasql_perpage = 10;

$datasql_search_fields = array("aModule", "aModuleDesc");

$datasql_table_fields = array("aModule", "aModuleDesc");
$datasql_table_orderby = array("aModule");

$datasql_edit_field_legend = array("aModule");
$datasql_edit_fields = array(
	array('tab' => 'aModule',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'aModule', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aModuleDesc', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'refid_aModuleType', 'type' => 'reference_combobox')
			))
	)),
	array('tab' => 'sql',
		'desc'  => 'sql',
		'row' => array(
			array('desc' => 'Tabelle', 'col' => array(
				array('field' => 'sql_table_perpage', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'sql_table_search', 'type' => 'fixed_fields')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'sql_table_fields', 'type' => 'fixed_fields')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'sql_table_order', 'type' => 'fixed_fields')
			)),
			array('desc' => 'Bearbeiten', 'col' => array(
				array('field' => 'sql_edit_legend', 'type' => 'fixed_fields')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'sql_edit_fields', 'type' => 'fixed_edit_fields')
			)),
			array('desc' => 'Export 1', 'col' => array(
				array('field' => 'sql_export1_fields', 'type' => 'fixed_fields')
			)),
			array('desc' => 'Druck 1', 'col' => array(
				array('field' => 'sql_print1_fields', 'type' => 'fixed_fields')
			)),
			array('desc' => 'Export n', 'col' => array(
				array('field' => 'sql_exportn_fields', 'type' => 'fixed_fields')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'sql_exportn_order', 'type' => 'fixed_fields')
			)),
			array('desc' => 'Druck n', 'col' => array(
				array('field' => 'sql_printn_fields', 'type' => 'fixed_fields')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'sql_printn_order', 'type' => 'fixed_fields')
			))
	)),
	array('tab' => 'file',
		'desc'  => 'file',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'file_perpage', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'file_path', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'file_search', 'type' => 'text')
			))
	))
);

$datasql_export1_fields = array("aModule", "aModuleDesc");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("aModule", "aModuleDesc");
$datasql_exportn_orderby = array("aModule");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
