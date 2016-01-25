<?php

$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";
$datetime = date("Y-m-d H:i:s");

if ($formaction == "save") {

	$tmpvar = array(
			"tab" => array(),
			"mod" => array(),
			"men" => array(),
			"doc" => array()
	);
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,2) == "f_" && $key != "f_tabledata") {

			$tmparr = explode("_", substr($key,2,255));
			if ($tmparr[0] == "doc") {
				$tmpvar[$tmparr[0]][] = $value;
			} else {
				$tmpvar[$tmparr[0]][] = $tmparr[1];
			}
		}
	}

	$backup = array();
	$backup_tab = array();
	$backup_tab_ids = array();

	if (count($tmpvar["exp"])) {
		foreach ($tmpvar["exp"] as $value) {
			$backup[$value] = 1;
			if ($value == "history") {
				$backup_tab[] = "aLogDB";
				$backup_tab[] = "aLogUser";
			} elseif ($value != "data") {
				$backup_tab[] = $value;
				if (in_array($value, array("aUser", "aRole"))) {
					if (!in_array("ref1n_aUser_aRole", $backup_tab)) {
						$backup_tab[] = "ref1n_aUser_aRole";
					}
				}
			}
		}
	}
	foreach (array("data", "history", "aReport", "aRight", "aRole", "aUser") as $value) {
		if (!isset($backup[$value])) {
			$backup[$value] = 0;
		}
	}

	$export = array();

	if (count($tmpvar["tab"])) {
		foreach ($tmpvar["tab"] as $key) {

			if ($result = $db->Execute("select id, aTable, aTableDesc from aTable where id = '".$key."' limit 1")) {

				if (!$result->EOF) {

					if ($backup["data"]) {

						$backup_tab[] = $result->fields["aTable"];
						$backup_tab_ids[] = $result->fields["id"];
					}

					$tmpfields_order = array();
					if ($sresult = $db->Execute("show fields from ".$result->fields["aTable"])) {

						while (!$sresult->EOF) {

							$tmp_field = $sresult->fields[0];
							if (!in_array($tmp_field, array("id", "aLastUpdate", "refid_aUser_update"))) {

								$tmpfields_order[] = $tmp_field;
							}

							$sresult->MoveNext();
						}
					}
					$tmpfields_order = array_flip($tmpfields_order);

					$tmpfields = array();
					if ($sresult = $db->Execute("select aField, aFieldDesc, (select aFieldType from aFieldType where id = refid_aFieldType) as aFieldType from aField where refid_aTable = '".$key."' order by aField")) {

						while (!$sresult->EOF) {

							$tmpfields[$tmpfields_order[$sresult->fields["aField"]]] = array(
									"aField" => $sresult->fields["aField"],
									"aFieldDesc" => $sresult->fields["aFieldDesc"],
									"aFieldType" => $sresult->fields["aFieldType"]
							);

							$sresult->MoveNext();
						}
					}
					ksort($tmpfields);

					$tmpref = array();
					if ($sresult = $db->Execute("select aRef, aRefDesc, aField, aSearch, aOrder from aRef where refid_aTable = '".$key."' order by aRef")) {

						while (!$sresult->EOF) {

							$tmpref[] = array(
									"aRef" => $sresult->fields["aRef"],
									"aRefDesc" => $sresult->fields["aRefDesc"],
									"aField" => $sresult->fields["aField"],
									"aSearch" => $sresult->fields["aSearch"],
									"aOrder" => $sresult->fields["aOrder"]
							);

							$sresult->MoveNext();
						}
					}

					$tmpref1n = array();
					if ($sresult = $db->Execute("select (select aTable from aTable where id = refid_aTable_n) as aTable_n from aRef1n where refid_aTable_1 = '".$key."' order by aTable_n")) {

						while (!$sresult->EOF) {

							$tmpref1n[] = $sresult->fields["aTable_n"];

							$sresult->MoveNext();
						}
					}

					$export["tab"][] = array(
							"aTable" => $result->fields["aTable"],
							"aTableDesc" => $result->fields["aTableDesc"],
							"fields" => $tmpfields,
							"ref" => $tmpref,
							"ref1n" => $tmpref1n
					);
				}
			}

			if ($backup["data"] && count($backup_tab_ids)) {

				if ($result = $db->Execute("select (select aTable from aTable where id = refid_aTable_1) as aTable_1, (select aTable from aTable where id = refid_aTable_n) as aTable_n from aRef1n where refid_aTable_1 in ('".implode("','", $backup_tab_ids)."')")) {

					while (!$result->EOF) {

						$backup_tab[] =  "ref1n_".$result->fields["aTable_1"]."_".$result->fields["aTable_n"];
						$result->MoveNext();
					}
				}
			}
		}
	}

	$tmp_files = array(
			"datafile" => array(),
			"datasql" => array(),
			"mod" => array(),
			"doc" => array()
	);
	if (count($tmpvar["mod"])) {
		foreach ($tmpvar["mod"] as $key) {

			if ($result = $db->Execute("select aModule, aModuleDesc from aModule where id = '".$key."' limit 1")) {

				if (!$result->EOF) {

					$export["mod"][] = array(
							"aModule" => $result->fields["aModule"],
							"aModuleDesc" => $result->fields["aModuleDesc"]
					);

					$tmpmod = $result->fields["aModule"];

					if (file_exists(DEF_PATH_PRIVATE."config".DS."datasql".DS.$tmpmod.".datasql.php")) {

						$tmp_files["datasql"][] = $tmpmod.".datasql.php";

					} elseif (file_exists(DEF_PATH_PRIVATE."config".DS."datafile".DS.$tmpmod.".datafile.php")) {

						$tmp_files["datafile"][] = $tmpmod.".datafile.php";
					}

					foreach (glob(DEF_PATH_PRIVATE."mod".DS.$tmpmod.DS."*") as $file) {

						$tmp_files["mod"][$tmpmod][] = basename($file);
					}
				}
			}
		}
	}

	if (count($tmpvar["men"])) {

		if ($result = $db->Execute("select id, aMenu, (select aModule from aModule where id = refid_aModule) as aModule from aMenu where (refid_aMenu_parent = 0 or refid_aMenu_parent is null) and id in ('".implode("','",$tmpvar["men"])."') order by pos")) {

			while (!$result->EOF) {

				$tmpsub = array();
				if ($sresult = $db->Execute("select aMenu, (select aModule from aModule where id = refid_aModule) as aModule from aMenu where refid_aMenu_parent = '".$result->fields["id"]."' and id in ('".implode("','",$tmpvar["men"])."') order by pos")) {

					while (!$sresult->EOF) {

						$tmpsub[] = array(
								"aMenu" => $sresult->fields["aMenu"],
								"aModule" => $sresult->fields["aModule"]
						);
						$sresult->MoveNext();
					}
				}

				$export["men"][] = array(
						"aMenu" => $result->fields["aMenu"],
						"aModule" => $result->fields["aModule"],
						"subMenu" => $tmpsub
				);

				$result->MoveNext();
			}
		}
	}

	if (count($tmpvar["doc"])) {

		$tmp_files["doc"] = $tmpvar["doc"];
	}

	/* export actual ids */

	$export_ids = array();

	if ($result = $db->Execute("select id, aTable from aTable where aTable not in ('".implode("','", $apfra_tables)."') order by aTable")) {

		while (!$result->EOF) {

			$export_ids["aTable"][$result->fields["aTable"]] = $result->fields["id"];
			$result->MoveNext();
		}
	}

	if ($result = $db->Execute("select id, aModule from aModule where aModule not in ('".implode("','", $apfra_tables)."') order by aModule")) {

		while (!$result->EOF) {

			$export_ids["aModule"][$result->fields["aModule"]] = $result->fields["id"];
			$result->MoveNext();
		}
	}

	/* save */

	$tmpstamp = date("Ymd_His");
	$tmppath = sys_get_temp_dir()."export_".$tmpstamp;

	$oldmask = umask(0);
	@mkdir($tmppath);
	umask($oldmask);

	// create files
	file_put_contents($tmppath.DS."export.apfra", serialize($export));
	file_put_contents($tmppath.DS."files.apfra", serialize($tmp_files));
	file_put_contents($tmppath.DS."ids.apfra", serialize($export_ids));

	$oldmask = umask(0);
	@mkdir($tmppath.DS."csv");
	umask($oldmask);
	if (count($backup_tab)) {

		foreach ($backup_tab as $value) {

			$query = "select * into outfile '".$tmppath.DS."csv".DS.$value.".csv' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\n' from ".$value;
			$db->Execute($query);
		}
	}

	$oldmask = umask(0);
	@mkdir($tmppath.DS."config");
	umask($oldmask);

	$oldmask = umask(0);
	@mkdir($tmppath.DS."config".DS."datafile");
	umask($oldmask);

	foreach ($tmp_files["datafile"] as $file) {

		file_put_contents($tmppath.DS."config".DS."datafile".DS.$file, file_get_contents(DEF_PATH_PRIVATE."config".DS."datafile".DS.$file));
	}

	$oldmask = umask(0);
	@mkdir($tmppath.DS."config".DS."datasql");
	umask($oldmask);

	foreach ($tmp_files["datasql"] as $file) {

		file_put_contents($tmppath.DS."config".DS."datasql".DS.$file, file_get_contents(DEF_PATH_PRIVATE."config".DS."datasql".DS.$file));
	}

	$oldmask = umask(0);
	@mkdir($tmppath.DS."mod");
	umask($oldmask);

	foreach ($tmp_files["mod"] as $tmpmod => $filearr) {

		$oldmask = umask(0);
		@mkdir($tmppath.DS."mod".DS.$tmpmod);
		umask($oldmask);

		foreach ($filearr as $file) {

			file_put_contents($tmppath.DS."mod".DS.$tmpmod.DS.$file, file_get_contents(DEF_PATH_PRIVATE."mod".DS.$tmpmod.DS.$file));
		}
	}

	$oldmask = umask(0);
	@mkdir($tmppath.DS."doc");
	umask($oldmask);

	foreach ($tmp_files["doc"] as $file) {

		$tmpparr = explode(DS, $file);
		$tmpp = "";
		for ($i=0; $i<count($tmpparr)-1; $i++) {
			if (!file_exists($tmppath.DS."doc".DS.$tmpp.$tmpparr[$i])) {
				$oldmask = umask(0);
				@mkdir($tmppath.DS."doc".DS.$tmpp.$tmpparr[$i]);
				umask($oldmask);
			}
			$tmpp .= $tmpparr[$i].DS;
		}

		file_put_contents($tmppath.DS."doc".DS.$file, file_get_contents(DEF_PATH_PRIVATE."doc".DS.$file));
	}

	// zip
	$export_fn = $tmpstamp."_apfra_export.zip";

	$zip = new apfra_zip();
	$res = $zip->open(sys_get_temp_dir().$export_fn, ZipArchive::CREATE);
	if($res === TRUE) {
		$zip->addDir($tmppath, basename($tmppath));
		$zip->close();
	} else {
// TODO error page
		echo "Could not create a zip archive (".$ret.")";
		die();
	}

	// delete files
	unlinkrec($tmppath);

	// download zip
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="'.$export_fn.'"');
	header('Cache-Control: max-age=0');
	readfile(sys_get_temp_dir().$export_fn);
	unlink(sys_get_temp_dir().$export_fn);

	require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");
	die();
}

$data_table = array();
if ($result = $db->Execute("select id, aTable, aTableDesc from aTable where aTable not in ('".implode("','", $apfra_tables)."') order by aTable")) {

	while (!$result->EOF) {

		$data_table[] = array(
				"id" => $result->fields["id"],
				"aTable" => $result->fields["aTable"],
				"aTableDesc" => $result->fields["aTableDesc"]
		);

		$result->MoveNext();
	}
}
$smarty->assign("data_table", $data_table);

$data_mod = array();
if ($result = $db->Execute("select id, aModule, aModuleDesc from aModule where aModule not in ('".implode("','", $apfra_tables)."') order by aModule")) {

	while (!$result->EOF) {

		$data_mod[] = array(
			"id" => $result->fields["id"],
			"aModule" => $result->fields["aModule"],
			"aModuleDesc" => $result->fields["aModuleDesc"]
		);

		$result->MoveNext();
	}
}
$smarty->assign("data_mod", $data_mod);

$data_menu = array();
if ($result = $db->Execute("select id, pos, aMenu, (select aModule from aModule where id = refid_aModule) as aModule from aMenu where (refid_aMenu_parent = 0 or refid_aMenu_parent is null) order by pos")) {

	while (!$result->EOF) {

		$tmpsub = array();
		if ($sresult = $db->Execute("select id, refid_aMenu_parent, pos, aMenu, (select aModule from aModule where id = refid_aModule) as aModule from aMenu where refid_aMenu_parent = '".$result->fields["id"]."' order by pos")) {

			while (!$sresult->EOF) {

				$tmpsub[] = array(
						"id" => $sresult->fields["id"],
						"refid_aMenu_parent" => $sresult->fields["refid_aMenu_parent"],
						"pos" => $sresult->fields["pos"],
						"aMenu" => $sresult->fields["aMenu"],
						"aModule" => $sresult->fields["aModule"]
				);

				$sresult->MoveNext();
			}
		}

		$data_menu[] = array(
			"id" => $result->fields["id"],
			"pos" => $result->fields["pos"],
			"aMenu" => $result->fields["aMenu"],
			"aModule" => $result->fields["aModule"],
			"submenu" => $tmpsub
		);

		$result->MoveNext();
	}
}
$smarty->assign("data_menu", $data_menu);

$data_doc = filelist(DEF_PATH_PRIVATE."doc");
$smarty->assign("data_doc", $data_doc);

function filelist($base, $dir = "") {

	$files = array();
	foreach (glob(($dir ? $dir : $base).DS."*") as $file) {

		if (is_dir($file)) {
			$files = array_merge($files, filelist($base, $file));
		} else {
			$files[] = substr($file, strlen($base)+1, 4096);
		}
	}

	return $files;
}

function unlinkrec($base) {

	foreach (scandir($base) as $file) {

		if (is_dir($base.DS.$file)) {
			if (!in_array($file, array(".", ".."))) {
				@unlinkrec($base.DS.$file);
			}
		} else {
			@unlink($base.DS.$file);
		}
	}
	@rmdir($base);
}

?>
