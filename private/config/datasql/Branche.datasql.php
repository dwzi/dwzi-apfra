<?php

$datasql_perpage = 10;

$datasql_search_fields = array("Branche");

$datasql_table_fields = array("Branche", "refid_Branche_prev");
$datasql_table_orderby = array("Branche");

$datasql_edit_field_legend = array("Branche");
$datasql_edit_fields = array(
	array('tab' => 'Branche',
		'desc' => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'refid_Branche_prev', 'type' => 'reference', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Branche', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Synonym', 'type' => 'text')
			))
	))
);

$datasql_export1_fields = array("Branche", "refid_Branche_prev", "Synonym");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("Branche", "refid_Branche_prev", "Synonym");
$datasql_exportn_orderby = array("Branche");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
