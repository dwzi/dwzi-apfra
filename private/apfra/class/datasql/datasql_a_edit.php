<?php

$tab = isset($_SESSION["psd"]["t"]) ? $_SESSION["psd"]["t"] : "";
$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";

$backmod = isset($_SESSION["psd"]["backmod"]) ? $_SESSION["psd"]["backmod"] : "";
$backid = isset($_SESSION["psd"]["backid"]) ? $_SESSION["psd"]["backid"] : "";
$backt = isset($_SESSION["psd"]["backt"]) ? $_SESSION["psd"]["backt"] : "";

$errors = 0;
$datetime = date("Y-m-d H:i:s");

$tmp_edit_fields = array();
for ($i=0; $i < count($datasql_edit_fields); $i++) {
	if (isset($apfra_rights[$datasql_edit_fields[$i]["tab"]])) {
		if ($apfra_rights[$datasql_edit_fields[$i]["tab"]]["sum"] > 0) {
			$tmp_edit_fields[] = $datasql_edit_fields[$i];
		}
	} else {
		$tmp_edit_fields[] = $datasql_edit_fields[$i];
	}
}
$datasql_edit_fields = $tmp_edit_fields;

$datasql_fields_edit = array();
$datasql_fields_edit_ref1n = array();
$datasql_fields_edit_ref1n_min = array();
$datasql_fields_edit_ref1nsub = array();
$datasql_fields_edit_password = array();
$datasql_fields_edit_files = array();
$datasql_fields_edit_filesinfo = array();
$datasql_fields_edit_files_uploaded = array();
$datasql_fields_edit_fixed_fields = array();
$datasql_fields_edit_fixed_edit_fields = array();

foreach ($datasql_edit_fields as $fields_tab) {
	foreach ($fields_tab["row"] as $fields_row) {
		foreach ($fields_row["col"] as $fields_col) {

			if (!in_array($fields_col["type"], array("reference1n", "reference1n_min", "reference1nsub", "fixed_fields", "fixed_edit_fields", "aLogDB", "password", "file", "image", ""))) {
				$datasql_fields_edit[] = $fields_col["field"];
			} elseif ($fields_col["type"] == "password") {
				$datasql_fields_edit_password[] = $fields_col["field"];
			} elseif ($fields_col["type"] == "reference1n") {
				$datasql_fields_edit_ref1n[] = $fields_col["field"];
			} elseif ($fields_col["type"] == "reference1n_min") {
				$datasql_fields_edit_ref1n_min[] = $fields_col["field"];
			} elseif ($fields_col["type"] == "reference1nsub") {
				$datasql_fields_edit_ref1nsub[] = $fields_col["field"];
			} elseif ($fields_col["type"] == "fixed_fields") {
				$datasql_fields_edit[] = $fields_col["field"];
				$datasql_fields_edit_fixed_fields[] = $fields_col["field"];
			} elseif ($fields_col["type"] == "fixed_edit_fields") {
				$datasql_fields_edit[] = $fields_col["field"];
				$datasql_fields_edit_fixed_edit_fields[] = $fields_col["field"];
			} elseif ($fields_col["type"] == "file" || $fields_col["type"] == "image") {
				$datasql_fields_edit_files[] = $fields_col["field"];
				$datasql_fields_edit_filesinfo[] = $fields_col["field"]."_fileinfo";
			}
		}
	}
}

if (count(array_merge($datasql_fields_edit_ref1n, $datasql_fields_edit_ref1n_min))) {

	foreach (array_merge($datasql_fields_edit_ref1n, $datasql_fields_edit_ref1n_min) as $ref1n) {

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

if (substr($formaction,0,4) == "del_") {

	$tmpfr = isset($_SESSION["psd"]["fr"]) ? $_SESSION["psd"]["fr"] : "";

	if ($apfra_rights[$tmpfr]["del"] == 0) {

		reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort."&a=".$action."&id=".$id."&t=".$tab);
	}

	$did = isset($_SESSION["psd"]["did"]) ? $_SESSION["psd"]["did"] : "";
	if ($did) {

		$delta = array();
		if ($result = $db->Execute("select * from ".substr($formaction,4,255)." where id = '".$did."' limit 1")) {
			if (!$result->EOF) {
				foreach ($result->fields as $key => $field) {
					if (!is_numeric($key)) {
						$delta[$key] = $field;
					}
				}
				$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
				unset($delta["id"]);
			}
		}

		$query = "delete from ".substr($formaction,4,255)." where id = '".$did."' limit 1";
		if (!$result = $db->Execute($query)) {
echo $query;
die();
		} else {

			$apfra_log_db->delete(substr($formaction,4,255), $did, $delta);
		}
		reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort."&a=".$action."&id=".$id."&t=".$tab);
	}
}

if (substr($formaction,0,5) == "delf_") {

	$tmpcol = substr($formaction,5,255);

	$delta = array();
	if ($result = $db->Execute("select ".$tmpcol."_fileinfo, aLastUpdate from ".$datasql_table." where id = '".$id."' limit 1")) {
		if (!$result->EOF) {
			foreach ($result->fields as $key => $field) {
				if (!is_numeric($key)) {
					$delta[$key] = $field;
				}
			}
			$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
		}
	}

	$query = "update ".$datasql_table." set ".$tmpcol." = NULL, ".$tmpcol."_fileinfo = NULL where id = '".$id."' limit 1";
	if (!$result = $db->Execute($query)) {
echo $query;
die();
	} else {

		$apfra_log_db->update($datasql_table, $id, $delta);
	}
	reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort."&a=".$action."&id=".$id."&t=".$tab);
}

if ($formaction == "save" || $formaction == "saveback") {

//	$fields = "";
	$fieldarr = array();
	$fieldarr_ref1n = array();
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,2) == "f_") {
			$tmpref = false;
			if (count($datasql_reference1n)) {
				foreach ($datasql_reference1n as $ref1n_arr) {
					if (isset($ref1n_arr["coln_key"]) && substr($key,2,255) == $ref1n_arr["coln_key"]) {
						$tmpref = true;
					}
				}
			}
			if (substr($key,0,8) == "f_refid_" && $value == "") {
				$value = "0";
			}
			if ($tmpref) {
				$fieldarr_ref1n[substr($key,2,255)] = $value;
			} else {
				$fieldarr[substr($key,2,255)] = $value;
			}
		}
	}

	if (count($fieldarr)) {

		foreach ($datasql_fields_edit as $field) {
			if (!isset($fieldarr[$field])) {
				foreach ($datasql_fields_add_default as $fieldadd => $value) {
					if ($field == $fieldadd) {
						$fieldarr[$fieldadd] = $value;
					}
				}
			}
		}

		if (count($datasql_fields_edit_password)) {
			foreach ($datasql_fields_edit_password as $field) {
				$tmp_pwd1 = $fieldarr[$field];
				$tmp_pwd2 = isset($_SESSION["psd"]["fp_".$field]) ? $_SESSION["psd"]["fp_".$field] : "";
				if ($tmp_pwd1 || $tmp_pwd2) {
					if ($tmp_pwd1 != $tmp_pwd2) {
						unset($fieldarr[$field]);
	// TODO set error
					}
				} else {
					unset($fieldarr[$field]);
				}
			}
		}

		if (count($datasql_fields_edit_files)) {
			foreach ($datasql_fields_edit_files as $field) {

				if ($_SESSION["psd"]["fd_".$field]["error"] == 0) {

					$tmp_finfo = new finfo(FILEINFO_MIME_TYPE);

					if ($action == "image" && false === $ext = array_search($tmp_finfo->file($_FILES["fd_".$field]["tmp_name"]), array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'), true)) {
					// TODO set error
						@unlink($_FILES["fd_".$field]["tmp_name"]);

					} else {

						$tmparr = array(
								"name" => $_SESSION["psd"]["fd_".$field]["name"],
								"type" => $_SESSION["psd"]["fd_".$field]["type"],
								"size" => $_SESSION["psd"]["fd_".$field]["size"]
						);
						$fieldarr[$field] = addslashes(file_get_contents(sys_get_temp_dir().$_SESSION["psd"]["fd_".$field]["tmpstamp"]));
						$fieldarr[$field."_fileinfo"] = serialize($tmparr);

						@unlink(sys_get_temp_dir().$_SESSION["psd"]["fd_".$field]["tmpstamp"]);
						$datasql_fields_edit_files_uploaded[] = $field."_fileinfo";
					}
				}
			}
		}

		if (count($datasql_fields_edit_fixed_edit_fields)) {
			print_r($fieldarr);
			foreach ($datasql_fields_edit_fixed_edit_fields as $field) {
				unset($fieldarr[$field]);
			}
		}
	}

	if (!$errors) {

//		$fields = implode(", ", $fieldarr);

		$fieldarr["aLastUpdate"] = $datetime;
		$fieldarr["refid_aUser_update"] = $_SESSION["psu"]["id"];

		if ($id > 0) {
			$query = "update ".$datasql_table." set ";
			if (count($fieldarr)) {
				foreach ($fieldarr as $key => $value) {
					if ($value == '') {
						$query .= $key." = NULL, ";
					} else {
						if (in_array($key, $datasql_fields_edit_password)) {
							$query .= $key." = password('".$value."'), ";
						} else {
							$query .= $key." = '".$value."', ";
						}
					}
				}
				$query = substr($query, 0, -2);
			}
			$query .= " where id = '".$id."'";
		} else {
			$query = "insert into ".$datasql_table." (";
			if (count($fieldarr)) {
				foreach ($fieldarr as $key => $value) {
					$query .= "`".$key."`, ";
				}
				$query = substr($query, 0, -2);
				$query .= ") values (";
				foreach ($fieldarr as $key => $value) {
					if ($value == '') {
						$query .= "NULL, ";
					} else {
						if (in_array($key, $datasql_fields_edit_password)) {
							$query .= "password('".$value."'), ";
						} else {
							$query .= "'".$value."', ";
						}
					}
				}
				$query = substr($query, 0, -2);
				$query .= ")";
			}
		}

		if (count($fieldarr_ref1n)) {
			foreach ($fieldarr_ref1n as $key => $value) {

				foreach ($datasql_reference1n as $ref1n_arr) {
					if (isset($ref1n_arr["coln_key"]) && $key == $ref1n_arr["coln_key"] && $value) {

						if ($apfra_rights[$ref1n_arr["coln_table"]]["ins"] == 1) {

							if (is_array($value)) {
								$tmpvalarr = $value;
								$queryref = "delete from ".$ref1n_arr["table"]." where ".$ref1n_arr["col1_key"]." = '".$id."'";
								$db->Execute($queryref);
							} else {
								$tmpvalarr = array($value);
							}

							foreach ($tmpvalarr as $tmpval) {

								$queryref = "insert into ".$ref1n_arr["table"]." (".$ref1n_arr["col1_key"].", ".$ref1n_arr["coln_key"].", aLastUpdate, refid_aUser_update) values ('".$id."', '".$tmpval."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
								if (!$resultref = $db->Execute($queryref)) {
									$errors++;
	echo $queryref;
	die();
								}
							}
						}
					}
				}
			}
		}

		$delta = array();
		if ($id > 0) {
			if ($result = $db->Execute("select aLastUpdate, ".implode(",",array_merge($datasql_fields_edit, $datasql_fields_edit_password, $datasql_fields_edit_files_uploaded))." from ".$datasql_table." where id = '".$id."' limit 1")) {
				if (!$result->EOF) {
					$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
					foreach (array_merge($datasql_fields_edit, $datasql_fields_edit_password, $datasql_fields_edit_files_uploaded) as $field) {
						if (!in_array($field, array_merge($datasql_fields_edit_password, $datasql_fields_edit_fixed_edit_fields))) {
							if ($fieldarr[$field] != $result->fields[$field]) {
								$delta[$field] = $result->fields[$field];
							}
						} else {
							if (isset($fieldarr[$field])) {
								$delta[$field] = "-";
							}
						}
					}
				}
			}
		}

		if (!$result = $db->Execute($query)) {
echo $query;
die();
			$errors++;
		} else {

			if ($id > 0) {

				$apfra_log_db->update($datasql_table, $id, $delta);
			}

			if (file_exists(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_post_save.inc.php")) {
				require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_post_save.inc.php");
			} elseif (file_exists(DEF_PATH_PRIVATE."mod/".$module."/".$module."_post_save.inc.php")) {
				require(DEF_PATH_PRIVATE."mod/".$module."/".$module."_post_save.inc.php");
			}

			if ($formaction == "save") {

				if ($id == 0) {
					if ($tmpid = $db->Insert_ID()) {
						reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort."&a=".$action."&id=".$tmpid."&t=".$tab);
					} else {
						reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort);
					}
				} else {
					reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort."&a=".$action."&id=".$id."&t=".$tab);
				}
			}
		}

		if ($formaction == "saveback") {
			if ($backmod && $backid) {
				reload_page("?mod=".$backmod."&a=edit&id=".$backid."&t=".$backt);
			} else {
				reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort);
			}
		}
	}
}

if (count($datasql_reference1n)) {

	foreach ($datasql_reference1n as $refkey => $refarr) {

		if (in_array($refkey, array_merge($datasql_fields_edit_ref1n, $datasql_fields_edit_ref1n_min))) {

			$tmpdata = "dataref1n_".$refkey;
			$$tmpdata = array();

			if ($result = $db->Execute("select ".$refarr["table"].".id as dataid, ".$refarr["coln_table"].".id, ".implode(",", $refarr["coln_value"])." from ".$refarr["col1_table"].", ".$refarr["table"].", ".$refarr["coln_table"]." where ".$refarr["col1_table"].".id = ".$refarr["table"].".".$refarr["col1_key"]." and ".$refarr["coln_table"].".id = ".$refarr["table"].".".$refarr["coln_key"]." and ".$refarr["col1_table"].".id = '".$id."' order by ".implode(",", $refarr["coln_order"]))) {

				while (!$result->EOF) {

					$datatmp = array();

					$datatmp["id"] = $result->fields["id"];
					$datatmp["dataid"] = $result->fields["dataid"];
					foreach ($refarr["coln_value"] as $value) {
						if (strpos($value," as ") !== false) {
							$value = substr($value, strpos($value, " as ")+4, 255);
						}
						$datatmp[$value] = $result->fields[$value];
					}

					array_push($$tmpdata, $datatmp);

					$result->MoveNext();
				}
			}
			$smarty->assign($tmpdata, $$tmpdata);
		}
	}
}

if (count($datasql_fields_edit_fixed_fields)) {

	$tmpdata = array();

	if ($result = $db->Execute("select id, aTable, aTableDesc from aTable where aTable not in ('".implode("','", $apfra_tables)."') and aTable = (select aModule from aModule where id = '".$id."') order by aTable")) {

		while (!$result->EOF) {

			$tmpsub = array();
			if ($sresult = $db->Execute("select aField, aFieldDesc from aField where refid_aTable = '".$result->fields["id"]."' order by aField")) {

				while (!$sresult->EOF) {

					$tmpdesc = "";
					if (substr($sresult->fields["aField"],0,6) == "refid_") {
						if ($s2result = $db->Execute("select aRefDesc from aRef where aRef = '".$sresult->fields["aField"]."' limit 1")) {
							if (!$s2result->EOF) {
								$tmpdesc = $s2result->fields["aRefDesc"];
							}
						}
					} else {
						$tmpdesc = $sresult->fields["aFieldDesc"];
					}

					$tmpsub[] = array(
						"field" => $sresult->fields["aField"],
						"desc"  => $tmpdesc
					);
					$sresult->MoveNext();
				}
			}

			$tmpdata[] = array(
				"table"  => $result->fields["aTable"],
				"desc"   => $result->fields["aTableDesc"],
				"fields" => $tmpsub
			);

			$result->MoveNext();
		}
	}

	$smarty->assign("data_fixed_fields", $tmpdata);
}

if (count($datasql_fields_edit_ref1nsub)) {

	foreach ($datasql_fields_edit_ref1nsub as $ref1nsub) {

		$tmptab = substr($ref1nsub,6,255);
		if ($result = $db->Execute("select aField, aFieldDesc from aField, aTable where aField.refid_aTable = aTable.id and aTable = '".$tmptab."'")) {

			while (!$result->EOF) {

				if (substr($result->fields["aField"],0,6) == "refid_") {

					if ($sresult = $db->Execute("select aRefDesc from aRef where aRef = '".$result->fields["aField"]."' limit 1")) {

						if (!$sresult->EOF) {

							$apfra_db_desc[$tmptab.".".$result->fields["aField"]] = $sresult->fields["aRefDesc"];
						}
					}

				} else {

					$apfra_db_desc[$tmptab.".".$result->fields["aField"]] = $result->fields["aFieldDesc"];
				}

				$result->MoveNext();
			}
		}
	}
}

foreach ($datasql_edit_fields as $keytab => $fields_tab) {
	foreach ($fields_tab["row"] as $keyrow => $fields_row) {
		foreach ($fields_row["col"] as $keycol => $fields_col) {

			if (in_array($fields_col["type"], array("reference_combobox", "reference_select"))) {

				$refarr = $datasql_reference11[$fields_col["field"]];

				$tmpdata = "dataref_".$fields_col["field"];
				$$tmpdata = array();
				if ($result = $db->Execute("select id, ".$refarr["field"]." from ".$refarr["table"]." order by ".implode(",", $refarr["orderby"]))) {

					while (!$result->EOF) {

						$value = $refarr["field"];
						$datatmp = array();

						$datatmp["id"] = $result->fields["id"];
						if (strpos($value," as ") !== false) {
							$value = substr($value, strpos($value, " as ")+4, 255);
						}
						$datatmp["field"] = $result->fields[$value];

						array_push($$tmpdata, $datatmp);

						$result->MoveNext();
					}
				}
				$smarty->assign($tmpdata, $$tmpdata);
			}

			if ($fields_col["type"] == "reference1nsub") {

				$tmpfield = substr($fields_col["field"], 6, 255);

				$tmpdata = "dataref1nsub_".$tmpfield;
				$$tmpdata = array();

				$tmptotal = array();
				if (isset($fields_col["ref_total"]) && count($fields_col["ref_total"])) {
					foreach ($fields_col["ref_total"] as $field) {
						$tmptotal[$field] = 0;
					}
				}

				foreach ($fields_col["ref_value"] as $keyfieldcol => $value) {

					if (strpos($value," as ") !== false) {

						$value = substr($value, strpos($value, " as ")+4, 255);
						$datasql_edit_fields[$keytab]["row"][$keyrow]["col"][$keycol]["ref_value"][$keyfieldcol] = $value;
					}
				}

				if ($result = $db->Execute("select id, ".implode(",",$fields_col["ref_value"])." from ".$tmpfield." where refid_".$datasql_table." = '".$id."' order by ".implode(",", $fields_col["ref_order"]))) {

					while (!$result->EOF) {

						$datatmp = array();
						$datatmp["id"] = $result->fields["id"];

						foreach ($fields_col["ref_value"] as $keyfieldcol => $value) {

							if (strpos($value," as ") !== false) {

								$value = substr($value, strpos($value, " as ")+4, 255);
								$datatmp[$value] = $result->fields[$value];

								if (isset($fields_col["ref_total"]) && in_array($value, $fields_col["ref_total"])) {
									$tmptotal[$value] += $result->fields[$value];
								}

							} elseif (count($datasql_reference11)) {

								$datatmp[$value] = $result->fields[$value];

								if (isset($fields_col["ref_total"]) && in_array($value, $fields_col["ref_total"])) {
									$tmptotal[$value] += $result->fields[$value];
								}

								foreach ($datasql_reference11 as $key => $refarr2) {

									if ($key == $value) {
										if ($sresult = $db->Execute("select ".$refarr2["field"]." from ".$refarr2["table"]." where id = '".$result->fields[$value]."' limit 1")) {
											if (!$sresult->EOF) {

												$tmpidx = $refarr2["field"];
												if (strpos($tmpidx," as ") !== false) {
													$tmpidx = substr($tmpidx, strpos($tmpidx, " as ")+4, 255);
												}

												$datatmp[$value] = $sresult->fields[$tmpidx];
											}
										}
									}
								}
							}
						}

						array_push($$tmpdata, $datatmp);

						$result->MoveNext();
					}
				}

				$datatmp = array();
				$datatmp["id"] = "";
				foreach ($fields_col["ref_value"] as $keyfieldcol => $value) {
					if (strpos($value," as ") !== false) {
						$value = substr($value, strpos($value, " as ")+4, 255);
					}
					$datatmp[$value] = "";
				}
				if (isset($fields_col["ref_total"]) && count($fields_col["ref_total"])) {

					foreach ($tmptotal as $key => $value) {
						$datatmp[$key] = str_replace(",",".",$value);
					}

					$dataref1nsub_totals = true;
				} else {

					$dataref1nsub_totals = false;
				}
				$smarty->assign("dataref1nsub_total_".$tmpfield, $datatmp);
				$smarty->assign("dataref1nsub_totals", $dataref1nsub_totals);

				$tmporder = "";
				if (count($fields_col["ref_order"])) {
					foreach ($fields_col["ref_order"] as $value) {
						if (strpos($value, " ") !== false) {
							$tmp_value = array_search(substr($value, 0, strpos($value," ")), $fields_col["ref_value"]);
							$tmp_order = substr($value, strpos($value," ")+1, strlen($value));
						} else {
							$tmp_value = array_search($value, $fields_col["ref_value"]);
							$tmp_order = "asc";
						}
						$tmp_value++;
						$tmporder .= "[".$tmp_value.", \"".$tmp_order."\"],";
					}
				}
				if ($tmporder) {
					$tmporder = substr($tmporder, 0, -1);
				}
				$tmporder = "[".$tmporder."]";
				$smarty->assign($tmpdata."_dtorder", $tmporder);

				$smarty->assign($tmpdata, $$tmpdata);
			}
		}
	}
}

$data = array();
if ($id > 0) {

	if ($is_admin) {

		$data_history = array();
		if ($result = $db->Execute("select aUser.aUser, action, afields, stamp from aLogDB, aUser where aLogDB.refid_aUser = aUser.id and refid_aTable = (select id from aTable where aTable = '".$datasql_table."') and refid = '".$id."' order by stamp desc")) {
			while (!$result->EOF) {
				$data_history[] = array(
						"aUser" => $result->fields["aUser"],
						"action" => $result->fields["action"],
						"afields" => unserialize($result->fields["afields"]),
						"stamp" => $result->fields["stamp"]
				);
				$result->MoveNext();
			}
		}
		$smarty->assign("data_history", $data_history);


		if (count($data_history)) {

			$datasql_edit_fields[] = array('tab' => $module,
					'desc' => 'Historie ('.count($data_history).')',
					'row' => array(
							array('desc' => '', 'col' => array(
									array('field' => 'ref1n_aLogDB', 'type' => 'aLogDB')
							))
					));
		}
	}

	if ($result = $db->Execute("select aLastUpdate, (select aUser from aUser where id = ".$datasql_table.".refid_aUser_update) as ref_benutzer, ".implode(",",array_merge($datasql_fields_edit, $datasql_fields_edit_filesinfo))." from ".$datasql_table." where id = '".$id."' limit 1")) {

		if (!$result->EOF) {

			$data["aLastUpdate"] = $result->fields["aLastUpdate"];
			$data["ref_benutzer"] = $result->fields["ref_benutzer"];
			foreach (array_merge($datasql_fields_edit, $datasql_fields_edit_filesinfo) as $field) {
				$field = str_replace("`","",$field);
				if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
				$data[$field] = $result->fields[$field];

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

				if (in_array($field, array_merge($datasql_fields_edit_filesinfo, $datasql_fields_edit_fixed_edit_fields))) {

					$data[$field] = unserialize($data[$field]);
/*
					echo "data.$field:<br>";
					echo "<pre>";
					print_r($data[$field]);
					echo "</pre>";
*/
				}
			}

			if (isset($datasql_field_type[$field]["link"]) && $datasql_field_type[$field]["link"] == "#www#" && $data[$field] != "") {

				if (substr($data[$field],0,7) != "http://" && substr($data[$field],0,8) != "https://") {
					$data[$field] = "http://".$data[$field];
				}
			}
		}
	}

} else {

	if ($apfra_rights[$module]["ins"] == 0) {

		reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort);
	}

	$refcol = "";
	$refvalue = "";
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,6) == "refid_") {
			$refcol = $key;
			$refvalue = $value;
		}
	}

	if ($id == 0 && $refcol && $refvalue) {

		if (file_exists(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_pre_insert.inc.php")) {
			require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_pre_insert.inc.php");
		} elseif (file_exists(DEF_PATH_PRIVATE."mod/".$module."/".$module."_pre_insert.inc.php")) {
			require(DEF_PATH_PRIVATE."mod/".$module."/".$module."_pre_insert.inc.php");
		}

		if (count($data)) {
			$query = "insert into ".$datasql_table." (".$refcol.", ".implode(",",array_keys($data)).") values ('".$refvalue."', '".implode("','",$data)."')";
		} else {
			$query = "insert into ".$datasql_table." (".$refcol.") values ('".$refvalue."')";
		}

		if ($iresult = $db->Execute($query)) {
			if ($tmpid = $db->Insert_ID()) {
				reload_page("?mod=".$module."&a=".$action."&id=".$tmpid."&backmod=".$backmod."&backid=".$backid."&backt=".$backt);
			}
		} else {
echo $query;
die();
		}
	}

	$data["aLastUpdate"] = "";
	$data["ref_benutzer"] = "";
	foreach ($datasql_fields_edit as $field) {
		if ($field <> "id") {
			$data[$field] = "";
		}
		if (array_key_exists($field, $datasql_reference11)) {
			$data["ref"][$field] = "";
		}
	}
	foreach ($datasql_fields_add_default as $field => $value) {
		$data[$field] = $value;
	}

	if (file_exists(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_pre_insert.inc.php")) {
		require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_pre_insert.inc.php");
	} elseif (file_exists(DEF_PATH_PRIVATE."mod/".$module."/".$module."_pre_insert.inc.php")) {
		require(DEF_PATH_PRIVATE."mod/".$module."/".$module."_pre_insert.inc.php");
	}
}

$smarty->assign("errors", $errors);
$smarty->assign("tab", $tab);

$smarty->assign("backmod", $backmod);
$smarty->assign("backid", $backid);
$smarty->assign("backt", $backt);

?>
