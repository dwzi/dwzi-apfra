<?php

$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";

if ($formaction == "save") {

	$query = "truncate aMenu";
	$db->Execute($query);
	$datetime = date("Y-m-d H:i:s");

	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,7) == "f_menu_") {

			$tmpa = explode("_",$key);
			$tmpmodid = $tmpa[2];
			$tmppid = $tmpa[4];
			$tmpid = $tmpa[6];

			if ($tmpid) {
				$query = "insert into aMenu (id, refid_aMenu_parent, pos, aMenu, refid_aModule, aLastUpdate, refid_aUser_update) values ('".$tmpid."', '".$tmppid."', '".$tmpid."', '".$value."', '".$tmpmodid."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
				$db->Execute($query);
			}
		}
	}

	$tmpmenu = new apfra_menu($db, $_SESSION["psu"]["rights"]);
	$_SESSION["psu"]["menu"] = $tmpmenu->readmenu();

	reload_page("?mod=".$module);
}

$tmpmenu = isset($_SESSION["psu"]["menu"]) && $_SESSION["psu"]["menu"] ? $_SESSION["psu"]["menu"] : array();
$data = createmenu($tmpmenu);

$data_modmenu = array();
if (count($data)) {
	foreach ($data as $menuarr) {
		if (isset($menuarr["module"])) {
			$data_modmenu[] = $menuarr["module"];
		} elseif (isset($menuarr["submenu"]) && count($menuarr["submenu"])) {
			foreach ($menuarr["submenu"] as $submenuarr) {
				if (isset($submenuarr["module"])) {
					$data_modmenu[] = $submenuarr["module"];
				}
			}
		}
	}
}

$data_mod = array();
$query = "select id, aModule, aModuleDesc from aModule where aModule not in ('".implode("','", array_merge($apfra_tables, $data_modmenu))."') order by aModule";
if ($result = $db->Execute($query)) {
	while (!$result->EOF) {
		$data_mod[] = array(
			"id" => $result->fields["id"],
			"module" => $result->fields["aModule"],
			"desc" => $result->fields["aModuleDesc"]
		);
		$result->MoveNext();
	}
}

$data_modid = array();
$query = "select id, aModule from aModule where aModule not in ('".implode("','", $apfra_tables)."') order by aModule";
if ($result = $db->Execute($query)) {
	while (!$result->EOF) {
		$data_modid[$result->fields["aModule"]] = $result->fields["id"];
		$result->MoveNext();
	}
}

$smarty->assign("data", $data);
$smarty->assign("data_mod", $data_mod);
$smarty->assign("data_modid", $data_modid);

?>
