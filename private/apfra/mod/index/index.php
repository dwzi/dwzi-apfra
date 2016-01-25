<?php

if ($logged_in) {

	$search = isset($_SESSION["psd"]["s"]) ? $_SESSION["psd"]["s"] : "";
	$data = array();

	if ($search) {

		$data_db = array();
		if ($result = $db->Execute("select id, aTable, aTableDesc from aTable where aTable not in ('".implode("','", $apfra_tables)."')")) {

			while (!$result->EOF) {

				if ($apfra_rights[$result->fields["aTable"]]["sum"] && file_exists(DEF_PATH_PRIVATE."config".DS."datasql".DS.$result->fields["aTable"].".datasql.php")) {

					if (file_exists(DEF_PATH_PRIVATE."apfra".DS."config".DS."datasql".DS.$result->fields["aTable"].".datasql.php")) {

						require(DEF_PATH_PRIVATE."apfra".DS."config".DS."datasql".DS.$result->fields["aTable"].".datasql.php");

					} elseif (file_exists(DEF_PATH_PRIVATE."config".DS."datasql".DS.$result->fields["aTable"].".datasql.php")) {

						require(DEF_PATH_PRIVATE."config".DS."datasql".DS.$result->fields["aTable"].".datasql.php");
					}

					$tmpfields = array();
					$tmpfields_desc = array();
					if ($fresult = $db->Execute("select aField, aFieldDesc from aField, aFieldType where aField.refid_aTable = '".$result->fields["id"]."' and aField.refid_aFieldType = aFieldType.id and aField in ('".implode("','", $datasql_table_fields)."')")) {

						while (!$fresult->EOF) {

							$tmpfields[$fresult->fields["aField"]] = $fresult->fields["aField"];

							if (substr($fresult->fields["aField"],0,6) == "refid_") {

								if ($sresult = $db->Execute("select aRefDesc from aRef where aRef = '".$fresult->fields["aField"]."' limit 1")) {

									if (!$sresult->EOF) {

										$tmpfields_desc[$fresult->fields["aField"]] = $sresult->fields["aRefDesc"];
									}
								}

							} else {

								$tmpfields_desc[$fresult->fields["aField"]] = $fresult->fields["aFieldDesc"];
							}

							$fresult->MoveNext();
						}
					}

					$ttmpfields = $tmpfields;
					$ttmpfields_desc = $tmpfields_desc;

					$tmpfields = array();
					$tmpfields_desc = array();
					foreach ($datasql_table_fields as $field) {
						$tmpfields[] = isset($ttmpfields[$field]) ? $ttmpfields[$field] : $field;
						$tmpfields_desc[] = isset($ttmpfields_desc[$field]) ? $ttmpfields_desc[$field] : $field;
					}

					$data_db[] = array(
							"table" => $result->fields["aTable"],
							"aTableDesc" => $result->fields["aTableDesc"],
							"fields" => $tmpfields,
							"fieldsDesc" => $tmpfields_desc,
							"orderby" => $datasql_table_orderby
					);
				}

				$result->MoveNext();
			}
		}

		foreach ($data_db as $dbarr) {

			$where = "where ";
			foreach ($dbarr["fields"] as $field) {

				if (in_array($field, $datasql_reference11_fields)) {

					$tmpwhere = "";
					foreach ($datasql_reference11[$field]["search"] as $svalue) {

						$tmpwhere .= $field." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$search."%') or ";
					}

					$where .= $tmpwhere;

				} else {

					$where .= $field." like '%".$search."%' or ";
				}
			}
			$where = substr($where, 0, -3);

			$query = "select id, ".implode(',',$dbarr["fields"])." from ".$dbarr["table"]." ".$where." order by ".implode(",",$dbarr["orderby"]);

			if ($result = $db->Execute($query)) {

				if (!$result->EOF) {

					$data[$dbarr["table"]]["desc"] = $dbarr["fieldsDesc"];
				}
				while (!$result->EOF) {

					$tmparr = array();
					foreach ($dbarr["fields"] as $field) {

						$tmparr[$field] = "";

						if (in_array($field, $datasql_reference11_fields)) {

							if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."'")) {
								if (!$sresult->EOF) {

									$tmpfield = $datasql_reference11[$field]["field"];
									if (strpos($tmpfield, " as ")) $tmpfield = substr($tmpfield, strpos($tmpfield, " as ")+4, strlen($tmpfield));

									$tmparr[$field] = $sresult->fields[$tmpfield];
								}
							}

						} else {

							$tmparr[$field] = $result->fields[$field];
						}
					}
					$data[$dbarr["table"]][$result->fields["id"]] = $tmparr;

					$result->MoveNext();
				}

			}

		}

	}

	$smarty->assign("search", $search);
	$smarty->assign("data", $data);
}

?>
