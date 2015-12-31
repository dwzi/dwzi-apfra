<?php

print_r($fieldarr);

$tmpid_tab1 = $fieldarr["refid_aTable_1"];
$tmpid_tabn = $fieldarr["refid_aTable_n"];

$tmp_tab1 = "";
if ($result = $db->Execute("select aTable from aTable where id = '".$tmpid_tab1."'")) {

	if (!$result->EOF) {

		$tmp_tab1 = $result->fields["aTable"];
	}
}

$tmp_tabn = "";
if ($result = $db->Execute("select aTable from aTable where id = '".$tmpid_tabn."'")) {

	if (!$result->EOF) {

		$tmp_tabn = $result->fields["aTable"];
	}
}

$query = "";
$query .= "create table "."ref1n_".$tmp_tab1."_".$tmp_tabn." (";
$query .= "id int(11) unsigned not null AUTO_INCREMENT,";
$query .= "primary key (id),";
$query .= "refid_".$tmp_tab1." int(11) unsigned default null,";
$query .= "refid_".$tmp_tabn." int(11) unsigned default null,";
$query .= "aLastUpdate datetime default null,";
$query .= "refid_aUser_update int(11) unsigned default null";
$query .= ")";
$db->Execute($query);

?>