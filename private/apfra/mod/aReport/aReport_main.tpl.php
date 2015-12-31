{strip}

	<div class="modal fade" id="Modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Eintrag l&ouml;schen?</h4>
		  </div>
		  <div class="modal-body">
			Sind Sie sicher, dass Sie diesen Eintrag l&ouml;schen wollen?<br>
			<br>
			<p id="modal-info"></p>
		  </div>
		  <div class="modal-footer">
			<a id="modal-btnsubmit" class="btn btn-danger">L&ouml;schen</a>
			<button id="modal-btncancel" type="button" class="btn btn-default" data-dismiss="modal">Abbruch</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

{/strip}
<script type="text/javascript">
<!--
$('#Modal-delete').on('show.bs.modal', function(e) {
    $('#modal-btnsubmit').attr('href', $(e.relatedTarget).data('href'));
    $('#modal-info').html($(e.relatedTarget).data('info'));
    $('#'+$(e.relatedTarget).data('row')).addClass('danger');
    $('#modal-btncancel').click(function() {
    	$('#'+$(e.relatedTarget).data('row')).removeClass('danger');
    });    
});
//-->
</script>
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

<table class="table table-striped table-bordered">
	<thead>
		<tr>
		{section name=i loop=$datasql_table_fields}
			{assign var="tmpdesc" value="{$datasql_table}.{$datasql_table_fields[i]}"}
			<th><a href="{$url}index.php?mod={$module}&p={$page}&sort={$datasql_table_fields[i]}&dir={if $datasql_table_fields[i] != $sort}asc{else}{if $dirsort == "asc"}desc{else}asc{/if}{/if}&s={$search}">{$apfra_db_desc[$tmpdesc]}</a>{if $datasql_table_fields[i] == $sort} {if $dirsort == "asc"}<span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span>{else}<span class="glyphicon glyphicon-sort-by-alphabet-alt" aria-hidden="true"></span>{/if}{/if}</th>
		{/section}
			<th width="180">
				<a role="button" class="btn btn-sm btn-default" href="{$url}index.php?mod={$module}&a=edit&p={$page}&s={$search}&id=0"><span class="glyphicon glyphicon-plus"></span></a>
			</th>
		</tr>
	</thead>
	<tbody>
	{section name=i loop=$data}
		<tr id="trow_{$smarty.section.i.index}">
		{section name=j loop=$datasql_table_fields}
			<td>
			{if $datasql_table_fields[j]|truncate:3:"" == "www"}
				<a href="{$data[i].{$datasql_table_fields[j]}}" target="{$datasql_table_fields[j]}_{$data[i].id}">{$data[i].{$datasql_table_fields[j]}}</a>
			{elseif $datasql_table_fields[j]|truncate:5:"" == "email"}
				<a href="mailto:{$data[i].{$datasql_table_fields[j]}}">{$data[i].{$datasql_table_fields[j]}}</a>
			{else}
				{$data[i].{$datasql_table_fields[j]}}
			{/if}
			</td>
		{/section}
			<td>
				<a role="button" class="btn btn-sm btn-default" target="print" href="{$url}index.php?mod={$module}&a=print&p={$page}&s={$search}&id={$data[i].id}"><span class="glyphicon glyphicon-print"></span></a>
				&nbsp;
				<a role="button" class="btn btn-sm btn-default" target="export" href="{$url}index.php?mod={$module}&a=export&p={$page}&s={$search}&id={$data[i].id}"><span class="glyphicon glyphicon-export"></span></a>
			{if $is_admin}
				&nbsp;
				<a role="button" class="btn btn-sm btn-default" href="{$url}index.php?mod={$module}&a=edit&p={$page}&s={$search}&id={$data[i].id}"><span class="glyphicon glyphicon-pencil"></span></a>
				&nbsp;
				<a role="button" class="btn btn-sm btn-danger" href="#" data-row="trow_{$smarty.section.i.index}" data-info="{$data[i].{$datasql_table_fields[0]}}" data-href="{$url}index.php?mod={$module}&p={$page}&s={$search}&sort={$sort}&dir={$dirsort}&a=delete&id={$data[i].id}" data-toggle="modal" data-target="#Modal-delete"><span class="glyphicon glyphicon-trash"></span></a>
			{/if}	
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
