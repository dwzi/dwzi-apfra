<?php

$id = isset($_SESSION["psd"]["id"]) ? $_SESSION["psd"]["id"] : 0;
$page = isset($_SESSION["psd"]["p"]) ? $_SESSION["psd"]["p"] : 1;
$search = isset($_SESSION["psd"]["s"]) ? $_SESSION["psd"]["s"] : "";
$sort = isset($_SESSION["psd"]["sort"]) ? $_SESSION["psd"]["sort"] : "";
$dirsort = isset($_SESSION["psd"]["dir"]) ? $_SESSION["psd"]["dir"] : "asc";
$fpath = isset($_SESSION["psd"]["fpath"]) ? DS.$_SESSION["psd"]["fpath"] : "";

$data = array();


switch ($action) {
/*
case "edit":
case "delete":
	require(DEF_PATH_PRIVATE."apfra".DS."class".DS."datafile".DS."datafile_a_".$action.".php");
	break;
*/
/* main */
case "":
default:
	require(DEF_PATH_PRIVATE."apfra".DS."class".DS."datafile".DS."datafile_main.php");
	break;
}

$smarty->assign("data", $data);
$smarty->assign("id", $id);
$smarty->assign("page", $page);
$smarty->assign("search", $search);
$smarty->assign("sort", $sort);
$smarty->assign("dirsort", $dirsort);
$smarty->assign("fpath", $fpath);

?>
