<?php

$datasql_perpage = 10;

$datasql_search_fields = array("aReport");

$datasql_table_fields = array("aReport");
$datasql_table_orderby = array("aReport");

$datasql_edit_field_legend = array("aReport");
$datasql_edit_fields = array(
	array('tab' => 'daten',	
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'aReport',
					'desc' => 'Bericht',
					'type' => 'text')
			))
	)),
	array('tab' => 'felder',	
		'desc'  => 'Datenfelder',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'felder',
					'desc' => 'Datenfelder',
					'type' => 'fields')
			))
	)),
	array('tab' => 'filter',	
		'desc'  => 'Filter',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'filter',
					'desc' => 'Filter',
					'type' => 'filter')
			))
	)),
	array('tab' => 'sort',	
		'desc'  => 'Sortierung',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'sortierung',
					'desc' => 'Sortierung',
					'type' => 'fields')
			))
	))
);

?>