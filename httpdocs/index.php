<?php

/* exists config.inc.php ? */

if (file_exists("../private/config/config.inc.php")) {

	require("../private/config/config.inc.php");

} else {

	die("config.inc.php missing ... check private/config/config.inc.php or path to config.inc.php in httpdocs/index.php");
}

/* check configuration values */

if (DEF_URL == "") die("url configuration missing ... check private/config/config.inc.php");
if (DEF_PATH == "") die("path configuration missing ... check private/config/config.inc.php");
if (DEF_PATH_PRIVATE == "") die("private path configuration missing ... check private/config/config.inc.php");
if (DEF_DB == "") die("database name configuration missing ... check private/config/config.inc.php");
if (DEF_DB_HOST == "") die("database host configuration missing ... check private/config/config.inc.php");

if (substr(DEF_URL,-1) != "/" && substr(DEF_URL,-1) != "\\") die("url configuration error, trailing slash missing ... check private/config/config.inc.php");
if (substr(DEF_PATH,-1) != "/" && substr(DEF_PATH,-1) != "\\") die("path configuration error, trailing slash missing ... check private/config/config.inc.php");
if (substr(DEF_PATH_PRIVATE,-1) != "/" && substr(DEF_PATH_PRIVATE,-1) != "\\") die("private path configuration error, trailing slash missing ... check private/config/config.inc.php");

if (DEF_PREVENT_AUTOLOGOUT == "1" && DEF_AUTORELOAD == "0" && DEF_AUTOLOGOUT == "0") die("prevent autologout mismatch, you had to choose between autologout and autoreload ... check private/config/config.inc.php");
if (DEF_PREVENT_AUTOLOGOUT == "1" && DEF_AUTORELOAD == "1" && DEF_AUTOLOGOUT == "1") die("prevent autologout mismatch, you had to choose between autologout and autoreload ... check private/config/config.inc.php");
if (DEF_PREVENT_AUTOLOGOUT == "1" && !is_numeric(DEF_PREVENT_TIME)) die("prevent autologout mismatch, invalid prevent time ... check private/config/config.inc.php");
if (DEF_PREVENT_AUTOLOGOUT == "1" && DEF_PREVENT_TIME == "0") die("prevent autologout mismatch, invalid prevent time ... check private/config/config.inc.php");
if (DEF_PREVENT_AUTOLOGOUT == "1" && DEF_PREVENT_TIME < "60") die("prevent autologout mismatch, invalid prevent time ... check private/config/config.inc.php");

/* init */

require(DEF_PATH_PRIVATE."apfra/lib/init.inc.php");

$class = "";
$module = isset($_SESSION["psd"]["mod"]) && $_SESSION["psd"]["mod"] ? $_SESSION["psd"]["mod"] : "index";
$action = isset($_SESSION["psd"]["a"]) && $_SESSION["psd"]["a"] ? $_SESSION["psd"]["a"] : "";

$page = isset($_SESSION["psd"]["p"]) && $_SESSION["psd"]["p"] ? $_SESSION["psd"]["p"] : "";
$perpage = isset($_SESSION["psd"]["pp"]) && $_SESSION["psd"]["pp"] ? $_SESSION["psd"]["pp"] : 0;
$search = isset($_SESSION["psd"]["s"]) && $_SESSION["psd"]["s"] ? $_SESSION["psd"]["s"] : "";
$sort = isset($_SESSION["psd"]["sort"]) ? $_SESSION["psd"]["sort"] : "";
$dirsort = isset($_SESSION["psd"]["dir"]) ? $_SESSION["psd"]["dir"] : "asc";

$apfra_rights = isset($_SESSION["psu"]["rights"]) && $_SESSION["psu"]["rights"] ? $_SESSION["psu"]["rights"] : array();

$apfra_db_desc = array();
if ($logged_in) {

	if ($result = $db->Execute("select aTable, aTableDesc from aTable")) {

		while (!$result->EOF) {

			$apfra_db_desc[$result->fields["aTable"]] = $result->fields["aTableDesc"];
			$result->MoveNext();
		}
	}
}

require(DEF_PATH_PRIVATE."apfra/lib/menu.inc.php");

/* template/module */

if (!$logged_in && !in_array($module, array("login", "quicklogin", "loggedout", "quicklogout", "reset"))) {

	$module = "index";

} else {

	if (!$is_admin && $logged_in && !in_array($module, array("index", "login", "quicklogin", "logout", "quicklogout", "loggedout", "reset", "aSettings")) && $apfra_rights[$module]["sum"] == 0) {

		$module = "index";
	}

	if (file_exists(DEF_PATH_PRIVATE."apfra/config/datasql/".$module.".datasql.php")) {

		$class = "datasql";
		require(DEF_PATH_PRIVATE."apfra/config/datasql/".$module.".datasql.php");
		require(DEF_PATH_PRIVATE."apfra/class/datasql/datasql.php");

	} elseif (file_exists(DEF_PATH_PRIVATE."config/datasql/".$module.".datasql.php")) {

		$class = "datasql";
		require(DEF_PATH_PRIVATE."config/datasql/".$module.".datasql.php");
		require(DEF_PATH_PRIVATE."apfra/class/datasql/datasql.php");

	} elseif (file_exists(DEF_PATH_PRIVATE."config/datafile/".$module.".datafile.php")) {

		$class = "datafile";
		require(DEF_PATH_PRIVATE."config/datafile/".$module.".datafile.php");
		require(DEF_PATH_PRIVATE."apfra/class/datafile/datafile.php");

	} else {

		if (file_exists(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module.".php")) {

			require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module.".php");

		} elseif (file_exists(DEF_PATH_PRIVATE."mod/".$module."/".$module.".php")) {

			require(DEF_PATH_PRIVATE."mod/".$module."/".$module.".php");
		}
		if (!file_exists(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module.".tpl.php") && !file_exists(DEF_PATH_PRIVATE."mod/".$module."/".$module.".tpl.php")) {

			$module = "index";
		}
	}
}


$smarty->assign("apfra_prevent_autologout", DEF_PREVENT_AUTOLOGOUT);
$smarty->assign("apfra_autoreload", DEF_AUTORELOAD);
$smarty->assign("apfra_autologout", DEF_AUTOLOGOUT);
$smarty->assign("apfra_prevent_time", DEF_PREVENT_TIME*1000);

$smarty->assign("apfra_db_desc", $apfra_db_desc);
$smarty->assign("apfra_rights", $apfra_rights);

$smarty->assign("page", $page);
$smarty->assign("perpage", $perpage);
$smarty->assign("search", $search);
$smarty->assign("sort", $sort);
$smarty->assign("dirsort", $dirsort);

$smarty->assign("class", $class);
$smarty->assign("module", $module);
$smarty->assign("action", $action);
$smarty->display("index.tpl.php");

require(DEF_PATH_PRIVATE."apfra/lib/exit.inc.php");

?>
