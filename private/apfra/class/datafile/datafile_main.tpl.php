{strip}

<div class="pagination pull-right">
	<form class="form-inline" role="form" action="{$url}index.php" method="get">

		<div class="form-group">
			{$count} Datens&auml;tze&nbsp;&nbsp;
		</div>	
	
		<input type="hidden" name="mod" value="{$module}">
		<input type="hidden" name="p" value="1">
		<input type="hidden" id="nffvalue" value="">
		<div class="form-group">
			<label class="sr-only" for="s">Suchbegriff</label>
			<input type="text" class="form-control" id="s" name="s" value="{$search}" placeholder="Suchbegriff">
		</div>
		&nbsp;
		
		<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
	{if $search != ""}
		&nbsp;<button type="submit" class="btn btn-danger" onClick="document.getElementById('s').value='';"><span class="glyphicon glyphicon-remove"></span></button>
	{/if}
	</form>
</div>

<ul class="pagination">
	{section name=i loop=$pagination}
		<li {if $pagination[i].class != ""}class="{$pagination[i].class}"{/if}><a href="{if $pagination[i].page != 0}{$url}index.php?mod={$module}&p={$pagination[i].page}&s={$search}&sort={$sort}&dir={$dirsort}{else}#{/if}">{$pagination[i].text}</a></li>
	{/section}
</ul>

<ol class="breadcrumb">
	{section name=i loop=$fpatharr}
		<li><a href="{$url}index.php?mod={$module}&p={$page}&s={$search}&sort={$sort}&dir={$dirsort}&fpath={if $fpatharr[i] == ""}{else}{$fpatharr[i]}{/if}">{if $fpatharr[i] == ""}Stammverzeichnis{else}{$fpatharr[i]}{/if}</a></li>
	{/section}
</ol>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th width="30">
				&nbsp;
			</th>
			<th>Name</th>
			<th>Gr&ouml;&szlig;e</th>
			<th width="90">
				&nbsp;
			</th>
		</tr>
	</thead>
	<tbody>
	{section name=i loop=$data_dir}
		<tr>
			<td><span class="glyphicon glyphicon-folder-open"></span></td>
			<td><a href="{$url}index.php?mod={$module}&p={$page}&s={$search}&sort={$sort}&dir={$dirsort}&fpath={$data_dir[i].filename}">{$data_dir[i].filename}</a></td>
			<td>&nbsp;</td>
			<td>
				&nbsp;
			</td>
		</tr>
	{/section}
	{section name=i loop=$data_file}
		<tr>
			<td><span class="glyphicon glyphicon-file"></span></td>
			<td>{$data_file[i].filename}</td>
			<td align="right">{$data_file[i].filesize}</td>
			<td>
				&nbsp;
			</td>
		</tr>
	{/section}
	</tbody>
</table>

<div class="pagination pull-right">
	Seite {$page} von {$pages}
</div>
<ul class="pagination">
	{section name=i loop=$pagination}
		<li {if $pagination[i].class != ""}class="{$pagination[i].class}"{/if}><a href="{if $pagination[i].page != 0}{$url}index.php?mod={$module}&s={$search}&sort={$sort}&dir={$dirsort}&p={$pagination[i].page}{else}#{/if}">{$pagination[i].text}</a></li>
	{/section}
</ul>

{/strip}
