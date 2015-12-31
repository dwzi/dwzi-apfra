<?php

$datasql_perpage = 10;

$datasql_search_fields = array("PLZ", "Ort" ,"refid_Geo");

$datasql_table_fields = array("PLZ", "Ort" ,"refid_Geo");
$datasql_table_orderby = array("PLZ", "Ort");

$datasql_edit_field_legend = array("PLZ", "Ort");
$datasql_edit_fields = array(
	array('tab' => 'PLZOrt',
		'desc' => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'PLZ', 'type' => 'text'),
				array('field' => '', 'type' => '')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Ort', 'type' => 'text'),
				array('field' => '', 'type' => '')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'refid_Geo', 'type' => 'reference'),
				array('field' => '', 'type' => '')
			))
	))
);

$datasql_export1_fields = array("PLZ", "Ort" ,"refid_Geo");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("PLZ", "Ort" ,"refid_Geo");
$datasql_exportn_orderby = array("PLZ", "Ort");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
