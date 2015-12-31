<?php

$datasql_perpage = 10;

$datasql_search_fields = array("Firma", "Adresse", "PLZ", "Ort");

$datasql_table_fields = array("Firma", "Adresse", "PLZ", "Ort", "ref1n_Firma_Branche");
$datasql_table_orderby = array("Firma");

$datasql_edit_field_legend = array("Firma");
$datasql_edit_fields = array(
	array('tab' => 'Firma',
		'desc' => 'Stammdaten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'Firma', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Beschreibung', 'type' => 'text')
			)),
			array('desc' => 'Anschrift', 'col' => array(
				array('field' => 'Adresse', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'PLZ', 'type' => 'text'),
				array('field' => 'Ort', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'refid_Geo', 'type' => 'reference')
			)),
			array('desc' => 'Kontaktdaten', 'col' => array(
				array('field' => 'Telefon', 'type' => 'text', 'link' => '#phone#'),
				array('field' => 'TelefonBeschreibung', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Fax', 'type' => 'text'),
				array('field' => 'FaxBeschreibung', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Mobil', 'type' => 'text'),
				array('field' => 'MobilBeschreibung', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'Mail', 'type' => 'text', 'link' => '#mail#'),
				array('field' => 'MailBeschreibung', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'URL', 'type' => 'text', 'link' => '#www#'),
				array('field' => 'URLBeschreibung', 'type' => 'text')
			)),
			array('desc' => '', 'col' => array(
				array('field' => 'OeffnungszeitenBeschreibung', 'type' => 'text')
			))
	)),
	array('tab' => 'Logo',
		'desc' => 'Logo',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_Logo', 'type' => 'reference1nsub',
					'ref_value' => array('LogoGroesse'),
					'ref_order' => array('refid_Firma'))
			))
	)),
	array('tab' => 'Oeffnungszeiten',
		'desc' => '&Ouml;ffnungszeiten',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_Oeffnungszeiten', 'type' => 'reference1nsub',
					'ref_value' => array('refid_Tag', 'OffenVon', 'OffenBis', 'Ruhetag'),
					'ref_order' => array('refid_Tag', 'OffenVon'))
			))
	)),
	array('tab' => 'Branchen',
		'desc' => 'Branchen',
		'row' => array(
			array('desc' => '', 'col' => array(
				array('field' => 'ref1n_Firma_Branche', 'type' => 'reference1n')
			))
	))
);

$datasql_export1_fields = array("Firma", "Adresse", "PLZ", "Ort", "ref1n_Firma_Branche");
$datasql_print1_fields = $datasql_export1_fields;

$datasql_exportn_fields = array("Firma", "Adresse", "PLZ", "Ort", "ref1n_Firma_Branche");
$datasql_exportn_orderby = array("Firma");

$datasql_printn_fields = $datasql_exportn_fields;
$datasql_printn_orderby = $datasql_exportn_orderby;

?>
