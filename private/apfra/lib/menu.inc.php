<?php

if (!$logged_in) {

	$menu = array(
		array("module" => "login", "desc" => "Anmelden")
	);

} else {

	$menu = isset($_SESSION["psu"]["menu"]) && $_SESSION["psu"]["menu"] ? $_SESSION["psu"]["menu"] : array();

	if ($is_admin) {

		$menu[] = array("module" => "aReport", "desc" => "Berichte");
		$menu[] = array("desc" => "Administration", "submenu" => array(
			array("module" => "aRight", "desc" => "Rechte"),
			array("module" => "aRole", "desc" => "Rollen"),
			array("module" => "aUser", "desc" => "Benutzer"),
			"#divider",
			array("module" => "aImportData", "desc" => "Daten-Import"),
			array("module" => "aExportData", "desc" => "Daten-Export")
		));
		$menu[] = array("desc" => "Setup", "submenu" => array(
			array("module" => "aTable", "desc" => "Tabellen"),
			array("module" => "aField", "desc" => "Datenfelder"),
			array("module" => "aRef", "desc" => "Referenzen"),
			array("module" => "aRef1n", "desc" => "Referenzen 1:n"),
			array("module" => "aSync", "desc" => "Datenbank Sync"),
			"#divider",
			array("module" => "aModuleType", "desc" => "Modultypen"),
			array("module" => "aModule", "desc" => "Module"),
			array("module" => "aMenu", "desc" => "Menü"),
			"#divider",
			array("module" => "aImportConfig", "desc" => "Import Konfiguration"),
			array("module" => "aExportConfig", "desc" => "Export Konfiguration"),
			"#divider",
			array("module" => "aTheme", "desc" => "Themen"),
			array("module" => "aFieldType", "desc" => "Datenfeldtypen")
		));
	}
}

function createmenu($tmpmenu) {

	$menu = array();

	if (count($tmpmenu)) {

		foreach ($tmpmenu as $menuitem) {

			if (is_array($menuitem)) {

				if (isset($menuitem["submenu"])) {

					$menu[] = array("type" => "submenu", "desc" => $menuitem["desc"], "submenu" => createmenu($menuitem["submenu"]));

				} else {

					$menu[] = array("module" => $menuitem["module"], "desc" => $menuitem["desc"], "type" => "single");
				}

			} else {

				if ($menuitem == "#divider") {

					$menu[] = array("type" => "divider");
				}
			}
		}
	}

	return $menu;
}

$menu = createmenu($menu);

$submenu = "";
$moduledesc = "";
if (count($menu)) {
	foreach ($menu as $menuitem) {
		if ($menuitem["type"] == "submenu") {
			foreach ($menuitem["submenu"] as $submenuitem) {
				if ($submenuitem["type"] == "single") {
					if ($module == $submenuitem["module"]) {
						$submenu = $menuitem["desc"];
						$moduledesc = $submenuitem["desc"];
					}
				}
			}
		} elseif ($menuitem["type"] == "single") {
			if ($module == $menuitem["module"]) $moduledesc = $menuitem["desc"];
		}
	}
}

$breadcrumb = array();
$breadcrumb[] = array(
		"desc" => "Startseite",
		"link" => DEF_URL,
		"class" => ($module == "index" ? "active" : "")
		);

if (!in_array($module, array("index", "aSettings", "logout"))) {

	if ($submenu) {

		$breadcrumb[] = array(
				"desc" => $submenu,
				"link" => "",
				"class" => "active"
		);
	}

	if ($action == "") {

		$breadcrumb[] = array(
				"desc" => $moduledesc,
				"link" => "",
				"class" => "active"
				);

	} else {

		$breadcrumb[] = array(
				"desc" => $moduledesc,
				"link" => DEF_URL."?mod=".$module."&p=".$page."&s=".$search."&sort=".$sort."&dir=".$dirsort,
				"class" => ""
				);
		$breadcrumb[] = array(
				"desc" => ($action == "edit" ? "bearbeiten" : $action),
				"link" => "",
				"class" => "active"
				);
	}

} else {

	switch ($module) {

	case "aSettings":
		$breadcrumb[] = array("desc" => "Einstellungen", "link" => "", "class" => "active");
		break;

	case "logout":
		$breadcrumb[] = array("desc" => "abmelden", "link" => "", "class" => "active");
		break;
	}

}

$smarty->assign("breadcrumb", $breadcrumb);

$smarty->assign("menu", $menu);

?>
