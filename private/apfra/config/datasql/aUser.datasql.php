<?php

$datasql_perpage = 10;

$datasql_search_fields = array("aUser", "email", "vorname", "nachname");

$datasql_table_fields = array("aUser", "titel", "vorname", "nachname", "email", "aktiv", "letzterlogin");
$datasql_table_orderby = array("aUser");

$datasql_edit_field_legend = array("aUser");
$datasql_edit_fields = array(
	array('tab' => 'aUser',
		'desc'  => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'aUser', 'type' => 'text', 'required' => '1', 'minlength' => '4')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'kennwort', 'type' => 'password')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'titel', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'vorname', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'nachname', 'type' => 'text', 'required' => '1')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'email', 'type' => 'text', 'required' => '1')
			)),
/* additional fields */
			array('desc' => '', 'col' => array(
				array('field' => 'StundenSatz', 'type' => 'text')
			)),
/* additional fields end */
			array('desc' => '', 'col' => array(
				array('field' => 'refid_aTheme', 'type' => 'reference_combobox')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'aktiv', 'type' => 'checkbox')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'letzterlogin', 'type' => 'readonly')
			))
	)),
	array('tab' => 'role',
		'desc' => 'Rollen',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_aUser_aRole', 'type' => 'reference1n')
			))
	)),

	array('tab' => 'Zeiterfassung',
		'desc' => 'Zeiterfassung',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_Zeiterfassung', 'type' => 'reference1nsub',
					'ref_value' => array('refid_aUser', 'refid_Taetigkeit', 'Beginn', 'DauerMin', 'StundenSatzMitarbeiter', 'StundenSatzTaetigkeit', 'round( ( ((DauerMin/60.0)*StundenSatzMitarbeiter) + ((DauerMin/60.0)*StundenSatzTaetigkeit) ), 2) as Summe'),
					'ref_order' => array('Beginn desc', 'refid_aUser'),
					'ref_total' => array('DauerMin', 'Summe'))
			))
	)),

);

$datasql_export1_fields = array("aUser", "titel", "vorname", "nachname", "email", "aktiv", "letzterlogin");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("aUser", "titel", "vorname", "nachname", "email", "aktiv", "letzterlogin");
$datasql_exportn_orderby = array("aUser");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
