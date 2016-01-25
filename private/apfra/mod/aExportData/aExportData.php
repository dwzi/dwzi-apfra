<?php

$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";
$datetime = date("Y-m-d H:i:s");

$f_table = isset($_SESSION["psd"]["f_table"]) ? $_SESSION["psd"]["f_table"] : "";
$f_fields = isset($_SESSION["psd"]["f_fields"]) ? $_SESSION["psd"]["f_fields"] : "";
$f_filter = isset($_SESSION["psd"]["f_filter"]) ? $_SESSION["psd"]["f_filter"] : "";
$f_order = isset($_SESSION["psd"]["f_order"]) ? $_SESSION["psd"]["f_order"] : "";

$step = isset($_SESSION["psd"]["step"]) ? $_SESSION["psd"]["step"] : 1;

$steps = array(
		array("step" => 1, "desc" => "Tabelle", "ok" => ($f_table ? 1 : 0)),
		array("step" => 2, "desc" => "Felder", "ok" => ($f_fields ? 1 : 0)),
		array("step" => 3, "desc" => "Filter", "ok" => ($f_filter ? 1 : 0)),
		array("step" => 4, "desc" => "Sortierung", "ok" => ($f_order ? 1 : 0)),
		array("step" => 5, "desc" => "Vorschau", "ok" => 0)
);

if ($formaction == "prev" || $formaction == "next" || $formaction == "direct") {

	reload_page("?mod=".$module."&step=".$step."&f_table=".$f_table."&f_fields=".$f_fields."&f_filter=".$f_filter."&f_order=".$f_order);
}

$data = array();

switch ($step) {

	case 1:
		if ($result = $db->Execute("select id, aTable, aTableDesc from aTable where aTable not in ('".implode("','", $apfra_tables)."') order by aTable")) {

			while (!$result->EOF) {

				$data[] = array(
						"id" => $result->fields["id"],
						"aTable" => $result->fields["aTable"],
						"aTableDesc" => $result->fields["aTableDesc"]
				);

				$result->MoveNext();
			}
		}
		break;

	case 2:
	case 3:
	case 4:

		if ($step == 2) $tmp_data = explode(",", $f_fields);
		if ($step == 3) $tmp_data = explode(",", $f_filter);
		if ($step == 4) $tmp_data = explode(",", $f_order);

		$data2 = array();
		if ($f_table && $result = $db->Execute("select id, aField, aFieldDesc from aField where refid_aTable = (select id from aTable where aTable = '".$f_table."') order by aField")) {

			while (!$result->EOF) {

				if (!in_array($result->fields["aField"], $tmp_data)) {

					$data[] = array(
							"id" => $result->fields["id"],
							"aField" => $result->fields["aField"],
							"aFieldDesc" => $result->fields["aFieldDesc"]
					);

				} else {

					$data2[array_keys($tmp_data, $result->fields["aField"])[0]] = array(
							"id" => $result->fields["id"],
							"aField" => $result->fields["aField"],
							"aFieldDesc" => $result->fields["aFieldDesc"]
					);
				}

				$result->MoveNext();
			}
		}

		ksort($data2);
		$smarty->assign("data2", $data2);
		break;

	case 5:

		if ($result = $db->Execute("select aField, aFieldDesc from aField, aTable where aField.refid_aTable = aTable.id and aTable = '".$f_table."'")) {

			while (!$result->EOF) {

				if (substr($result->fields["aField"],0,6) == "refid_") {

					if ($sresult = $db->Execute("select aRefDesc from aRef where aRef = '".$result->fields["aField"]."' limit 1")) {

						if (!$sresult->EOF) {

							$apfra_db_desc[$f_table.".".$result->fields["aField"]] = $sresult->fields["aRefDesc"];
						}
					}

				} else {

					$apfra_db_desc[$f_table.".".$result->fields["aField"]] = $result->fields["aFieldDesc"];
				}

				$datasql_fields_add_default[$result->fields["aField"]] = "";
				$result->MoveNext();
			}
		}

		$tmpdescarr = array();
		$tmparr = explode(",", $f_fields);
		$tmpfieldarr = array();

		foreach ($tmparr as $field) {
			if (in_array($field, $datasql_reference11_fields)) {
				$tmpfieldarr[] = $field;
			} else {
				$tmpfieldarr[] = $field;
			}
			$tmpdescarr[] = $apfra_db_desc[$f_table.".".$field];
		}

		$f_fields = implode(",",$tmpfieldarr);

		if ($formaction == "export") {

			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename='.date("Y-m-d_H-i-s").'_export_'.$f_table.'.csv');

			$output = fopen('php://output', 'w');

			$tmpdescarr = array_map("utf8_decode", $tmpdescarr);
			fputcsv($output, $tmpdescarr, ";", "\"");

			if ($result = $db->Execute("select ".$f_fields." from ".$f_table.($f_order ? " order by ".$f_order : ""))) {

				while (!$result->EOF) {

					$tmpdata = array();
					foreach (explode(",", $f_fields) as $field) {

						if (in_array($field, $datasql_reference11_fields)) {

							$stmp = "";
							if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."' limit 1")) {
								if (!$result->EOF) {
									$sfield = $datasql_reference11[$field]["field"];
									if (strpos($sfield, " as ")) $sfield = substr($sfield, strpos($sfield, " as ")+4, strlen($sfield));
									$stmp = $sresult->fields[$sfield];
								}
							}
							$tmpdata[] = mb_convert_encoding($stmp, 'UTF-16LE', 'UTF-8');

						} else {

							$tmpdata[] = mb_convert_encoding($result->fields[$field], 'UTF-16LE', 'UTF-8');
						}
					}
					fputcsv($output, $tmpdata, ";", "\"");

					$result->MoveNext();
				}
			}

			fclose($output);

			require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");
			die();

		} else {

			if ($result = $db->Execute("select ".$f_fields." from ".$f_table.($f_order ? " order by ".$f_order : "")." limit 0,10")) {

				while (!$result->EOF) {

					$tmpdata = array();
					foreach (explode(",", $f_fields) as $field) {

						if (in_array($field, $datasql_reference11_fields)) {

							$stmp = "";
							if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."' limit 1")) {
								if (!$result->EOF) {
									$sfield = $datasql_reference11[$field]["field"];
									if (strpos($sfield, " as ")) $sfield = substr($sfield, strpos($sfield, " as ")+4, strlen($sfield));
									$stmp = $sresult->fields[$sfield];
								}
							}
							$tmpdata[] = $stmp;

						} else {

							$tmpdata[] = $result->fields[$field];
						}
					}
					$data[] = $tmpdata;

					$result->MoveNext();
				}
			}

			$smarty->assign("data_header", $tmpdescarr);
		}
		break;
}

$smarty->assign("data", $data);

$smarty->assign("steps", $steps);
$smarty->assign("step", $step);

$smarty->assign("f_table", $f_table);
$smarty->assign("f_fields", $f_fields);
$smarty->assign("f_filter", $f_filter);
$smarty->assign("f_order", $f_order);

?>
