<?php

class apfra_log_db {

	private $adodb;
	private $dbtable;

	function apfra_log_db($adodb, $dbtable = 'aLogDB') {

		$this->adodb = $adodb;
		$this->dbtable = $dbtable;		
	}

	private function log($action, $table, $id, $fieldarr) {

		$datetime = date("Y-m-d H:i:s");
		$tmp_tableid = 0;
		if ($result = $this->adodb->Execute("select id from aTable where aTable = '".$table."' limit 1")) {
			if (!$result->EOF) {
				$tmp_tableid = $result->fields["id"];
			}
		}		
		$result = $this->adodb->Execute("insert into ".$this->dbtable." (refid_aUser, action, refid_aTable, refid, afields, stamp) values ('".$_SESSION["psu"]["id"]."', '".$action."', '".$tmp_tableid."', '".$id."', '".serialize($fieldarr)."', '".$datetime."')");		
	}

	function update($table, $id, $fieldarr) {

		self::log('update', $table, $id, $fieldarr);
	}

	function delete($table, $id, $fieldarr) {

		self::log('delete', $table, $id, $fieldarr);
	}
}

?>