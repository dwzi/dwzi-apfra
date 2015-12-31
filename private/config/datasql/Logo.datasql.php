<?php

$datasql_perpage = 10;

$datasql_search_fields = array("refid_Firma");

$datasql_table_fields = array("refid_Firma", "LogoGroesse"); // "Logo"
$datasql_table_orderby = array("refid_Firma");

$datasql_edit_field_legend = array("refid_Firma");
$datasql_edit_fields = array(
	array('tab' => 'Logo',
		'desc' => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'refid_Firma', 'type' => 'reference', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Logo', 'type' => 'image', 'maxwidth' => '200', 'maxheight' => '150')
			))
	))
);

$datasql_export1_fields = array("refid_Firma", "LogoGroesse");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("refid_Firma", "LogoGroesse");
$datasql_exportn_orderby = array("refid_Firma");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
