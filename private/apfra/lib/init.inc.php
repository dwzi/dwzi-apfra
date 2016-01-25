<?php

/* error handler */

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."error_handler.inc.php");

/* dont stop php script */

set_time_limit(0);

/* dont cache html output */

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html; charset=UTF-8");

$loc_de = setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
date_default_timezone_set('Europe/Vienna');

mb_internal_encoding("UTF-8");

/* start session */

session_name(DEF_SESSION);
session_start();

/* template */

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."smarty".DS."Smarty.class.php");

$smarty = new Smarty();

$smarty->setTemplateDir(array(
	"apfra" => DEF_PATH_PRIVATE."apfra".DS."mod".DS,
	"apfra_class" => DEF_PATH_PRIVATE."apfra".DS."class".DS,
	"user" => DEF_PATH_PRIVATE."mod".DS
));

$smarty->setCompileDir(DEF_PATH_PRIVATE."tplc");

//$smarty->template_dir = DEF_PATH_PRIVATE."";
//$smarty->compile_dir = DEF_PATH_PRIVATE."tplc";
//$smarty->cache_dir = DEF_PATH_PRIVATE."mod";
//$smarty->config_dir = DEF_PATH_PRIVATE."mod";
$smarty->force_compile = true;
$smarty->compile_check = false;
$smarty->caching = false;
$smarty->debugging = false;
//$smarty->debugging = DEF_DEBUG;
$smarty->muteExpectedErrors();

/* database-layer */

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."adodb5".DS."adodb.inc.php");

/* create database connection */

$db = NewADOConnection("mysqli://".DEF_DB_USER.":".DEF_DB_PASS."@".DEF_DB_HOST."/".DEF_DB) or die("error");
$db->Execute("SET NAMES utf8");
$db->Execute("SET CHARACTER SET utf8");

/* logging */

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."logdb.class.php");

$apfra_log_db = new apfra_log_db($db);

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."loguser.class.php");

$apfra_log_user = new apfra_log_user($db);

/* other classes */

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."rights.class.php");
require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."menu.class.php");
require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."zip.class.php");

/* standard parameters */

$apfra_tables = array('aField', 'aFieldType', 'aLogDB', 'aLogUser', 'aMenu', 'aModule', 'aRef', 'aRef1n', 'aReport', 'aRight', 'aRole', 'aSync', 'aTable', 'aTheme', 'aUser', 'aSettings', 'aImportConfig', 'aExportConfig', 'aImportData', 'aExportData', 'aModuleType');

/* file upload values */

$apfra_uploadsize = 0;
if (ini_get('file_uploads')) {

	$apfra_uploadsize = ini_get('upload_max_filesize');

	if (ini_get('post_max_size') < $apfra_uploadsize) {

		$apfra_uploadsize = ini_get('post_max_size');
	}
}
if ($apfra_uploadsize) {

	switch (strtoupper(substr($apfra_uploadsize, -1))) {

	case "G":
		$apfra_uploadsize = substr($apfra_uploadsize, 0, -1) * 1024 * 1024 * 1024;
		break;
	case "M":
		$apfra_uploadsize = substr($apfra_uploadsize, 0, -1) * 1024 * 1024;
		break;
	case "K":
		$apfra_uploadsize = substr($apfra_uploadsize, 0, -1) * 1024;
		break;
	}
}
$smarty->assign("uploadsize", $apfra_uploadsize);

/* post/get/file upload ? */

$_SESSION["psd"] = array();

if (count($_POST)) {

	$tmparr = $_POST;
	if (isset($_GET)) {

		$tmparr = array_merge($tmparr, $_GET);
	}

	if (count($_FILES)) {

		$tmpfilearr = array();

		foreach ($_FILES as $key => $valuearr) {

			if (!isset($valuearr['error']) || is_array($valuearr['error'])) {

				$valuearr["f_error"] = 1;

			} elseif ($valuearr["error"] == 0 && is_uploaded_file($valuearr["tmp_name"])) {

				if ($valuearr["size"] == 0) {

					@unlink($valuearr["tmp_name"]);
					$valuearr["f_error"] = 2;

				} elseif (!preg_match("`^[-0-9A-Z_\.]+$`i", $valuearr["name"])) {

					@unlink($valuearr["tmp_name"]);
					$valuearr["f_error"] = 3;

				} elseif (mb_strlen($valuearr["name"], "UTF-8") > 255) {

					@unlink($valuearr["tmp_name"]);
					$valuearr["f_error"] = 4;

				} else {

					$tmpstamp = uniqid();
					$tmpfile = sys_get_temp_dir().$tmpstamp;

					if (!move_uploaded_file($valuearr["tmp_name"], $tmpfile)) {

						$valuearr["f_error"] = 6;
					}

					$valuearr["tmpstamp"] = $tmpstamp;
				}

			} else {

				switch ($valuearr["error"]) {
					case UPLOAD_ERR_OK:
						break;
					case UPLOAD_ERR_NO_FILE:
						$valuearr["f_error"] = 9;
						break;
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						$valuearr["f_error"] = 10;
						break;
					default:
						$valuearr["f_error"] = 99;
						break;
				}
			}

			$tmpfilearr[$key] = $valuearr;
		}

		$tmparr = array_merge($tmparr, $tmpfilearr);
	}

	reload_page("?_="._encode_string_array($tmparr));
}

if (count($_GET)) {

	if (count($_GET) == 1 && isset($_GET["_"])) {

		$tmp = _decode_string_array($_GET["_"]);
		foreach ($tmp as $key => $value) {

			$_SESSION["psd"][$key] = is_array($value) ? $value : str_replace("'", "\'", htmlspecialchars(trim($value)));
		}

	} else {

		reload_page("?_="._encode_string_array($_GET));
	}
}

/* logged in ? */

$logged_in = isset($_SESSION["psu"]) && isset($_SESSION["psu"]["id"]) ? 1 : 0;

/* user role ? */

$is_admin = isset($_SESSION["psu"]) && isset($_SESSION["psu"]["id"])  && $_SESSION["psu"]["id"] == 1 ? 1 : 0;

/* default smarty vars */

if ($logged_in) {

	$smarty->assign("theme", $_SESSION["psu"]["theme"]);
	$smarty->assign("username", $_SESSION["psu"]["benutzer"]);
	$smarty->assign("userid", $_SESSION["psu"]["id"]);
}

/* reference11 */

if ($logged_in) {

	$datasql_reference11 = array();
	if ($result = $db->Execute("select aRef, aTable, aField, aSearch, aOrder from aRef, aTable where aRef.refid_aTable = aTable.id")) {

		while (!$result->EOF) {

			$datasql_reference11[$result->fields["aRef"]] = array(
					"table"     => $result->fields["aTable"],
					"field"     => $result->fields["aField"],
					"fieldnorm" => (strpos($result->fields["aField"], " as ") !== false ? substr($result->fields["aField"], strpos($result->fields["aField"], " as ")+4, strlen($result->fields["aField"])) : $result->fields["aField"]),
					"search"    => explode("/", $result->fields["aSearch"]),
					"orderby"   => explode("/", $result->fields["aOrder"])
			);
			$result->MoveNext();
		}
	}
	$datasql_reference11_fields = array_keys($datasql_reference11);
	$smarty->assign("datasql_reference11", $datasql_reference11);

}

/* version files */

$version = file_get_contents(DEF_PATH_PRIVATE."config".DS."version.txt");
$version = trim($version);
if (!preg_match("/^[0-9]{1,2}\.[0-9]{1,2} \([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}\)$/",$version)) {
	echo "error: version hacking";
	die();
}
$smarty->assign("version", $version);

/* version db */

$version_db = array();
if ($result = $db->Execute("select aModuleVersion from aModule where aModule in ('".implode("','",$apfra_tables)."') group by aModuleVersion")) {

	while (!$result->EOF) {
		$version_db[] = $result->fields["aModuleVersion"];
		$result->MoveNext();
	}
}

foreach ($version_db as $value) {

	if (!preg_match("/^[0-9]{1,2}\.[0-9]{1,2} \([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}\)$/",$value)) {
		echo "error: version-db hacking";
		die();
	}
}

if (count($version_db) != 1) {

	echo "version mismatch, different versions in database<br>";
	echo "files: ";
	print_r($version);
	echo "<br>";
	echo "db: ";
	print_r($version_db);
	echo "<br>";
	echo "please contact your system adminstrator";
	echo "<br>";
	die();

} else {

	if ($version != $version_db[0]) {

		echo "version mismatch<br>";
		echo "files: ";
		print_r($version);
		echo "<br>";
		echo "db: ";
		print_r($version_db[0]);
		echo "<br>";
		echo "please contact your system adminstrator";
		echo "<br>";
		die();
	}
}

/* check for newer version */

$version_dwzi = "";

/* check only one time per day for newer versions */
if (file_exists(DEF_PATH_PRIVATE."tplc".DS."version_dwzi.txt") && date("Y-m-d", filectime(DEF_PATH_PRIVATE."tplc".DS."version_dwzi.txt")) != date("Y-m-d")) {
	@unlink(DEF_PATH_PRIVATE."tplc".DS."version_dwzi.txt");
}

if (file_exists(DEF_PATH_PRIVATE."tplc".DS."version_dwzi.txt")) {

	$version_dwzi = file_get_contents(DEF_PATH_PRIVATE."tplc".DS."version_dwzi.txt");
	$version_dwzi = trim($version_dwzi);

} else {

	if (ini_get("allow_url_fopen")) {

		$ctx = stream_context_create(array(
		    'http' => array(
		        'timeout' => 2
		        )
		    )
		);
		$version_dwzi = @file_get_contents('http://apfra.dwzi.at/version.txt', 0, $ctx);

		if ($version_dwzi !== false) {

			$version_dwzi = trim($version_dwzi);
			if (preg_match("/^[0-9]{1,2}\.[0-9]{1,2} \([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}\)$/",$version_dwzi)) {
				file_put_contents(DEF_PATH_PRIVATE."tplc".DS."version_dwzi.txt", $version_dwzi);
			} else {
					$version_dwzi = "";
			}

		} else {
			/* error version retrieving */
		}

	} else {

		/* version checking disabled */
	}
}
$smarty->assign("version_dwzi", $version_dwzi);

/* cron jobs ? */

if (file_exists(DEF_PATH_PRIVATE."cron")) {

	foreach (glob(DEF_PATH_PRIVATE."cron".DS."*.inc.php") as $file) {

		$filename = basename($file);

		$tmparr = explode("_",$filename);
		$interval = $tmparr[0];
		if (is_numeric($interval)) {

			if (file_exists(DEF_PATH_PRIVATE."tplc".DS."cron_".$filename.".txt") && ((time() - filectime(DEF_PATH_PRIVATE."tplc".DS."cron_".$filename.".txt")) > $interval)) {

				/* interval reached, delete cron stamp */
				@unlink(DEF_PATH_PRIVATE."tplc".DS."cron_".$filename.".txt");
			}

			if (!file_exists(DEF_PATH_PRIVATE."tplc".DS."cron_".$filename.".txt")) {

				/* set cron stamp */

				file_put_contents(DEF_PATH_PRIVATE."tplc".DS."cron_".$filename.".txt", "");

				require_once($file);
			}

		} else {

			echo "error with cron job ".$filename.", time interval not set [n]_0cron.inc.php";
			die();
		}
	}
}

$smarty->assign("debug", DEF_DEBUG);
$smarty->assign("debug_info", "[Debug Info] Host: ".$_SERVER["REMOTE_ADDR"]);

$smarty->assign("url", DEF_URL);
$smarty->assign("path", DEF_PATH);

$smarty->assign("appname", DEF_APPNAME);

$smarty->assign("logged_in", $logged_in);
$smarty->assign("is_admin", $is_admin);


function reload_page($param = '') {

	require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");

	header("Location: ".DEF_URL.$param);
	die();
}

function convertPHP2Moment($format)
{
	$replacements = [
			'd' => 'DD',
			'D' => 'ddd',
			'j' => 'D',
			'l' => 'dddd',
			'N' => 'E',
			'S' => 'o',
			'w' => 'e',
			'z' => 'DDD',
			'W' => 'W',
			'F' => 'MMMM',
			'm' => 'MM',
			'M' => 'MMM',
			'n' => 'M',
			't' => '', // no equivalent
			'L' => '', // no equivalent
			'o' => 'YYYY',
			'Y' => 'YYYY',
			'y' => 'YY',
			'a' => 'a',
			'A' => 'A',
			'B' => '', // no equivalent
			'g' => 'h',
			'G' => 'H',
			'h' => 'hh',
			'H' => 'HH',
			'i' => 'mm',
			's' => 'ss',
			'u' => 'SSS',
			'e' => 'zz', // deprecated since version 1.6.0 of moment.js
			'I' => '', // no equivalent
			'O' => '', // no equivalent
			'P' => '', // no equivalent
			'T' => '', // no equivalent
			'Z' => '', // no equivalent
			'c' => '', // no equivalent
			'r' => '', // no equivalent
			'U' => 'X',
	];
	$momentFormat = strtr($format, $replacements);
	return $momentFormat;
}

function convertMoment2PHP($format)
{
	$replacements = [
			'DD' => 'd',
			'ddd' => 'D',
			'D' => 'j',
			'dddd' => 'l',
			'E' => 'N',
			'o' => 'S',
			'e' => 'w',
			'DDD' => 'z',
			'W' => 'W',
			'MMMM' => 'F',
			'MM' => 'm',
			'MMM' => 'M',
			'M' => 'n',
			'YYYY' => 'Y',
			'YY' => 'y',
			'a' => 'a',
			'A' => 'A',
			'h' => 'g',
			'H' => 'G',
			'hh' => 'h',
			'HH' => 'H',
			'mm' => 'i',
			'ss' => 's',
			'SSS' => 'u',
			'zz' => 'e', // deprecated since version 1.6.0 of moment.js
			'X' => 'U',
	];
	$momentFormat = strtr($format, $replacements);
	return $momentFormat;
}

/**
 * Function for creating a file array for a modal box (action=excel or word)
 */
function create_filearr($fbase, $ftype = "", $fpath = "") {

	$farr = array();

	foreach (glob(($fpath ? $fpath : $fbase).DS."*") as $file) {

		if (is_dir($file)) {
			$farr = array_merge($farr, create_filearr($fbase, $ftype, $file));
		} else {
			$tmpf = pathinfo($file);
			switch ($ftype) {
			case "word":
				if (in_array(strtolower($tmpf["extension"]), array("docx"))) {
					$farr[] = substr($file, strlen($fbase)+1, 4096);
				}
				break;
			case "excel":
				if (in_array(strtolower($tmpf["extension"]), array("xls", "xlsx"))) {
					$farr[] = substr($file, strlen($fbase)+1, 4096);
				}
				break;
			case "":
			default:
				$farr[] = substr($file, strlen($fbase)+1, 4096);
				break;
			}
		}
	}

	return $farr;
}

function _encode_string_array ($stringArray) {
	$s = strtr(base64_encode(addslashes(gzcompress(serialize($stringArray),9))), '+/=', '-_,');
	return $s;
}

function _decode_string_array ($stringArray) {
	$s = unserialize(gzuncompress(stripslashes(base64_decode(strtr($stringArray, '-_,', '+/=')))));
	return $s;
}

?>
