<?php

header('Content-Type: text/xml; charset=utf-8');
//header('Content-Disposition: attachment; filename=definition.xml');

$xml = new SimpleXMLElement("<apfra></apfra>");
$xmltables = $xml->addChild('tables');
$xmlreferences11 = $xml->addChild('references11');
$xmlreferences1n = $xml->addChild('references1n');

function todesc($value) {

	$tmpdesc = $value;
	if (strpos($tmpdesc, "_") !== false) {
		$tmpdesc = str_replace("_", " ", $tmpdesc);
	}
	$imax = strlen($tmpdesc);
	for ($i=1; $i<$imax; $i++) {
		if (in_array($tmpdesc[$i], range('A', 'Z'))) {
			$tmpdesc = substr($tmpdesc,0,$i)." ".substr($tmpdesc,$i,255);
			$i++;
		}
	}
	
	return $tmpdesc;
}

$dataxml_ref11 = array();
$dataxml_ref1n = array();
if (($result = $db->Execute("show tables"))) {

	while (!$result->EOF) {

		$tmptable = $result->fields[0];

		if (!in_array($tmptable, array('aLogUser','aLogDB', 'bericht'))) { // 'aUser',

			if (substr($tmptable,0,6) != "ref1n_") {

				$xmltable = $xmltables->addChild('table');
				$xmltable->addAttribute('name', $tmptable);				
				$xmltable->addAttribute('desc', todesc($tmptable));
				
				$xmlfields = $xmltable->addChild('fields');

			} else {
			
				if (!in_array($tmptable, $dataxml_ref1n)) {
					$dataxml_ref1n[] = $tmptable;
				}
			}
				
			if (($resultf = $db->Execute("show fields from ".$tmptable))) {
					
				while (!$resultf->EOF) {

					$tmpfield = $resultf->fields[0];
/*
					$tmptype = $resultf->fields[1];
					if ($tmptype == "longtext") $tmptype = "text";
					if (substr($tmptype,0,3) == "int") $tmptype = "text"; 
					if (substr($tmptype,0,7) == "varchar") $tmptype = "text";
*/			
					if (!in_array($tmpfield, array('id', 'aLastUpdate', 'refid_aUser_update'))) {
						
						$tmpdesc = $resultf->fields[0];
						if (substr($tmpdesc,0,6) == "refid_") {
							$tmpdesc = "";
//							$tmptype = "reference";
						}
						$tmpdesc = todesc($tmpdesc);

						if (substr($tmptable,0,6) != "ref1n_") {

							$xmlfield = $xmlfields->addChild('field');
							$xmlfield->addAttribute('name', $resultf->fields[0]);
							$xmlfield->addAttribute('desc', $tmpdesc);
//							$xmlfield->addAttribute('type', $tmptype);
						}
			
						if (substr($tmpfield,0,6) == "refid_") {
							if (!in_array($tmpfield, $dataxml_ref11)) {
								$dataxml_ref11[] = $tmpfield;
							}
						}
					}
					$resultf->MoveNext();
				}
			}				
		}
		$result->MoveNext();
	}
}

if (count($dataxml_ref11)) {
	
	foreach ($dataxml_ref11 as $field) {

		$table = substr($field,6,255);
		if (strpos($table, "_") !== false) {	
			$table = substr($table,0,strpos($table, "_"));
		}
		
		$xmltable = $xmlreferences11->addChild('reference');
		$xmltable->addAttribute('name', $field); 
		$xmltable->addAttribute('desc', todesc(substr($field,6,255))); 
		$xmltable->addAttribute('table', $table);
		$xmltable->addAttribute('field', $table);
		$xmltable->addAttribute('search', $table);
		$xmltable->addAttribute('orderby', $table);
	}
}

if (count($dataxml_ref1n)) {

	foreach ($dataxml_ref1n as $table) {

		$xmltable = $xmlreferences1n->addChild('reference');
		$xmltable->addAttribute('name', substr($table, 6, 255)); 
	}
}

$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
echo $dom->saveXML();

require(DEF_PATH_PRIVATE."apfra/lib/exit.inc.php");
die();

?>