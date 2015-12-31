<?php

$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";
$datetime = date("Y-m-d H:i:s");
$f_stamp = isset($_SESSION["psd"]["f_stamp"]) ? $_SESSION["psd"]["f_stamp"] : "";
$f_reset = isset($_SESSION["psd"]["f_reset"]) ? $_SESSION["psd"]["f_reset"] : 0;
$f_error = isset($_SESSION["psd"]["f_error"]) ? $_SESSION["psd"]["f_error"] : 0;
$f_success = isset($_SESSION["psd"]["f_success"]) ? $_SESSION["psd"]["f_success"] : 0;

if ($formaction == "import" && $f_stamp == "") {

	$tmpdir = "";

	if ($_SESSION["psd"]["fd_file"]["error"] == 0) {

		$tmpfile = sys_get_temp_dir().$_SESSION["psd"]["fd_file"]["tmpstamp"];
		$tmp_finfo = new finfo(FILEINFO_MIME_TYPE);

		if (false === $ext = array_search($tmp_finfo->file($tmpfile), array('zip' => 'application/zip'), true)) {

			@unlink($tmpfile);
			reload_page("?mod=".$module."&f_error=5");

		} else {

			$zip = new apfra_zip();
			if ($zip->open($tmpfile) === TRUE) {

				$tmpstamp = uniqid();
				$tmppath = sys_get_temp_dir().$tmpstamp;

				$oldmask = umask(0);
				@mkdir($tmppath);
				umask($oldmask);

				$zip->extractTo($tmppath);
				$zip->close();

				$tmpdirarr = glob($tmppath."/*");
				if (count($tmpdirarr) == 1) {

					$tmpdir = basename($tmpdirarr[0]);
					$tmpdirarr2 = explode("_",$tmpdir);

					if (!(count($tmpdirarr2) == 3 && $tmpdirarr2[0] == "export" && preg_match("/^\d{8}$/", $tmpdirarr2[1]) && preg_match("/^\d{6}$/", $tmpdirarr2[2]))) {

						@unlink($tmpfile);
						unlinkrec($tmppath);
						reload_page("?mod=".$module."&f_error=7");
					}

				} else {

					@unlink($tmpfile);
					unlinkrec($tmppath);
					reload_page("?mod=".$module."&f_error=7");
				}

			} else {

				@unlink($tmpfile);
				reload_page("?mod=".$module."&f_error=8");
			}

			@unlink($tmpfile);

			$export_files = unserialize(file_get_contents($tmppath."/".$tmpdir."/files.apfra"));
			$export = unserialize(file_get_contents($tmppath."/".$tmpdir."/export.apfra"));
			$export_ids = unserialize(file_get_contents($tmppath."/".$tmpdir."/ids.apfra"));

			if (!(is_array($export) && is_array($export_files) && is_array($export_ids))) {

				reload_page("?mod=".$module."&f_error=7");
			}

			reload_page("?mod=".$module."&f_stamp=".$tmpstamp."&f_reset=".$f_reset);
		}

 	} elseif ($_SESSION["psd"]["fd_file"]["f_error"]) {

		reload_page("?mod=".$module."&f_error=".$_SESSION["psd"]["fd_file"]["f_error"]);
 	}
}

if ($f_stamp != "") {

	$tmppath = sys_get_temp_dir().$f_stamp;

	if ($formaction == "importcancel") {

		unlinkrec($tmppath);
 		reload_page("?mod=".$module);
	}

	$tmpdirarr = glob($tmppath."/*");
	$tmpdir = basename($tmpdirarr[0]);

	$export = unserialize(file_get_contents($tmppath."/".$tmpdir."/export.apfra"));
	$export_files = unserialize(file_get_contents($tmppath."/".$tmpdir."/files.apfra"));
	$export_ids = unserialize(file_get_contents($tmppath."/".$tmpdir."/ids.apfra"));

	$backup_files = array();
	foreach (glob($tmppath."/".$tmpdir."/csv/*") as $file) {
		if (is_file($file)) {
			$backup_files[] = basename($file);
		}
	}

	if ($formaction == "importgo") {

		if ($f_reset == 1) {

			/* read actual entries to delete */

			$del_tab_ids = array();
			$drop_tab = array();
			if ($result = $db->Execute("select id, aTable from aTable where aTable not in ('".implode("','", $apfra_tables)."')")) {

				while (!$result->EOF) {

					$del_tab_ids[] = $result->fields["id"];
					$drop_tab[] =  $result->fields["aTable"];
					$result->MoveNext();
				}
			}

			if ($result = $db->Execute("select (select aTable from aTable where id = refid_aTable_1) as aTable_1, (select aTable from aTable where id = refid_aTable_n) as aTable_n from aRef1n where refid_aTable_1 in ('".implode("','", $del_tab_ids)."')")) {

				while (!$result->EOF) {

					$drop_tab[] =  "ref1n_".$result->fields["aTable_1"]."_".$result->fields["aTable_n"];
					$result->MoveNext();
				}
			}

			$del_mod_ids = array();
			if ($result = $db->Execute("select id from aModule where aModule not in ('".implode("','", $apfra_tables)."')")) {

				while (!$result->EOF) {

					$del_mod_ids[] = $result->fields["id"];
					$result->MoveNext();
				}
			}

			/* backup actual database and files -> zip */

			$tmpzipname = date("Ymd_His")."_".$f_stamp;

			$zip = new apfra_zip();
			$res = $zip->open(DEF_PATH_PRIVATE."bak/".$tmpzipname.".zip", ZipArchive::CREATE);
			if($res === TRUE) {

				$oldmask = umask(0);
				@mkdir(sys_get_temp_dir()."csv");
				umask($oldmask);

				foreach ($drop_tab as $value) {

					$query = "select * into outfile '".sys_get_temp_dir()."csv/".$value.".csv' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\n' from ".$value;
					$db->Execute($query);

					$zip->addFile(sys_get_temp_dir()."csv/".$value.".csv", $tmpzipname."/csv/".$value.".csv");
				}

				$zip->addDir(DEF_PATH_PRIVATE."config/datafile", $tmpzipname."/config/datafile");
				$zip->addDir(DEF_PATH_PRIVATE."config/datasql", $tmpzipname."/config/datasql");
				$zip->addDir(DEF_PATH_PRIVATE."mod", $tmpzipname."/mod");
				$zip->addDir(DEF_PATH_PRIVATE."doc", $tmpzipname."/doc");
				$zip->close();

				foreach ($drop_tab as $value) {

					@unlink(sys_get_temp_dir()."csv/".$value.".csv");
				}
				@rmdir(sys_get_temp_dir()."csv");

			} else {

				reload_page("?mod=".$module."&f_error=11");
			}

			/* delete files */

			foreach (glob(DEF_PATH_PRIVATE."config/datafile/*") as $file) {
				if (is_file($file)) {
					@unlink($file);
				}
			}

			foreach (glob(DEF_PATH_PRIVATE."config/datasql/*") as $file) {
				if (is_file($file)) {
					@unlink($file);
				}
			}

			foreach (glob(DEF_PATH_PRIVATE."mod/*") as $file) {
				if (is_dir($file)) {
					unlinkrec($file);
				}
			}

			foreach (glob(DEF_PATH_PRIVATE."doc/*") as $file) {
				if (is_file($file)) {
					@unlink($file);
				} elseif (is_dir($file)) {
					unlinkrec($file);
				}
			}

			// delete entries in database
// TODO aRight, aRole, aReport, aUser?
			$queryarr = array();
			$queryarr[] = "delete from aTable where id in ('".implode("','", $del_tab_ids)."')";
			$queryarr[] = "delete from aField where refid_aTable in ('".implode("','", $del_tab_ids)."')";
			$queryarr[] = "delete from aRef where refid_aTable in ('".implode("','", $del_tab_ids)."')";
			$queryarr[] = "delete from aRef1n where refid_aTable_1 in ('".implode("','", $del_tab_ids)."')";
			$queryarr[] = "delete from aModule where id in ('".implode("','", $del_mod_ids)."')";
			$queryarr[] = "delete from aMenu";
			foreach ($drop_tab as $value) {
				$queryarr[] = "drop table ".$value;
			}
			$queryarr[] = "truncate aReport";
			$queryarr[] = "truncate aRight";
			$queryarr[] = "truncate aRole";
			$queryarr[] = "truncate aLogDB";
			$queryarr[] = "truncate aLogUser";
			$queryarr[] = "truncate aUser";
			$queryarr[] = "truncate ref1n_aUser_aRole";

			foreach ($queryarr as $query) {
				if (!$result = $db->Execute($query)) {
// TODO error page
					echo "error: $query<br>";
					die();
				}
			}
		}

		// import data

		$tmplastupdate = date("Y-m-d H:i:s");

		$backup_tab = array();

		$tmp_fieldtype_ids = array();
		if ($result = $db->Execute("select id, aFieldType from aFieldType")) {

			while (!$result->EOF) {

				$tmp_fieldtype_ids[$result->fields["aFieldType"]] = $result->fields["id"];
				$result->MoveNext();
			}
		}

		$tmp_ref1n = array();

		$tmp_table_ids = array();
		foreach ($export["tab"] as $valuearr) {

			if ($db->Execute("insert into aTable (aTable, aTableDesc, aLastUpdate, refid_aUser_update) values ('".$valuearr["aTable"]."', '".$valuearr["aTableDesc"]."', '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')")) {

				$backup_tab[] = $valuearr["aTable"];

				$tmpid = $db->Insert_ID();
				$tmp_table_ids[$valuearr["aTable"]] = $tmpid;
				if ($tmpid && count($valuearr["fields"])) {

					foreach ($valuearr["fields"] as $subvaluearr) {

						if (!array_key_exists($subvaluearr["aFieldType"], $tmp_fieldtype_ids)) {

							$db->Execute("insert into aFieldType (aFieldType, aLastUpdate, refid_aUser_update) values ('".$subvaluearr["aFieldType"]."', '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')");
							$tmp_fieldtype_ids[$subvaluearr["aFieldType"]] = $db->Insert_ID();
						}

						$db->Execute("insert into aField (refid_aTable, aField, aFieldDesc, refid_aFieldType, aLastUpdate, refid_aUser_update) values ('".$tmpid."', '".$subvaluearr["aField"]."', '".$subvaluearr["aFieldDesc"]."', '".$tmp_fieldtype_ids[$subvaluearr["aFieldType"]]."', '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')");
					}
				}

				if ($tmpid && count($valuearr["ref"])) {

					foreach ($valuearr["ref"] as $subvaluearr) {


						$db->Execute("insert into aRef (refid_aTable, aRef, aRefDesc, aField, aSearch, aOrder, aLastUpdate, refid_aUser_update) values ('".$tmpid."', '".$subvaluearr["aRef"]."', '".$subvaluearr["aRefDesc"]."', '".addslashes($subvaluearr["aField"])."', '".$subvaluearr["aSearch"]."', '".$subvaluearr["aOrder"]."', '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')");
					}
				}
				if ($tmpid && count($valuearr["ref1n"])) {

					foreach ($valuearr["ref1n"] as $subvalue) {

						$tmp_ref1n[] = array("1" => $valuearr["aTable"],"n" => $subvalue);
					}
				}

				$query = "";
				$query .= "create table ".$valuearr["aTable"]." (";
				$query .= "id int(11) unsigned not null AUTO_INCREMENT,";
				$query .= "primary key (id),";
				foreach ($valuearr["fields"] as $subvaluearr) {
					$query .= $subvaluearr["aField"]." ".$subvaluearr["aFieldType"]." default null,";
				}
				$query .= "aLastUpdate datetime default null,";
				$query .= "refid_aUser_update int(11) unsigned default null";
				$query .= ")";
				$db->Execute($query);

			}
		}

		if (count($tmp_ref1n)) {

			foreach ($tmp_ref1n as $valuearr) {

				$backup_tab[] = "ref1n_".$valuearr["1"]."_".$valuearr["n"];

				$db->Execute("insert into aRef1n (refid_aTable_1, refid_aTable_n, aLastUpdate, refid_aUser_update) values ('".$tmp_table_ids[$valuearr["1"]]."', '".$tmp_table_ids[$valuearr["n"]]."', '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')");

				$query = "";
				$query .= "create table ref1n_".$valuearr["1"]."_".$valuearr["n"]." (";
				$query .= "id int(11) unsigned not null AUTO_INCREMENT,";
				$query .= "primary key (id),";
				$query .= "refid_".$valuearr["1"]." int(11) unsigned default null,";
				$query .= "refid_".$valuearr["n"]." int(11) unsigned default null,";
				$query .= "aLastUpdate datetime default null,";
				$query .= "refid_aUser_update int(11) unsigned default null";
				$query .= ")";
				$db->Execute($query);
			}
		}

		$tmp_module_ids = array("" => "NULL");
		foreach ($export["mod"] as $valuearr) {

			if ($db->Execute("insert into aModule (aModule, aModuleDesc, aLastUpdate, refid_aUser_update) values ('".$valuearr["aModule"]."', '".$valuearr["aModuleDesc"]."', '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')")) {
				$tmp_module_ids[$valuearr["aModule"]] = $db->Insert_ID();
			}
		}

		$tmppos = 0;
		foreach ($export["men"] as $valuearr) {

			$tmppos++;

			if ($db->Execute("insert into aMenu (refid_aMenu_parent, pos, aMenu, refid_aModule, aLastUpdate, refid_aUser_update) values ('0', '".$tmppos."', '".$valuearr["aMenu"]."', ".$tmp_module_ids[$valuearr["aModule"]].", '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')")) {

				$tmpid = $db->Insert_ID();
				if ($tmpid && count($valuearr["subMenu"])) {

					$tmpsubpos = 0;
					foreach ($valuearr["subMenu"] as $subvaluearr) {

						$tmpsubpos++;
						$db->Execute("insert into aMenu (refid_aMenu_parent, pos, aMenu, refid_aModule, aLastUpdate, refid_aUser_update) values ('".$tmpid."', '".$tmpsubpos."', '".$subvaluearr["aMenu"]."', ".$tmp_module_ids[$subvaluearr["aModule"]].", '".$tmplastupdate."', '".$_SESSION["psu"]["id"]."')");
					}
				}
			}
		}

		if (count($backup_files)) {

			foreach ($backup_files as $file) {

				$tmp_table = substr($file, 0, -4);
				$query = "load data infile '".$tmppath."/".$tmpdir."/csv/".$file."' into table ".$tmp_table." fields terminated by ',' optionally enclosed by '\"' lines terminated by '\n'";
				$db->Execute($query);

				if ($tmp_table == "aRight") {

					$new_module_ids = array();
					if ($result = $db->Execute("select id, aModule from aModule")) {

						while (!$result->EOF) {

							$new_module_ids[$result->fields["aModule"]] = $result->fields["id"];
							$result->MoveNext();
						}
					}

					$tmp_maxid = 0;
					if ($result = $db->Execute("select max(id) as id from aModule")) {

						if (!$result->EOF) {

							$tmp_maxid = $result->fields["id"];
						}
					}

					foreach ($export_ids["aModule"] as $table => $oldid) {

						$tmp_maxid++;
						$db->Execute("update aRight set refid_aModule = '".$tmp_maxid."' where refid_aModule = '".$oldid."'");
						$db->Execute("update aRight set refid_aModule = '".$new_module_ids[$table]."' where refid_aModule = '".$tmp_maxid."'");
					}
				}

				if ($tmp_table == "aLogDB") {

					$new_table_ids = array();
					if ($result = $db->Execute("select id, aTable from aTable")) {

						while (!$result->EOF) {

							$new_table_ids[$result->fields["aTable"]] = $result->fields["id"];
							$result->MoveNext();
						}
					}

					$tmp_maxid = 0;
					if ($result = $db->Execute("select max(id) as id from aTable")) {

						if (!$result->EOF) {

							$tmp_maxid = $result->fields["id"];
						}
					}

					foreach ($export_ids["aTable"] as $table => $oldid) {

						$tmp_maxid++;
						$db->Execute("update aLogDB set refid_aTable = '".$tmp_maxid."' where refid_aTable = '".$oldid."'");
						$db->Execute("update aLogDB set refid_aTable = '".$new_table_ids[$table]."' where refid_aTable = '".$tmp_maxid."'");
					}
				}

			}
		}

		foreach (glob($tmppath."/".$tmpdir."/config/datafile/*") as $file) {
			rename($file, str_replace($tmppath."/".$tmpdir."/config/datafile/", DEF_PATH_PRIVATE."config/datafile/", $file));
		}
		foreach (glob($tmppath."/".$tmpdir."/config/datasql/*") as $file) {
			rename($file, str_replace($tmppath."/".$tmpdir."/config/datasql/", DEF_PATH_PRIVATE."config/datasql/", $file));
		}
		foreach (glob($tmppath."/".$tmpdir."/mod/*") as $file) {
			rename($file, str_replace($tmppath."/".$tmpdir."/mod/", DEF_PATH_PRIVATE."mod/", $file));
		}
		foreach (glob($tmppath."/".$tmpdir."/doc/*") as $file) {
			rename($file, str_replace($tmppath."/".$tmpdir."/doc/", DEF_PATH_PRIVATE."doc/", $file));
		}

		// delete tmp data

		unlinkrec($tmppath);

		// reload rights & menu

		$tmprights = new apfra_rights($db);
		$_SESSION["psu"]["rights"] = $tmprights->readrights();

		$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
		$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();

		reload_page("?mod=".$module."&f_success=1");
	}

	$smarty->assign("data", $export);
	$smarty->assign("data_files", $export_files);
	$smarty->assign("backup_files", $backup_files);
}

$smarty->assign("f_stamp", $f_stamp);
$smarty->assign("f_reset", $f_reset);
$smarty->assign("f_error", $f_error);
$smarty->assign("f_success", $f_success);

function unlinkrec($base) {

	foreach (scandir($base) as $file) {

		if (is_dir($base."/".$file)) {
			if (!in_array($file, array(".", ".."))) {
				@unlinkrec($base."/".$file);
			}
		} else {
			@unlink($base."/".$file);
		}
	}
	@rmdir($base);
}

?>
