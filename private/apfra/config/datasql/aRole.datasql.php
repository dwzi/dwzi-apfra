<?php
//TODO make it work
$datasql_perpage = 10;

$datasql_search_fields = array("aRole");

$datasql_table_fields = array("aRole");
$datasql_table_orderby = array("aRole");

$datasql_edit_field_legend = array("aRole");
$datasql_edit_fields = array(
	array('tab' => 'aRole',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
					array('field' => 'aRole', 'type' => 'text', 'required' => '1')
			))
	)),
	array('tab' => 'aUser',
		'desc' => 'Benutzer',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_aRole_aUser', 'type' => 'reference1n')
			))
	))
);

$datasql_export1_fields = array("aRole");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("aRole");
$datasql_exportn_orderby = array("aRole");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
