<?php

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.date("Y-m-d_H-i-s").'_export_'.$module.'.csv');

$datasql_table_fields_ref1n = array();
$datasql_table_fields_sql = array();
$datasql_table_fields_tables = array();
for ($i = 0; $i < count($datasql_exportn_fields); $i++) {
	if (strpos($datasql_exportn_fields[$i], ".") !== false) {
		$tmparr = explode(".", $datasql_exportn_fields[$i]);
		if (!in_array($tmparr[0], $datasql_table_fields_tables)) {
			$datasql_table_fields_tables[] = $tmparr[0];
		}
		$datasql_table_fields_sql[] = $datasql_exportn_fields[$i];
		if (strpos($datasql_exportn_fields[$i], " as ") !== false) {
			$datasql_exportn_fields[$i] = substr($datasql_exportn_fields[$i], strpos($datasql_exportn_fields[$i], " as ")+4, strlen($datasql_exportn_fields[$i]));
		}
	} elseif (substr($datasql_exportn_fields[$i],0,6) == "ref1n_" && substr($datasql_exportn_fields[$i],6,strrpos($datasql_exportn_fields[$i],"_")-6) == $datasql_table) {
		$datasql_table_fields_ref1n[] = $datasql_exportn_fields[$i];
		$apfra_db_desc[$datasql_table.".".$datasql_exportn_fields[$i]] = $apfra_db_desc[substr($datasql_exportn_fields[$i],strrpos($datasql_exportn_fields[$i],"_")+1,255)];
	} elseif ($datasql_exportn_fields[$i] != "refid_aUser_update") {
		$datasql_table_fields_sql[] = $datasql_exportn_fields[$i];
	}
}

$decimal_type = array();
if ($result = $db->Execute("select aField from aField where refid_aTable = (select id from aTable where aTable = '".$datasql_table."') and refid_aFieldType = (select id from aFieldType where aFieldType = 'decimal(19,2)')")) {
	while (!$result->EOF) {
		$decimal_type[] = $result->fields["aField"];
		$result->MoveNext();
	}
}

$where = "";
if ($search) {
	if (count($datasql_search_fields)) {
		$where .= " where (";
		foreach ($datasql_search_fields as $field) {

			if (strpos($field,".") === false) {
				$tmpsql_sfield = $datasql_table.".".$field;
			} else {
				$tmpsql_sfield = $field;
			}

			if (in_array($field, $datasql_reference11_fields)) {

				$tmpwhere = "";
				foreach ($datasql_reference11[$field]["search"] as $svalue) {
					$tmpwhere .= $tmpsql_sfield." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$search."%') or ";
				}

				$where .= $tmpwhere;

			} elseif (in_array($field, $datasql_table_fields_ref1n)) {

				$tmp_ref1n = substr($field,strrpos($field,"_")+1,255);

				$tmpidarr = array();
				if ($sresult = $db->Execute("select id from ".$tmp_ref1n." where ".$tmp_ref1n." like '%".$search."%'")) {
					while (!$sresult->EOF) {
						$tmpidarr[] = $sresult->fields["id"];
						$sresult->MoveNext();
					}
				}

				if (count($tmpidarr)) {

					$where .= "(select count(*) from ".$field." where ".$field.".refid_".$datasql_table." = ".$datasql_table.".id and ".$field.".refid_".$tmp_ref1n." in ('".implode("','", $tmpidarr)."') > '0') or ";
				}

			} else {

				$where .= $tmpsql_sfield." like '%".$search."%' or ";
			}
		}
		$where = substr($where, 0, strlen($where) - 3);
		$where .= ")";
	}
}

if (count($data_filter)) {

	$where .= !$where ? " where (" : " and (";
	foreach ($data_filter as $field => $value) {
		if (in_array($field, $datasql_reference11_fields)) {

			$tmpwhere = "(";
			foreach ($datasql_reference11[$field]["search"] as $svalue) {
				$tmpwhere .= $field." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$value."%') or ";
			}
			$tmpwhere = substr($tmpwhere, 0, strlen($tmpwhere) - 3);
			$tmpwhere .= ") and ";
			$where .= $tmpwhere;

		} elseif (in_array($field, $datasql_table_fields_ref1n)) {

			$tmp_ref1n = substr($field,strrpos($field,"_")+1,255);

			$tmpidarr = array();
			if ($sresult = $db->Execute("select id from ".$tmp_ref1n." where ".$tmp_ref1n." like '%".$value."%'")) {
				while (!$sresult->EOF) {
					$tmpidarr[] = $sresult->fields["id"];
					$sresult->MoveNext();
				}
			}

			if (count($tmpidarr)) {

				$where .= "(select count(*) from ".$field." where ".$field.".refid_".$datasql_table." = ".$datasql_table.".id and ".$field.".refid_".$tmp_ref1n." in ('".implode("','", $tmpidarr)."') > '0') and ";
			}

		} else {

			$tmp_field = $field;
			foreach ($datasql_table_fields_sql as $fvalue) {
				if (strpos($fvalue, " as ") !== false) {
					$tmp_field = substr($fvalue, 0, strpos($fvalue, " as "));
				}
			}

			$where .= $tmp_field." like '%".$value."%' and ";
		}
	}
	$where = substr($where, 0, strlen($where) - 4);
	$where .= ")";
}

if (count($datasql_table_fields_tables)) {

	$where .= !$where ? " where (" : " and (";
	foreach ($datasql_table_fields_tables as $tab) {
		$where .= $tab.".id = ".$datasql_table.".refid_".$tab." and ";
	}
	$where = substr($where, 0, strlen($where) - 4);
	$where .= ")";
}

$output = fopen('php://output', 'w');

$tmpdescarr = array();
foreach ($datasql_table_fields_sql as $field) {
	if (strpos($field," as ") === false) {
		$tmpdescarr[] = mb_convert_encoding($apfra_db_desc[$datasql_table.".".$field], 'UTF-16LE', 'UTF-8');
	} else {
		$tmpdescarr[] = mb_convert_encoding(substr($field,strpos($field," as ")+4,255), 'UTF-16LE', 'UTF-8');
	}
}
if (in_array("refid_aUser_update", $datasql_exportn_fields)) {
	$tmpdescarr[] = mb_convert_encoding($apfra_db_desc[$datasql_table.".refid_aUser_update"], 'UTF-16LE', 'UTF-8');
}
if (count($datasql_table_fields_ref1n)) {
	foreach ($datasql_table_fields_ref1n as $field) {
		$tmpdescarr[] = mb_convert_encoding($apfra_db_desc[$datasql_table.".".$field], 'UTF-16LE', 'UTF-8');
	}
}

fputcsv($output, $tmpdescarr, ";", "\"");

$tmp_sql_fields = array();
for ($i=0; $i<count($datasql_table_fields_sql);$i++) {
	if (strpos($datasql_table_fields_sql[$i], ".") === false) {
		$tmp_sql_fields[] = $datasql_table.".".$datasql_table_fields_sql[$i];
	} else {
		$tmp_sql_fields[] = $datasql_table_fields_sql[$i];
	}
}

$tmp_sql_order = array();
for ($i=0; $i<count($datasql_exportn_orderby);$i++) {
	if (strpos($datasql_exportn_orderby[$i], ".") === false) {
		$tmp_sql_order[] = $datasql_table.".".$datasql_exportn_orderby[$i];
	} else {
		$tmp_sql_order[] = $datasql_exportn_orderby[$i];
	}
}

if ($result = $db->Execute("select (select aUser from aUser where id = ".$datasql_table.".refid_aUser_update) as refid_aUser, ".implode(",",$tmp_sql_fields)." from ".$datasql_table.(count($datasql_table_fields_tables) ? ",".implode(",", $datasql_table_fields_tables) : "")." ".$where." order by ".implode(",",$tmp_sql_order))) {

	while (!$result->EOF) {

		$data = array();
		foreach ($datasql_table_fields_sql as $field) {
			$field = str_replace("`","",$field);
			if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
			if (in_array($field, $datasql_reference11_fields)) {

				if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."' limit 1")) {
					if (!$result->EOF) {
						$sfield = $datasql_reference11[$field]["field"];
						if (strpos($sfield, " as ")) $sfield = substr($sfield, strpos($sfield, " as ")+4, strlen($sfield));
						$stmp = $sresult->fields[$sfield];
					}
				}
				$data[$field] = mb_convert_encoding(htmlspecialchars_decode($stmp), 'UTF-16LE', 'UTF-8');

			} else {
				$stmp = $result->fields[$field];
				if (in_array($field, $decimal_type)) {
					$stmp = number_format($stmp, 2, ",", ".");
				}
				$data[$field] = mb_convert_encoding(htmlspecialchars_decode($stmp), 'UTF-16LE', 'UTF-8');
			}
		}

		if (in_array("refid_aUser_update", $datasql_exportn_fields)) {
			$data["refid_aUser_update"] = mb_convert_encoding($result->fields["refid_aUser"], 'UTF-16LE', 'UTF-8');
		}

		if (count($datasql_table_fields_ref1n)) {

			foreach ($datasql_table_fields_ref1n as $value) {

				$tmp_ref1n = substr($value,strrpos($value,"_")+1,255);
				$tmpvaluearr = array();

				if ($sresult = $db->Execute("select ".$tmp_ref1n." from ".$tmp_ref1n." where id in (select refid_".$tmp_ref1n." from ".$value." where refid_".$datasql_table." = '".$result->fields["id"]."' group by refid_".$tmp_ref1n.") order by ".$tmp_ref1n)) {
					while (!$sresult->EOF) {
						$tmpvaluearr[] = htmlspecialchars_decode($sresult->fields[$tmp_ref1n]);
						$sresult->MoveNext();
					}
				}

				$data[$value] = mb_convert_encoding(implode(", ", $tmpvaluearr), 'UTF-16LE', 'UTF-8');
			}
		}

		fputcsv($output, $data, ";", "\"");

		$result->MoveNext();
	}
}

fclose($output);

require(DEF_PATH_PRIVATE."apfra/lib/exit.inc.php");
die();

?>
