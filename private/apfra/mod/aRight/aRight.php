<?php

$formaction = isset($_SESSION["psd"]["fa"]) ? $_SESSION["psd"]["fa"] : "";
$datetime = date("Y-m-d H:i:s");

if ($formaction == "save") {

	$tmpr = array();
	foreach ($_SESSION["psd"] as $key => $value) {
		if (substr($key,0,2) == "f_") {

			$tmpkarr = explode("_", substr($key,2,255));
			$tmp_roleid = $tmpkarr[0];
			$tmp_moduleid = $tmpkarr[1];
			$tmp_op = $tmpkarr[2];

			$tmpr[$tmp_roleid][$tmp_moduleid][$tmp_op] = 1;
		}
	}

	$db->Execute("truncate aRight");

	foreach ($tmpr as $roleid => $modulearr) {

		foreach ($modulearr as $moduleid => $oparr) {

			$tmp_sel = isset($oparr["sel"]) ? 1 : 0;
			$tmp_ins = isset($oparr["ins"]) ? 1 : 0;
			$tmp_upd = isset($oparr["upd"]) ? 1 : 0;
			$tmp_del = isset($oparr["del"]) ? 1 : 0;

			$query = "insert into aRight (refid_aRole, refid_aModule, aselect, ainsert, aupdate, adelete, aLastUpdate, refid_aUser_update) values ('".$roleid."', '".$moduleid."', '".$tmp_sel."', '".$tmp_ins."', '".$tmp_upd."', '".$tmp_del."', '".$datetime."', '".$_SESSION["psu"]["id"]."')";
			$db->Execute($query);
		}
	}

	$tmprights = new apfra_rights($db);
	$_SESSION["psu"]["rights"] = $tmprights->readrights();

	reload_page("?mod=".$module);
}

$data_role = array();
if ($result = $db->Execute("select id, aRole from aRole order by aRole")) {

	while (!$result->EOF) {

		$data_role[] = array(
				"id" => $result->fields["id"],
				"aRole" => $result->fields["aRole"]
		);

		$result->MoveNext();
	}
}
$smarty->assign("data_role", $data_role);

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

$data = array();
if ($result = $db->Execute("select id, refid_aRole, refid_aModule, aselect, ainsert, aupdate, adelete from aRight")) {

	while (!$result->EOF) {

		$data[$result->fields["refid_aRole"]][$result->fields["refid_aModule"]] = array(
				"sel" => $result->fields["aselect"],
				"ins" => $result->fields["ainsert"],
				"upd" => $result->fields["aupdate"],
				"del" => $result->fields["adelete"]
		);

		$result->MoveNext();
	}
}
$smarty->assign("data", $data);

?>
