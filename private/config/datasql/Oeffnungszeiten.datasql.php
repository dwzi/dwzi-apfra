<?php

$datasql_perpage = 10;

$datasql_search_fields = array("Oeffnungszeiten");

$datasql_table_fields = array("refid_Tag", "OffenVon", "OffenBis", "Ruhetag");
$datasql_table_orderby = array("refid_Tag", "OffenVon");

$datasql_edit_field_legend = array("refid_Tag");
$datasql_edit_fields = array(
	array('tab' => 'Oeffnungszeiten',
		'desc' => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'refid_Tag', 'type' => 'reference', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'OffenVon', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'OffenBis', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Ruhetag', 'type' => 'checkbox')
			))
	))
);

$datasql_export1_fields = array("refid_Tag", "OffenVon", "OffenBis", "Ruhetag");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("refid_Tag", "OffenVon", "OffenBis", "Ruhetag");
$datasql_exportn_orderby = array("refid_Tag", "OffenVon");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
