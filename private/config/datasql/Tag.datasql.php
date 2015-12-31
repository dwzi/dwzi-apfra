<?php

$datasql_perpage = 10;

$datasql_search_fields = array("Tag");

$datasql_table_fields = array("Tag");
$datasql_table_orderby = array("Reihenfolge");

$datasql_edit_field_legend = array("Tag");
$datasql_edit_fields = array(
	array('tab' => 'Tag',
		'desc' => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'Tag', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Reihenfolge', 'type' => 'text')
			))
	))
);

$datasql_export1_fields = array("Tag");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("Tag");
$datasql_exportn_orderby = array("Reihenfolge");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
