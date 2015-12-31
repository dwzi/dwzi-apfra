<?php

class apfra_log_user {

	private $adodb;
	private $dbtable;

	function apfra_log_user($adodb, $dbtable = 'aLogUser') {

		$this->adodb = $adodb;
		$this->dbtable = $dbtable;		
	}

	private function log($action, $userid, $info = '') {

		$datetime = date("Y-m-d H:i:s");
		$result = $this->adodb->Execute("insert into ".$this->dbtable." (refid_aUser, action, ainfo, host, stamp) values ('".$userid."', '".$action."', ".($info ? "'".$info."'" : 'NULL').", '".$_SERVER["REMOTE_ADDR"]."', '".$datetime."')");		
	}

	function login() {

		self::log('login', $_SESSION["psu"]["id"]);
	}

	function login_failed($info = '') {

		self::log('login failed', 0, $info);
	}
	
	function logout() {

		self::log('logout', $_SESSION["psu"]["id"]);
	}
}

?>