<?php

if (isset($_SESSION["psd"]["logout"]) && $_SESSION["psd"]["logout"] == "true") {

	$apfra_log_user->logout();
	
	unset($_SESSION["psu"]);
	unset($_SESSION["psd"]);

	session_destroy();
	
	reload_page("?mod=loggedout");
}

?>