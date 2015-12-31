<?php

require(DEF_PATH_PRIVATE."apfra/lib/phpword/Autoloader.php");

use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

\PhpOffice\PhpWord\Autoloader::register();
\PhpOffice\PhpWord\Settings::loadConfig();

$ftemplate = isset($_SESSION["psd"]["ftemplate"]) ? $_SESSION["psd"]["ftemplate"] : "";
$fdata = isset($_SESSION["psd"]["fdata"]) ? $_SESSION["psd"]["fdata"] : "";

if ($id > 0 && $ftemplate) {

	$document = new \PhpOffice\PhpWord\TemplateProcessor(DEF_PATH_PRIVATE."doc/".$ftemplate);

	$datasql_fields_edit = array();
	$datasql_fields_edit_ref1n = array();
	$datasql_fields_edit_ref1nsub = array();
	$datasql_fields_edit_files = array();
	$datasql_fields_edit_filesinfo = array();
	$data_convert_datetime = array();
	$data_convert_date = array();

	foreach ($datasql_edit_fields as $fields_tab) {
		foreach ($fields_tab["row"] as $fields_row) {
			foreach ($fields_row["col"] as $fields_col) {

				if (!in_array($fields_col["type"], array("reference1n", "reference1nsub", "reference1n_min", "aLogDB", "password", "file", "image", ""))) {
					$datasql_fields_edit[] = $fields_col["field"];
				} elseif ($fields_col["type"] == "reference1n") {
					$datasql_fields_edit_ref1n[] = $fields_col["field"];
				} elseif (in_array($fields_col["type"], array("reference1nsub", "reference1n_min"))) {
					$datasql_fields_edit_ref1nsub[] = $fields_col["field"];
				} elseif ($fields_col["type"] == "file" || $fields_col["type"] == "image") {
					$datasql_fields_edit_files[] = $fields_col["field"];
					$datasql_fields_edit_filesinfo[] = $fields_col["field"]."_fileinfo";
				}
				if ($fields_col["type"] == "datetime") {
					$data_convert_datetime[$fields_col["field"]] = isset($fields_col["format"]) ? $fields_col["format"] : "DD.MM.YYYY HH:mm";
				}
				if ($fields_col["type"] == "date") {
					$data_convert_date[$fields_col["field"]] = isset($fields_col["format"]) ? $fields_col["format"] : "DD.MM.YYYY";
				}
			}
		}
	}

	if (count($datasql_fields_edit_ref1n)) {

		foreach ($datasql_fields_edit_ref1n as $ref1n) {

			$tmp = substr($ref1n, 6, 255);
			$tmparr = explode("_", $tmp);
			$tmpref1 = "refid_".$tmparr[0];
			$tmprefn = "refid_".$tmparr[1];
			$tmptab = $ref1n;

			if (!array_key_exists($ref1n, $datasql_reference1n)) {
				$tmptab = "ref1n_".$tmparr[1]."_".$tmparr[0];
			}

			$datasql_reference1n[$ref1n] = array(
					"table"      => $tmptab,
					"col1_key"   => $tmpref1,
					"coln_key"   => $tmprefn,
					"col1_table" => $datasql_reference11[$tmpref1]["table"],
					"coln_table" => $datasql_reference11[$tmprefn]["table"],
					"coln_valuenorm" => (strpos($datasql_reference11[$tmprefn]["field"], " as ") !== false ? substr($datasql_reference11[$tmprefn]["field"], strpos($datasql_reference11[$tmprefn]["field"], " as ")+4, strlen($datasql_reference11[$tmprefn]["field"])) : $datasql_reference11[$tmprefn]["field"]),
					"coln_value" => array($datasql_reference11[$tmprefn]["field"]),
					"coln_order" => $datasql_reference11[$tmprefn]["orderby"]
			);

			if ($sresult = $db->Execute("select aRefDesc from aRef where aRef = '".$tmprefn."' limit 1")) {

				if (!$sresult->EOF) {

					$apfra_db_desc[$ref1n] = $sresult->fields["aRefDesc"];
				}
			}
		}
	}

	$data = array();

	if ($fdata) {

		$fdataarr = explode(";", $fdata);

		if (count($fdataarr)) {

			foreach ($fdataarr as $value) {

				if ($value) {

					$valuearr = explode("=", $value);

					if (count($valuearr) == 2) {

						$data[$valuearr[0]] = $valuearr[1];
					}
				}
			}
		}
	}

	$query = "select aLastUpdate, (select aUser from aUser where id = ".$datasql_table.".refid_aUser_update) as ref_benutzer, ".implode(",",array_merge($datasql_fields_edit, $datasql_fields_edit_filesinfo))." from ".$datasql_table." where id = '".$id."' limit 1";
	if (($id > 0) && ($result = $db->Execute($query))) {

		if (!$result->EOF) {

			$data["aLastUpdate"] = $result->fields["aLastUpdate"];
			$data["ref_benutzer"] = $result->fields["ref_benutzer"];
			foreach (array_merge($datasql_fields_edit, $datasql_fields_edit_filesinfo) as $field) {
				$field = str_replace("`","",$field);
				if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
				if (!array_key_exists($field, $data)) {
					$data[$field] = $result->fields[$field];
				}

				if (array_key_exists($field, $datasql_reference11)) {

					$data["ref"][$field] = "";
					if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."' limit 1")) {

						if (!$sresult->EOF) {

							$tmpidx = $datasql_reference11[$field]["field"];
							if (strpos($tmpidx," as ") !== false) {
								$tmpidx = substr($tmpidx, strpos($tmpidx, " as ")+4, 255);
							}

							$data["ref"][$field] = $sresult->fields[$tmpidx];
						}
					}
				}

				if (in_array($field, $datasql_fields_edit_filesinfo)) {

					if (!array_key_exists($field, $data)) {
						$data[$field] = unserialize($data[$field]);
					}
				}
			}

			if (count($datasql_fields_edit_ref1nsub)) {

				foreach ($datasql_fields_edit_ref1nsub as $value) {

					if (substr($value,0,6) == "ref1n_" && substr($value,6,strrpos($value,"_")-6) == $datasql_table) {

						$tmp_ref1n = substr($value,strrpos($value,"_")+1,255);
						$tmpvaluearr = array();

						if ($sresult = $db->Execute("select ".$tmp_ref1n." from ".$tmp_ref1n." where id in (select refid_".$tmp_ref1n." from ".$value." where refid_".$datasql_table." = '".$result->fields["id"]."' group by refid_".$tmp_ref1n.") order by ".$tmp_ref1n)) {
							while (!$sresult->EOF) {
								$tmpvaluearr[] = $sresult->fields[$tmp_ref1n];
								$sresult->MoveNext();
							}
						}

						if (!array_key_exists($value, $data)) {
							$data[$value] = implode(", ", $tmpvaluearr);
						}
					}
				}
			}
		}
	}

	if (count($data)) {

		if (count($data_convert_datetime)) {
			foreach ($data_convert_datetime as $key => $format) {
				$data[$key] = date(convertMoment2PHP($format),strtotime($data[$key]));
			}
		}

		if (count($data_convert_date)) {
			foreach ($data_convert_date as $key => $format) {
				$data[$key] = date(convertMoment2PHP($format),strtotime($data[$key]));
			}
		}

		foreach ($data as $key => $value) {
			if ($key != "ref") {
				if (substr($key,0,6) == "refid_") {
					$document->setValue($key, $data["ref"][$key]);
				} elseif (substr($key,-9) == "_fileinfo") {
					$document->setValue(''.substr($key, 0, -9).'', $value["name"]." (".$value["size"]." bytes)");
				} else {
					$document->setValue(''.$key.'', addslashes($value));
				}
			}
		}
	}

	$temp_file = tempnam(sys_get_temp_dir(), 'word_'.$module.'_'.$id.'.docx');
//	$document->save($temp_file);
	$document->saveAs($temp_file);

	header('Content-Type: application/vnd.ms-word');
	header('Content-Disposition: attachment; filename="'.date("Y-m-d_H-i-s").'_word_'.$module.'_'.$id.'.docx"');
	header('Cache-Control: max-age=0');
	readfile($temp_file);
	unlink($temp_file);

	require(DEF_PATH_PRIVATE."apfra/lib/exit.inc.php");
	die();
}

?>
