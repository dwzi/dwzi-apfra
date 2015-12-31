<?php

$datetime = date("Y-m-d H:i:s");
$rowid = isset($_SESSION["psd"]["rowid"]) ? $_SESSION["psd"]["rowid"] : "";
$force = isset($_SESSION["psd"]["force"]) ? $_SESSION["psd"]["force"] : 0;

if ($id > 0) {

	if ($apfra_rights[$module]["del"] == 0) {

		reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort);
	}

	$found = array();

	if ($result = $db->Execute("select (select aTable from aTable where id = refid_aTable_1) as aTable1, (select aTable from aTable where id = refid_aTable_n) as aTablen from aRef1n having aTable1 = '".$module."' or aTablen = '".$module."'")) {

		while (!$result->EOF) {

			$tmptable = "ref1n_".$result->fields["aTable1"]."_".$result->fields["aTablen"];
			$tmptablen = $result->fields["aTable1"] == $module ? $result->fields["aTablen"] : $result->fields["aTable1"];
			if ($sresult = $db->Execute("select id, refid_".$tmptablen." from ".$tmptable." where refid_".$module." = '".$id."'")) {

				while (!$sresult->EOF) {

					$found[$tmptable]["ids"][] = $sresult->fields["refid_".$tmptablen];
					$sresult->MoveNext();
				}
			}
			if (isset($found[$tmptable]["ids"])) {
				$found[$tmptable]["ids"] = array_unique($found[$tmptable]["ids"]);
				$found[$tmptable]["desc"] = $tmptablen;
			}
			$result->MoveNext();
		}
	}

	if ($result = $db->Execute("select (select aTable from aTable where id = refid_aTable) as aTable, aField from aField where aField = 'refid_".$module."' or aField like 'refid\_".$module."\_%'")) {

		while (!$result->EOF) {

			$tmptable = $result->fields["aTable"];
			if ($sresult = $db->Execute("select id from ".$tmptable." where ".$result->fields["aField"]." = '".$id."'")) {

				while (!$sresult->EOF) {

					$found[$tmptable]["ids"][] = $sresult->fields["id"];
					$sresult->MoveNext();
				}
			}
			if (isset($found[$tmptable]["ids"])) {
				$found[$tmptable]["desc"] = $tmptable;
			}
			$result->MoveNext();
		}
	}

	if (count($found) && $force == 0) {

		reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort."&did=".$id."&rowid=".$rowid."&delerr=".base64_encode(serialize($found)));

	} else {

		if (count($found) && $force == 1) {

			foreach ($found as $deltable => $idarr) {

				if (substr($deltable, 0, 6) == "ref1n_") {

					$tmparr = explode("_", $deltable);
					$result = $db->Execute("delete from ".$deltable." where refid_".$tmparr[1]." = '".$id."'");

				} else {

					foreach ($idarr["ids"] as $delid) {

						$delta = array();
						if ($result = $db->Execute("select * from ".$deltable." where id = '".$delid."' limit 1")) {
							if (!$result->EOF) {
								foreach ($result->fields as $key => $field) {
									if (!is_numeric($key)) {
										$delta[$key] = $field;
									}
								}
								$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
								unset($delta["id"]);
							}
						}
						$apfra_log_db->delete($deltable, $delid, $delta);

						$result = $db->Execute("delete from ".$deltable." where id = '".$delid."' limit 1");
					}
				}
			}
		}

		$delta = array();
		if ($result = $db->Execute("select * from ".$datasql_table." where id = '".$id."' limit 1")) {
			if (!$result->EOF) {
				foreach ($result->fields as $key => $field) {
					if (!is_numeric($key)) {
						$delta[$key] = $field;
					}
				}
				$delta["aLastUpdate"] = $result->fields["aLastUpdate"] ? $result->fields["aLastUpdate"] : "0000-00-00 00:00:00";
				unset($delta["id"]);
			}
		}
		$apfra_log_db->delete($datasql_table, $id, $delta);

		$result = $db->Execute("delete from ".$datasql_table." where id = '".$id."' limit 1");

		reload_page("?mod=".$module."&p=".$page."&pp=".$perpage."&s=".$search."&sort=".$sort."&dir=".$dirsort);
	}
}

?>
