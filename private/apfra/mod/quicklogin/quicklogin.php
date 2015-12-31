<?php

$datetime = date("Y-m-d H:i:s");
$errors = 0;
$error_active = 0;
$data_input = array();

if (isset($_SESSION["psd"]["login"]) && $_SESSION["psd"]["login"] == "true") {

	$fields = array("aUser", "kennwort");
	foreach ($fields as $value) {

		$data_input[$value] = isset($_SESSION["psd"]["f_".$value]) ? $_SESSION["psd"]["f_".$value] : "";
	}

//	$errors += strlen($data_input["aUser"]) < 5 ? 1 : 0;
	$errors += strlen($data_input["aUser"]) == 0 ? 1 : 0;

	if ($result = $db->Execute("select id, aktiv from aUser where aUser = '".$data_input["aUser"]."' and kennwort = password('".$data_input["kennwort"]."') limit 1")) {

		if (!$result->EOF) {

			if ($result->fields["aktiv"] == 0) {

				$errors++;
				$error_active = 1;
			}
		}
	}

	if (!$errors) {

		if ($result = $db->Execute("select id, letzterlogin, (select aTheme from aTheme where id = refid_aTheme) as aTheme from aUser where aUser = '".$data_input["aUser"]."' and kennwort = password('".$data_input["kennwort"]."') and aktiv = '1' limit 1")) {

			if (!$result->EOF) {

				/* user-login ok */

				$_SESSION["psu"] = array();
				$_SESSION["psu"]["id"] = $result->fields["id"];
				$_SESSION["psu"]["benutzer"] = $data_input["aUser"];
				$_SESSION["psu"]["letzterlogin"] = $result->fields["letzterlogin"];
				$_SESSION["psu"]["timestamp"] = $datetime;
				$_SESSION["psu"]["theme"] = $result->fields["aTheme"];

				/* get user rights */

				$tmprights = new apfra_rights($db);
				$_SESSION["psu"]["rights"] = $tmprights->readrights();

				/* get menu */

				$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
				$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();

				/* update lastlogin */

				$uresult = $db->Execute("update aUser set letzterlogin = '".$datetime."' where id = '".$_SESSION["psu"]["id"]."' limit 1");

				$apfra_log_user->login();

			} else {

				$errors++;
			}
		}

		if (!$errors) {

			reload_page("?mod=index");

		} else {

			$apfra_log_user->login_failed($data_input["aUser"]);

			$errors++;
		}
	}
}

$data_user = array();
if ($result = $db->Execute("select aUser, vorname, nachname from aUser where aktiv = '1' order by nachname, vorname")) {

	while (!$result->EOF) {

		$data_user[] = array(
				"aUser" => $result->fields["aUser"],
				"vorname" => $result->fields["vorname"],
				"nachname" => $result->fields["nachname"]
		);
		$result->MoveNext();
	}
}
$smarty->assign("data_user", $data_user);

$smarty->assign("errors", $errors);
$smarty->assign("error_active", $error_active);

?>
