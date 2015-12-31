<?php

$data = array();

switch ($action) {

case "download":
	require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_a_".$action.".php");
	break;
	
/* main */
case "":
default:
	require(DEF_PATH_PRIVATE."apfra/mod/".$module."/".$module."_main.php");
	break;
}

$smarty->assign("data", $data);

?>