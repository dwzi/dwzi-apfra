<?php

$datasql_perpage = 10;

$datasql_search_fields = array("Geo");

$datasql_table_fields = array("Geo", "refid_Geo_prev");
$datasql_table_orderby = array("Geo");

$datasql_edit_field_legend = array("Geo");
$datasql_edit_fields = array(
	array('tab' => 'Geo',
		'desc' => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'refid_Geo_prev', 'type' => 'reference', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Geo', 'type' => 'text', 'required' => '1')
			))
	))
);

$datasql_export1_fields = array("Geo", "refid_Geo_prev");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("Geo", "refid_Geo_prev");
$datasql_exportn_orderby = array("Geo");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
