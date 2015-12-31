{strip}

<!DOCTYPE html>
<html lang="de">

	<head>
		<meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

		<title>{$appname}</title>

		<meta name="description" content="">
		<meta name="author" content="">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="{$url}js/html5shiv.js"></script>
		<script src="{$url}js/respond.min.js"></script>
		<![endif]-->

		<link href="{$url}css/stylesheet.css" rel="stylesheet">
	{if !$logged_in || $theme == ""}
		<link href="{$url}css/bootstrap.min.css" rel="stylesheet">
	{else}
		<link href="{$url}css/bootstrap-theme-{$theme}.min.css" rel="stylesheet">
	{/if}
		<link href="{$url}css/bootstrap-datetimepicker.min.css" rel="stylesheet">
		<link href="{$url}css/bootstrap-combobox.css" rel="stylesheet">
		<link href="{$url}css/bootstrap-select.min.css" rel="stylesheet">

		<link href="{$url}css/select2.min.css" rel="stylesheet">

		<link href="{$url}css/dataTables.bootstrap.min.css" rel="stylesheet">

		<link href="{$url}css/jquery.treegrid.css" rel="stylesheet"> <!-- //TODO check -->
		<style type="text/css">
			.sidebar-nav {
				padding: 9px 0;
			}
		</style>
		<link href="{$url}css/bootstrap-apfra.css" rel="stylesheet">
	<!--
		<link href="{$url}css/bootstrap-responsive.css" rel="stylesheet">
	//-->
	<!--
		<link rel="shortcut icon" href="images/favicon.ico">
		<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	//-->
		<script src="{$url}js/jquery-2.1.4.min.js"></script>
		<script src="{$url}js/bootstrap.min.js"></script>
		<script src="{$url}js/moment-with-locales.min.js"></script>
		<script src="{$url}js/bootstrap-typeahead-dwzi.js"></script>
		<script src="{$url}js/bootstrap-datetimepicker.min.js"></script>
		<script src="{$url}js/bootstrap-combobox-dwzi.js"></script>

		<script src="{$url}js/bootstrap-select.min.js"></script>

		<script src="{$url}js/jquery.dataTables.min.js"></script>
		<script src="{$url}js/dataTables.bootstrap.min.js"></script>

		<script src="{$url}js/select2.min.js"></script>
		<script src="{$url}js/jquery.treegrid.js"></script> <!-- //TODO check -->
		<script src="{$url}js/jquery.treegrid.bootstrap3.js"></script> <!-- //TODO check -->
		<script src="{$url}js/jquery-sortable.min.js"></script> <!-- //TODO check -->
		<script src="{$url}js/validator.min.js"></script>
		<script src="{$url}js/jquery.apfra.js"></script>
	</head>

	<body>

{/strip}
