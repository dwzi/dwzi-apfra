<?php

$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";
$datetime = date("Y-m-d H:i:s");

$f_error = isset($_SESSION["psd"]["f_error"]) ? $_SESSION["psd"]["f_error"] : 0;
$f_success = isset($_SESSION["psd"]["f_success"]) ? $_SESSION["psd"]["f_success"] : 0;

$f_table = isset($_SESSION["psd"]["f_table"]) ? $_SESSION["psd"]["f_table"] : "";

$f_stamp = isset($_SESSION["psd"]["f_stamp"]) ? $_SESSION["psd"]["f_stamp"] : "";

$f_imp_hdr = isset($_SESSION["psd"]["f_imp_hdr"]) ? $_SESSION["psd"]["f_imp_hdr"] : 1;
$f_imp_sep = isset($_SESSION["psd"]["f_imp_sep"]) ? $_SESSION["psd"]["f_imp_sep"] : ";";
$f_imp_enc = isset($_SESSION["psd"]["f_imp_enc"]) ? $_SESSION["psd"]["f_imp_enc"] : "\"";

$step = isset($_SESSION["psd"]["step"]) ? $_SESSION["psd"]["step"] : 1;

$steps = array(
		array("step" => 1, "desc" => "Tabelle", "ok" => ($f_table ? 1 : 0)),
		array("step" => 2, "desc" => "Dateiupload", "ok" => ($f_stamp ? 1 : 0)),
		array("step" => 3, "desc" => "Zuordnung/Vorschau", "ok" => ($f_stamp ? 1 : 0))
);

if ($formaction == "prev" || $formaction == "next" || $formaction == "direct") {

	reload_page("?mod=".$module."&step=".$step."&f_table=".$f_table."&f_stamp=".$f_stamp);
}

if ($formaction == "import") {

	$sql_fields = array();	
	$sql_nofield = array();
	$fieldmapping = array();
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,7) == "f_field") {
			$fieldmapping[substr($key,8,255)] = $value;
			if ($value) {
				$sql_fields[] = $value;
			} else {
				$sql_nofield[] = substr($key,8,255);
			}
		}
	}
	
	$data_header = array();

	$tmpfile = sys_get_temp_dir().$f_stamp;
	
	if ($fp = fopen($tmpfile, "r")) {
	
		$line = 0;
	
		if ($f_imp_hdr) {
	
			$data_header = fgetcsv($fp, 0, $f_imp_sep, $f_imp_enc);
		}
	
		while ($tmpline = fgetcsv($fp, 0, $f_imp_sep, $f_imp_enc)) {
			
			$line++;
			
			if (count($sql_nofield)) {
				foreach ($sql_nofield as $value) {
					unset($tmpline[$value]);
				}				
			}
//			$tmpline = array_map("addslashes", $tmpline);
			$tmpline2 = array();
			foreach ($tmpline as $value) {
				if (!$value) {
					$tmpline2[] = "NULL";
				} else {					
					$tmpline2[] = "'".mb_convert_encoding($value, "UTF-8", mb_detect_encoding($value, "UTF-8, ISO-8859-1, ISO-8859-15", true))."'";
				}
			}
			$tmpline = $tmpline2;
				
			$query = "insert into ".$f_table." (".implode(",",$sql_fields).") values (".implode(",",$tmpline).")";
			if (!$db->Execute($query))  {
				echo $query."<br>";
			}
		}
		fclose($fp);
	}
	echo $line." importiert!<br>";	
	
	//import errors?
	//unlink
	//reload
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
		if ($formaction == "upload") {
		
			if ($f_stamp != "") {
				
				$tmpfile = sys_get_temp_dir().$f_stamp;
				@unlink($tmpfile);
			}
			
			$tmpdir = "";
		
			if ($_SESSION["psd"]["fd_file"]["error"] == 0) {
		
				$tmp_finfo = new finfo(FILEINFO_MIME_TYPE);
		
				if (false === $ext = array_search($tmp_finfo->file(sys_get_temp_dir().$_SESSION["psd"]['fd_file']['tmpstamp']), array('text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/octet-stream', 'application/txt'), true)) {
		
					@unlink(sys_get_temp_dir().$_SESSION["psd"]["fd_file"]["tmpstamp"]);
					reload_page("?mod=".$module."&step=".$step."&f_table=".$f_table."&f_stamp=".$f_stamp."&f_error=5");
		
				} else {
		
					$tmpstamp = $_SESSION["psd"]["fd_file"]["tmpstamp"];
					$tmpfile = sys_get_temp_dir().$tmpstamp;
						
					if ($fp = fopen($tmpfile, "r")) {
		
						if ($tmpline = fgetcsv($fp, 0, $f_imp_sep, $f_imp_enc) === false) {
								
							@unlink($tmpfile);
							reload_page("?mod=".$module."&step=".$step."&f_table=".$f_table."&f_stamp=".$f_stamp."&f_error=8");
						}
						fclose($fp);
		
					} else {
		
						@unlink($tmpfile);
						reload_page("?mod=".$module."&step=".$step."&f_table=".$f_table."&f_stamp=".$f_stamp."&f_error=8");
					}
		
					reload_page("?mod=".$module."&step=".($step+1)."&f_table=".$f_table."&f_stamp=".$tmpstamp);
				}
		
		 	} elseif ($_SESSION["psd"]["fd_file"]["f_error"]) {

				reload_page("?mod=".$module."&step=".$step."&f_table=".$f_table."&f_stamp=".$f_stamp."&f_error=".$_SESSION["psd"]["fd_file"]["f_error"]);
 			}
		}
		break;
		
	case 3:
		if ($f_stamp != "") {
		
			$data = array();
			$data_header = array();
			$tmpfile = sys_get_temp_dir().$f_stamp;

			if ($fp = fopen($tmpfile, "r")) {
		
				$line = 0;
		
				if ($f_imp_hdr) {
		
					$data_header = fgetcsv($fp, 0, $f_imp_sep, $f_imp_enc);
					
					for ($i=0; $i<count($data_header); $i++) {
						$data_header[$i] = mb_convert_encoding($data_header[$i], "UTF-8", mb_detect_encoding($data_header[$i], "UTF-8, ISO-8859-1, ISO-8859-15", true));
					}
				}
		
				while ($line < 10 && $tmpline = fgetcsv($fp, 0, $f_imp_sep, $f_imp_enc)) {
		
					$line++;
					
					for ($i=0; $i<count($tmpline); $i++) {
						$tmpline[$i] = mb_convert_encoding($tmpline[$i], "UTF-8", mb_detect_encoding($tmpline[$i], "UTF-8, ISO-8859-1, ISO-8859-15", true));
					}
					$data[] = $tmpline;
				}
				fclose($fp);
			}

			$data_fields = array();
			if ($result = $db->Execute("select aField, aFieldDesc from aField where refid_aTable = (select id from aTable where aTable = '".$f_table."') order by aFieldDesc")) {
			
				while (!$result->EOF) {
			
					$data_fields[] = array(
						"field" => $result->fields["aField"],
						"desc" => $result->fields["aFieldDesc"] ? $result->fields["aFieldDesc"] : $result->fields["aField"]
					);
					$result->MoveNext();
				}
			}

			$smarty->assign("data_fields", $data_fields);
			$smarty->assign("data_header", $data_header);
		}		
		break;
}
		

$smarty->assign("data", $data);

$smarty->assign("steps", $steps);
$smarty->assign("step", $step);

$smarty->assign("f_table", $f_table);
$smarty->assign("f_stamp", $f_stamp);

$smarty->assign("f_error", $f_error);
$smarty->assign("f_success", $f_success);

$smarty->assign("f_imp_hdr", $f_imp_hdr);
$smarty->assign("f_imp_sep", $f_imp_sep);
$smarty->assign("f_imp_enc", $f_imp_enc);

?>