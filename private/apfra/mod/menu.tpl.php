{strip}

<div class="navbar navbar-default" role="navigation">
	<div class="container" style="min-width:100%;">
        <div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{$url}">{$appname}</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
		{section name=i loop=$menu}
			{if $menu[i].type == "submenu"}
				{assign var="subactiv" value=""}
			{section name=si loop=$menu[i].submenu}
				{if $menu[i].submenu[si].type == "single" && $module == $menu[i].submenu[si].module}
					{assign var="subactiv" value=" active"}
				{/if}
			{/section}			
				<li class="dropdown{$subactiv}">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$menu[i].desc} <b class="caret"></b></a>
					<ul class="dropdown-menu">
			{section name=si loop=$menu[i].submenu}
				{if $menu[i].submenu[si].type == "divider"} 
					<li class="divider"></li>
				{else}
					{* if !$logged_in || $is_admin || ($logged_in && isset($apfra_rights) && isset($apfra_rights[$menu[i].submenu[si].module]) && isset($apfra_rights[$menu[i].submenu[si].module]["sum"]) && $apfra_rights[$menu[i].submenu[si].module]["sum"] > 0) *}
					<li{if $module == $menu[i].submenu[si].module} class="active"{/if}><a href="{$url}index.php?mod={$menu[i].submenu[si].module}">{$menu[i].submenu[si].desc}</a></li>
					{* /if *}
				{/if}
			{/section}
					</ul>
				</li>
			
			{else}
				{* if !$logged_in || $is_admin || ($logged_in && isset($apfra_rights) && isset($apfra_rights[$menu[i].module]) && isset($apfra_rights[$menu[i].module]["sum"]) && $apfra_rights[$menu[i].module]["sum"] > 0) *}
				<li{if $module == $menu[i].module} class="active"{/if}><a href="{$url}index.php?mod={$menu[i].module}">{$menu[i].desc}</a></li>
				{* /if *}
			{/if}
		{/section}
			</ul>
			
{if $logged_in}
			<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$username} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="{$url}index.php?mod=aSettings"><i class="icon-user"></i> Einstellungen</a></li>
						<li class="divider"></li>
						<li><a href="{$url}index.php?mod=logout"><i class="icon-off"></i> abmelden</a></li>
					</ul>
				</li>
			</ul>
{/if}
		</div>
	</div>
</div>

{/strip}
