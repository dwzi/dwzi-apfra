<?php

$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";

$errors = 0;
$datetime = date("Y-m-d H:i:s");

if ($formaction == "save") {

	$fieldarr = array(
		"titel" => "",
		"vorname" => "",
		"nachname" => "",
		"email" => "",
		"kennwort" => "",
		"refid_aTheme" => "0"
	);
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,2) == "f_") {
			$fieldarr[substr($key,2,255)] = $value;
		}
	}
	
	$tmp_pwd1 = $fieldarr["kennwort"];
	$tmp_pwd2 = isset($_SESSION["psd"]["fp_kennwort"]) ? $_SESSION["psd"]["fp_kennwort"] : "";
	if ($tmp_pwd1 || $tmp_pwd2) {
		if ($tmp_pwd1 != $tmp_pwd2) {
			$fieldarr["kennwort"] = "";
			// TODO set error
		}
	}
	
	if (!$errors) {
		
		$fieldarr["aLastUpdate"] = $datetime;
		$fieldarr["refid_aUser_update"] = $_SESSION["psu"]["id"];
		
		if ($fieldarr["refid_aTheme"] == "") {
			$fieldarr["refid_aTheme"] = "0";
		}
		
		$query = "update aUser set ";
		foreach (array("titel", "vorname", "nachname", "email", "refid_aTheme") as $key) {
			$value = $fieldarr[$key];
			$query .= $key." = '".$value."', ";
		}
		if ($fieldarr["kennwort"] <> "") $query .= "kennwort = password('".$fieldarr["kennwort"]."'), "; 		
		$query = substr($query, 0, -2);
		$query .= " where id = '".$_SESSION["psu"]["id"]."'";

		$delta = array();
		if ($result = $db->Execute("select aLastUpdate, titel, vorname, nachname, email, refid_aTheme from aUser where id = '".$_SESSION["psu"]["id"]."' limit 1")) {
			if (!$result->EOF) {
				$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
				
				if ($fieldarr["titel"] != $result->fields["titel"]) $delta["titel"] = $result->fields["titel"];
				if ($fieldarr["vorname"] != $result->fields["vorname"]) $delta["vorname"] = $result->fields["vorname"];
				if ($fieldarr["nachname"] != $result->fields["nachname"]) $delta["nachname"] = $result->fields["nachname"];
				if ($fieldarr["email"] != $result->fields["email"]) $delta["email"] = $result->fields["email"];
				if ($fieldarr["kennwort"] <> "") $delta["kennwort"] = "#";
				if ($fieldarr["refid_aTheme"] != $result->fields["refid_aTheme"]) $delta["refid_aTheme"] = $result->fields["refid_aTheme"];
			}
		}
		
		if (!$result = $db->Execute($query)) {
echo $query;
die();
			$errors++;
		} else {
			
			$tmptheme = "";
			if ($result = $db->Execute("select aTheme from aTheme where id = '".$fieldarr["refid_aTheme"]."' limit 1")) {
				if (!$result->EOF) {
					$tmptheme = $result->fields["aTheme"];
				}
			}
			$_SESSION["psu"]["theme"] = $tmptheme;

			$apfra_log_db->update('aUser', $_SESSION["psu"]["id"], $delta);

			reload_page("?mod=".$module);
		}
	}
}

$data_theme = array();
if ($result = $db->Execute("select id, aTheme from aTheme order by aTheme")) {

	while (!$result->EOF) {

		$data_theme[] = array(
				"id" => $result->fields["id"],
				"aTheme" => $result->fields["aTheme"]
		);
		$result->MoveNext();
	}
}
$smarty->assign("data_theme", $data_theme);

$data = array();
if ($result = $db->Execute("select aUser, titel, vorname, nachname, email, letzterlogin, refid_aTheme, aLastUpdate, (select aUser from aUser where id = aUser.refid_aUser_update) as ref_benutzer from aUser where id = '".$_SESSION["psu"]["id"]."' limit 1")) {

	if (!$result->EOF) {

		$data = array(
			"aUser" => $result->fields["aUser"],
			"titel" => $result->fields["titel"],
			"vorname" => $result->fields["vorname"],
			"nachname" => $result->fields["nachname"],
			"email" => $result->fields["email"],
			"letzterlogin" => $result->fields["letzterlogin"],
			"refid_aTheme" => $result->fields["refid_aTheme"],
			"aLastUpdate" => $result->fields["aLastUpdate"],
			"ref_benutzer"  => $result->fields["ref_benutzer"]
		);
	}
}

$smarty->assign("data", $data);
$smarty->assign("errors", $errors);

?>