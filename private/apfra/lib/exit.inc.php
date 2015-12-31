<?php

/* stop session */

session_write_close();

/* close database connection */

if (isset($db)) {

	$db->Close();
}

?>