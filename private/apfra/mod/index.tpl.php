{strip}

{include file="header.tpl.php"}

{include file="menu.tpl.php"}

<div class="container" style="min-width:100%;">

	<div class="row">

		<div class="col-md-12">

	{if $logged_in}
		<ol class="breadcrumb">
		{section name=i loop=$breadcrumb}
			<li{if $breadcrumb[i].class != ""} class="{$breadcrumb[i].class}"{/if}>
			{if $breadcrumb[i].link != ""}
				<a href="{$breadcrumb[i].link}">{$breadcrumb[i].desc}</a>
			{else}
				{$breadcrumb[i].desc}
			{/if}
			</li>
		{/section}
			</ol>
	{/if}

{if $class == "datasql"}

	{include file="`$path`../private/apfra/class/datasql/datasql.tpl.php"}

{elseif $class == "datafile"}

	{include file="`$path`../private/apfra/class/datafile/datafile.tpl.php"}

{else}

	{include file="`$module`/`$module`.tpl.php"}

{/if}

		</div>

{*
		<div class="col-md-2" id="sidebar" role="navigation">
{include file="sidebar.tpl.php"}
		</div>
*}
	</div>

	<hr>

	<footer>
		<p>
			<a href="http://dwzi.at/" target="dwzi"><img src="{$url}img/logo_dwzi.png" height="50" width="50" border="0" alt="DWZI GmbH" title="DWZI GmbH" style="float:left; margin-right: 10px;"/></a>
			{$version}<br>
			<a href="http://dwzi.at/" target="dwzi">DWZI GmbH</a>
		</p>
	{if $version_dwzi}
		{if $version_dwzi > $version}
			<p>
				<small style="color:green;">Aktualisierung {$version_dwzi} verf&uuml;gbar</small>
			</p>
		{/if}
		{if $version_dwzi < $version}
			<p>
				<small style="color:red;">offiziell aktuelle Version {$version_dwzi}</small>
			</p>
		{/if}
	{/if}
	{if $debug}
		<hr>
		<p>
			{$debug_info}
		</p>
	{/if}
	<p id="print_prevent"></p>
	</footer>

</div>


{/strip}
<script type="text/javascript">
<!--

{if $logged_in && $apfra_prevent_autologout}

function prevent_func() {
{if $apfra_autoreload == 1}
	location.reload();
{/if}
{if $apfra_autologout == 1}
	location.href = "{$url}?mod=logout&logout=true";
{/if}
}

var preventTime = {$apfra_prevent_time};
var startpreventTimer = (new Date()).getTime();
var prevent_timeout = setTimeout(prevent_func, preventTime);

function print_prevent_time() {
	$('#print_prevent').html(Math.ceil((preventTime - ( (new Date()).getTime() - startpreventTimer )) / 1000) + ' Sekunden bis zum {if $apfra_autoreload == 1}Neuladen der Seite{/if}{if $apfra_autologout == 1}automatischen Abmelden{/if}');
	var print_prevent_timeout = setTimeout(print_prevent_time, 1000);
}

print_prevent_time();

{/if}

//-->
</script>
{strip}

{/strip}

{include file="footer.tpl.php"}

{/strip}
