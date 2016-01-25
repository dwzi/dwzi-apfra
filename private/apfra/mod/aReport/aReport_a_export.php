<?php

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=export.csv');
header('Content-Disposition: attachment; filename='.date("Y-m-d_H-i-s").'_report.csv');

$data = array();
if ($result = $db->Execute("select aReport, felder, filter, sortierung from ".$datasql_table." where id = '".$id."' limit 1")) {

	if (!$result->EOF) {

		$data["aReport"] = $result->fields["aReport"];
		$data["felder"] = $result->fields["felder"];
		$data["filter"] = $result->fields["filter"];
		$data["sortierung"] = $result->fields["sortierung"];
	}
}

$data_tables = array();
$data_fields = explode(",", $data["felder"]);
$data_filters = $data["filter"] ? unserialize($data["filter"]) : array();

if (count($data_fields)) {
	foreach ($data_fields as $data_field) {
		$tmparr = explode(".", $data_field);
		$tmptable = isset($tmparr[0]) && $tmparr[0] ? $tmparr[0] : "";
		if (!in_array($tmptable, $data_tables) && $tmptable) {
			$data_tables[] = $tmptable;
		}
	}
}

if (count($data_filters)) {

	foreach ($data_filters as $dfarr) {

		$tmparr = explode(".", $dfarr["col"]);
		$tmptable = isset($tmparr[0]) && $tmparr[0] ? $tmparr[0] : "";

		if (!in_array($tmptable, $data_tables) && $tmptable) {
			$data_tables[] = $tmptable;
		}
	}
}

$wherearr = array();

if (count($datasql_reference1n)) {

	foreach ($datasql_reference1n as $ref1n) {

		$tmparr = explode("_", substr($ref1n, 6, 255));
		$tmptable1 = $tmparr[0];
		$tmptablen = $tmparr[1];

		if (in_array($tmptable1, $data_tables) && in_array($tmptablen, $data_tables) && !in_array($ref1n, $data_tables)) {
			$data_tables[] = $ref1n;
			$wherearr[] = $ref1n.".refid_".$tmptable1." = ".$tmptable1.".id";
			$wherearr[] = $ref1n.".refid_".$tmptablen." = ".$tmptablen.".id";
		}
	}
}

if (count($data_tables)) {
	foreach ($data_tables as $data_table) {
		foreach ($datareport_ref as $refkey => $refvalue) {
			if ($refkey == $data_table) {
				foreach ($refvalue as $subkey => $subvalue) {
					if (in_array($subkey, $data_tables)) {
						$wherearr[] = $subkey.".id = ".$refkey.".".$subvalue;
					}
				}
			}
		}
	}
}

if (count($data_filters)) {
	$tmpwhere = "";

	foreach ($data_filters as $dfarr) {

		if (isset($dfarr["junc"]) && $dfarr["junc"]) $tmpwhere .= " ".$dfarr["junc"];
		$tmpwhere .= " ".$dfarr["col"]." ".$dfarr["op"]." '".$dfarr["val"]."'";
	}

	if ($tmpwhere) {
		$wherearr[] = "(".$tmpwhere.")";
	}
}

if ($wherearr) {
	$where = "where ".implode(" and ", $wherearr);
} else {
	$where = "";
}

$data_fields_desc = array();;
if (count($data_fields)) {
	foreach ($data_fields as $field) {
		$data_fields_desc[] = utf8_decode($apfra_db_desc[$field]);
	}
}

$output = fopen('php://output', 'w');
fputcsv($output, $data_fields_desc, ";", "\"");

$orderby = $data["sortierung"] ? "order by ".$data["sortierung"] : "";

$query = "select ".$data["felder"]." from ".implode(",", $data_tables)." ".$where." ".$orderby;
if ($result = $db->Execute($query)) {

	while (!$result->EOF) {

		$data = array();
		foreach ($data_fields as $data_field) {
			$tmparr = explode(".", $data_field);
			$tmptable = isset($tmparr[0]) && $tmparr[0] ? $tmparr[0] : "";
			$tmpfield = isset($tmparr[1]) && $tmparr[1] ? $tmparr[1] : "";
			$stmp = $result->fields[$tmpfield];
			/*
			if (is_numeric($stmp)) {
				$stmp = number_format($stmp, 2, ",", ".");
			}*/
			$data[$tmpfield] = mb_convert_encoding($stmp, 'UTF-16LE', 'UTF-8');
		}

		fputcsv($output, $data, ";", "\"");

		$result->MoveNext();
	}
}

fclose($output);

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");
die();

?>
