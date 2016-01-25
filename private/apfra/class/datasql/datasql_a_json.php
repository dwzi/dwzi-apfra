<?php

$ref = isset($_SESSION["psd"]["ref"]) ? $_SESSION["psd"]["ref"] : "";
$id = isset($_SESSION["psd"]["id"]) ? $_SESSION["psd"]["id"] : "";
$q = isset($_SESSION["psd"]["query"]) ? $_SESSION["psd"]["query"] : (isset($_SESSION["psd"]["q"]) ? $_SESSION["psd"]["q"] : "");
$refarr = array();
$data = array();
$lookup = 0;

if (substr($ref,0,6) == "ref1n_") {
	$ref = substr($ref,6,255);
	$ref = "refid_".substr($ref,strpos($ref, "_")+1,255);
} elseif (substr($ref,0,7) == "lookup_") {
	$ref = substr($ref,7,255);
	$lookup = 1;
}

if (count($datasql_reference11)) {

	foreach ($datasql_reference11 as $key => $valuearr) {

		if ($key == $ref) {
			$refarr = $valuearr;
		}
	}
}

$query = "";
if (count($refarr)) {

	$where = "";
	if ($q) {
		if (count($refarr["search"])) {
			$where .= " where (";
			foreach ($refarr["search"] as $field) {

				if (!$lookup && in_array($field, $datasql_reference11_fields)) {

					$tmpwhere = "";
					foreach ($datasql_reference11[$field]["search"] as $svalue) {
						$tmpwhere .= $field." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$q."%') or ";
					}

					$where .= $tmpwhere;
				} else {

					$where .= $field." like '%".$q."%' or ";
				}
			}
			$where = substr($where, 0, strlen($where) - 3);
			$where .= ")";
			if (isset($refarr["subtable"]) && isset($refarr["subrefid"])) {

				$tmpid = 0;
				if ($id) {

					if ($sresult = $db->Execute("select ".$refarr["subrefid"]." from ".$datasql_table." where id = '".$id."' limit 1")) {
						if (!$sresult->EOF) {
							$tmpid = $sresult->fields[$refarr["subrefid"]];
						}
					}
				}
				$where .= " and ".$refarr["subtable"].".id = ".$refarr["table"].".".$refarr["subrefid"]." and ".$refarr["subrefid"]." = ".$tmpid;
			}
		}
	}

	if (in_array($refarr["table"], array("aModule", "aTable"))) {
		$where .= !$where ? " where " : " and ";
		$where .= "(".$refarr["table"]." not in ('".implode("','", $apfra_tables)."'))";
	}
	$query = "select ".$refarr["table"].".id, ".$refarr["field"]." from ".$refarr["table"].(isset($refarr["subtable"]) ? ", ".$refarr["subtable"] : "")." ".$where." order by ".implode(",",$refarr["orderby"]);

} else if ($lookup) {

	foreach ($datasql_edit_fields as $fields_tab) {
		foreach ($fields_tab["row"] as $fields_row) {
			foreach ($fields_row["col"] as $fields_col) {
				if ($fields_col["field"] == $ref) {
					$refarr["field"] = $fields_col["ref_value"];
					$query = "select id, ".$refarr["field"]." from ".$fields_col["ref_table"]." where ".$refarr["field"]." like '%".$q."%' order by ".$refarr["field"];
				}
			}
		}
	}
}

if ($query) {

	if ($result = $db->Execute($query)) {

		while (!$result->EOF) {

			$datatmp = array();

			$field = str_replace("`","",$refarr["field"]);
			if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
			$datatmp["id"] = $result->fields["id"];
			$datatmp[$field] = $result->fields[$field];

			array_push($data, $datatmp);

			$result->MoveNext();
		}
	}
}

echo json_encode($data);

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");
die();

?>
