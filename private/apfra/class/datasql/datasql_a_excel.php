<?php

require(DEF_PATH_PRIVATE."apfra/lib/phpexcel/Classes/PHPExcel.php");

$ftemplate = isset($_SESSION["psd"]["ftemplate"]) ? $_SESSION["psd"]["ftemplate"] : "";
$fdata = isset($_SESSION["psd"]["fdata"]) ? $_SESSION["psd"]["fdata"] : "";

if ($id > 0 && $ftemplate) {

	$sql_wherearr = array();

	$datasql_excel_values = array();
	if (file_exists(DEF_PATH_PRIVATE."mod/".$module."/".$module."_a_excel.inc.php")) {
		require(DEF_PATH_PRIVATE."mod/".$module."/".$module."_a_excel.inc.php");
	}

	$values = array();
	if (count($datasql_excel_values)) {
		foreach ($datasql_excel_values as $key => $valuearr) {
			if ($key == $ftemplate) {
				$values = $valuearr;
			}
		}
	}

	$sql_fieldarr = array();
	foreach ($values as $sheetarr) {
		foreach ($sheetarr as $cellarr) {
			foreach ($cellarr as $field) {
				$sql_fieldarr[] = $field;
			}
		}
	}

	$sql_tablearr = array();
	$sql_tablearr[] = $datasql_table;
	foreach ($sql_fieldarr as $field) {
		$tmpvar = explode(".", $field);
		if (!in_array($tmpvar[0], $sql_tablearr)) {
			$sql_tablearr[] = $tmpvar[0];
		}
	}

	$query = "select aField from aField where refid_aTable = (select id from aTable where aTable = '".$datasql_table."' limit 1) and aField like 'refid_%'";
	if ($result = $db->Execute($query)) {
		while (!$result->EOF) {

			if (in_array(substr($result->fields["aField"], 6, 255), $sql_tablearr)) {

				$sql_wherearr[] = $datasql_table.".".$result->fields["aField"]." = ".substr($result->fields["aField"], 6, 255).".id";
			}

			$result->MoveNext();
		}
	}

	$inputFileName = DEF_PATH_PRIVATE."doc/".$ftemplate;

	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objReader->setReadDataOnly(true);
	$objReader->setLoadAllSheets();
	$document = $objReader->load($inputFileName);

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

				if (!in_array($fields_col["type"], array("reference1n", "reference1nsub", "aLogDB", "password", "file", "image", ""))) {
					$datasql_fields_edit[] = $fields_col["field"];
				} elseif ($fields_col["type"] == "reference1n") {
					$datasql_fields_edit_ref1n[] = $fields_col["field"];
				} elseif ($fields_col["type"] == "reference1nsub") {
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

	$sql_wherearr[] = $datasql_table.".id = '".$id."'";
	$query = "select ".$datasql_table.".aLastUpdate, (select aUser from aUser where id = ".$datasql_table.".refid_aUser_update) as ref_benutzer, ".implode(",",$sql_fieldarr)." from ".implode(",",$sql_tablearr)." where ".implode(" and ", $sql_wherearr)." limit 1";

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

	if (($id > 0) && ($result = $db->Execute($query))) {

		if (!$result->EOF) {

			$data["aLastUpdate"] = $result->fields["aLastUpdate"];
			$data["ref_benutzer"] = $result->fields["ref_benutzer"];

			foreach ($sql_fieldarr as $field) {

				$tmpvar = explode(".", $field);
				$field = $tmpvar[1];

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
		}
	}

	if (count($data)) {

		if (count($data_convert_datetime)) {
			foreach ($data_convert_datetime as $key => $format) {
				if (in_array($datasql_table.".".$key, $sql_fieldarr)) {
					$data[$key] = date(convertMoment2PHP($format),strtotime($data[$key]));
				}
			}
		}

		if (count($data_convert_date)) {
			foreach ($data_convert_date as $key => $format) {
				if (in_array($datasql_table.".".$key, $sql_fieldarr)) {
					$data[$key] = date(convertMoment2PHP($format),strtotime($data[$key]));
				}
			}
		}

		if (count($values)) {
			foreach ($values as $sheet => $valuearr) {

				$document->setActiveSheetIndexByName($sheet);

				foreach ($valuearr as $range => $fieldarr) {

					foreach ($fieldarr as $field) {

						$tmpvar = explode(".", $field);
						$field = $tmpvar[1];

						if (substr($field,0,6) == "refid_") {
							$document->getActiveSheet()->setCellValue($range, $data["ref"][$field]);
						} elseif (substr($field,-9) == "_fileinfo") {
							$document->getActiveSheet()->setCellValue($range, $data[$field]["name"]." (".$data[$field]["size"]." bytes)");
						} else {
							$document->getActiveSheet()->setCellValue($range, $data[$field]);
						}
					}
				}
			}
		}
	}

	$temp_file = tempnam(sys_get_temp_dir(), 'excel_'.$module.'_'.$id.'.xlsx');
	$objWriter = PHPExcel_IOFactory::createWriter($document, 'Excel2007');
	$objWriter->save($temp_file);

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.date("Y-m-d_H-i-s").'_excel_'.$module.'_'.$id.'.xlsx"');
	header('Cache-Control: max-age=0');
	readfile($temp_file);
	unlink($temp_file);

	require(DEF_PATH_PRIVATE."apfra/lib/exit.inc.php");
	die();
}

?>
