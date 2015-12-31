{strip}

	<div class="modal fade" id="Modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button id="modal-btnclose" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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

	<div class="modal fade" id="Modal-delete-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button id="modal-delete-error-btnclose" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Dieser Eintrag hat verkn&uuml;pfte Daten werden!</h4>
		  </div>
		  <div class="modal-body">
			Folgende Einträge sind in anderen Tabellen vorhanden:<br>
			<br>
		{foreach $delarr as $key => $valuearr}
			{$apfra_db_desc.{$valuearr["desc"]}} ({$valuearr["ids"]|@count} x)<br>
			{section name=i loop=$valuearr["ids"]}
				- <a href="{$url}index.php?mod={$valuearr["desc"]}&a=edit&id={$valuearr["ids"][i]}" target="t_{$valuearr["desc"]}_{$valuearr["ids"][i]}">ID {$valuearr["ids"][i]}</a><br>
			{/section}
		{/foreach}
		  </div>
		  <div class="modal-footer">
		  	<a id="modal-delete-error-btnsubmit" href="{$url}index.php?mod={$module}&p={$page}&pp={$perpage}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&a=delete&id={$did}&force=1" class="btn btn-danger">Alle Eintr&auml;ge l&ouml;schen</a>
			<button id="modal-delete-error-btncancel" type="button" class="btn btn-default" data-dismiss="modal">Abbruch</button>
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
    $('#modal-btncancel, #modal-btnclose').click(function() {
    	$('#'+$(e.relatedTarget).data('row')).removeClass('danger');
    });
    $('#Modal-delete').keydown(function(ev){
    	var code = ev.keyCode || ev.which;
        if(code == 27){
        	$('#'+$(e.relatedTarget).data('row')).removeClass('danger');
        }
    });
});
//-->
</script>
{strip}

{if file_exists("`$path`../private/mod/`$module`/`$module`_main_pre_buttons.tpl.php")}

	{include file="`$path`../private/mod/`$module`/`$module`_main_pre_buttons.tpl.php"}

{/if}

<div id="tabinfo" class="row" style="width: 100%; margin-left:0px{literal}!important{/literal}; margin-right:45px{literal}!important{/literal}; background-color: white; z-index:1;">

	<div class="col-md-4">
		<a style="color: #428BCA; margin-bottom: 10px; margin-right: 10px;" class="btn btn-default{if $pagination[0].class != ""} {$pagination[0].class}{/if}" href="{if $pagination[0].page != 0}{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&pp={$perpage}&p={$pagination[0].page}{else}#{/if}">{$pagination[0].text}</a>
		<a style="color: #428BCA; margin-bottom: 10px;" class="btn btn-default{if $pagination[1].class != ""} {$pagination[1].class}{/if}" href="{if $pagination[1].page != 0}{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&pp={$perpage}&p={$pagination[1].page}{else}#{/if}">{$pagination[1].text}</a>
		<select class="selectpicker show-tick col-md-1"  data-live-search="true" data-size="10" data-width="auto" id="p" name="p" onChange="location.href='{$url}index.php?mod={$module}&p='+$('#p').val()+'&pp={$perpage}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}';">
		{foreach $pagearr as $i}
			<option value="{$i}"{if $page == $i} selected{/if}>{$i}</option>
		{/foreach}
		</select>
		<span style="vertical-align:text-bottom; margin-right: 10px;">von {$pages}</span>
		<a style="color: #428BCA; margin-bottom: 10px; margin-right: 10px;" class="btn btn-default{if $pagination[{$pagination|@count}-2].class != ""} {$pagination[{$pagination|@count}-2].class}{/if}" href="{if $pagination[{$pagination|@count}-2].page != 0}{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&pp={$perpage}&p={$pagination[{$pagination|@count}-2].page}{else}#{/if}">{$pagination[{$pagination|@count}-2].text}</a>
		<a style="color: #428BCA; margin-bottom: 10px;" class="btn btn-default{if $pagination[{$pagination|@count}-1].class != ""} {$pagination[{$pagination|@count}-1].class}{/if}" href="{if $pagination[{$pagination|@count}-1].page != 0}{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&pp={$perpage}&p={$pagination[{$pagination|@count}-1].page}{else}#{/if}">{$pagination[{$pagination|@count}-1].text}</a>
	</div>

	<div class="col-md-4 text-center">

		<form class="form" role="form" action="{$url}index.php" method="get">
			<input type="hidden" name="mod" value="{$module}">
			<input type="hidden" name="p" value="1">
			<input type="hidden" name="pp" value="{$perpage}">
		{foreach $data_filter as $df_key => $df_value}
			<input type="hidden" name="ff_{$df_key}" value="{$df_value}">
		{/foreach}
			<input type="hidden" id="nffvalue" value="">
			<div class="input-group">
				<input type="text" class="form-control" id="s" name="s" value="{$search}" placeholder="Suchbegriff" autofocus="autofocus" />
				<span class="input-group-btn">
					<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
				{if $search != ""}
					<button type="submit" class="btn btn-danger" onClick="document.getElementById('s').value='';"><span class="glyphicon glyphicon-remove"></span></button>
				{/if}
				</span>
			</div>
		</form>

	</div>

	<div class="col-md-4 text-right">
			<span style="vertical-align:text-bottom; margin-right: 10px;">{$count} Datens&auml;tze</span>
			<select class="selectpicker show-tick" data-live-search="false" data-width="auto" id="pp" name="pp" onChange="location.href='{$url}index.php?mod={$module}&p={$page}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&pp='+$('#pp').val();">
				<option value="10"{if $perpage == 10} selected{/if}>10</option>
				<option value="20"{if $perpage == 20} selected{/if}>20</option>
				<option value="30"{if $perpage == 30} selected{/if}>30</option>
				<option value="40"{if $perpage == 40} selected{/if}>40</option>
				<option value="50"{if $perpage == 50} selected{/if}>50</option>
			</select>
			<span style="vertical-align:text-bottom; margin-left: 10px;">Einträge pro Seite</span>
			<a href="{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&pp={$perpage}&p={$page}" style="margin-bottom: 10px; margin-left: 10px;" role="button"" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="aktualisieren"><span class="glyphicon glyphicon-refresh"></span></a>
		{if $search || $data_filter_url}
			<a href="{$url}index.php?mod={$module}&sort={$sort}&dir={$dirsort}&pp={$perpage}&p=1" style="margin-bottom: 10px; margin-left: 10px;" role="button"" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Suche und Filter zur&uuml;cksetzen"><span class="glyphicon glyphicon-ban-circle"></span></a>
		{/if}
	</div>

</div>

<table class="table table-striped table-bordered table-fixed-header">
	<thead class="header">
		<tr>
			<th style="white-space: nowrap; width:1px;">&nbsp;</th>
		{section name=i loop=$datasql_table_fields}
			{assign var="tmpdesc" value="{$datasql_table}.{$datasql_table_fields[i]}"}
			<th>
				<a href="{$url}index.php?mod={$module}&p={$page}&pp={$perpage}&sort={$datasql_table_fields[i]}&dir={if $datasql_table_fields[i] != $sort}asc{else}{if $dirsort == "asc"}desc{else}asc{/if}{/if}&s={$search}{$data_filter_url}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$datasql_table_fields[i]}{/if}</a>{if $datasql_table_fields[i] == $sort} {if $dirsort == "asc"}<span class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>{else}<span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>{/if}{/if}
				&nbsp;
				<a href="#" id="filter_{$datasql_table_fields[i]}" class="{if $data_filter["{$datasql_table_fields[i]}"]|default:"" != ""}btn btn-sm btn-danger{else}text-muted{/if}" role="button" data-toggle="popover" data-placement="top"><span class="glyphicon glyphicon-filter disabled"></span></a>
			</th>
		{/section}
			<th style="white-space: nowrap; width:1px;">
			{if $apfra_rights[$module]["ins"] == 1}
				<a role="button" class="btn btn-sm btn-default" href="{$url}index.php?mod={$module}&a=edit&p={$page}&pp={$perpage}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&id=0" data-toggle="tooltip" data-placement="top" title="Datensatz hinzuf&uuml;gen"><span class="glyphicon glyphicon-plus"></span></a>
				&nbsp;
			{/if}
				<a role="button" class="btn btn-sm btn-default" target="print" href="{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&a=print" data-toggle="tooltip" data-placement="top" title="PDF-Export"><span class="glyphicon glyphicon-print"></span></a>
				&nbsp;
				<a role="button" class="btn btn-sm btn-default" target="export" href="{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&a=export" data-toggle="tooltip" data-placement="top" title="CSV-Export"><span class="glyphicon glyphicon-export"></span></a>
			</th>
		</tr>
	</thead>
	<tbody>
	{section name=i loop=$data}
		<tr id="trow_{$smarty.section.i.index}">
			<td style="white-space: nowrap;">
				<a role="button" class="btn btn-sm btn-default" href="{$url}index.php?mod={$module}&a=edit&p={$page}&pp={$perpage}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&id={$data[i].id}"><span class="glyphicon glyphicon-pencil"></span></a>
			</td>
		{section name=j loop=$datasql_table_fields}
			{assign var="tmpflink" value="{$datasql_field_type[{$datasql_table_fields[j]}]["link"]|default:""}"}
			{assign var="tmpftype" value="{$datasql_field_type[{$datasql_table_fields[j]}]["type"]|default:""}"}
			{assign var="tmpfalign" value="{$datasql_field_type[{$datasql_table_fields[j]}]["align"]|default:""}"}
			<td{if $tmpfalign != ""} align="{$tmpfalign}"{/if}>
			{if $tmpflink == "#www#"}
				<a href="{$data[i].{$datasql_table_fields[j]}}" target="{$datasql_table_fields[j]}_{$data[i].id}">{$data[i].{$datasql_table_fields[j]}}</a>
			{elseif $tmpflink == "#mail#"}
				<a href="mailto:{$data[i].{$datasql_table_fields[j]}}">{$data[i].{$datasql_table_fields[j]}}</a>
			{elseif $tmpflink == "#phone#"}
				<a href="calllto:{$data[i].{$datasql_table_fields[j]}}">{$data[i].{$datasql_table_fields[j]}}</a>
			{else}
				{if $tmpftype == "checkbox"}
					{if {$data[i].{$datasql_table_fields[j]}} == 1}
						&#10003;
					{else}
						&sdot;
					{/if}
				{elseif $tmpftype == "date"}
					{if $data[i].{$datasql_table_fields[j]} != "" && $data[i].{$datasql_table_fields[j]} != "0000-00-00"}
{/strip}
<script type="text/javascript">
<!--
	document.write(moment('{$data[i].{$datasql_table_fields[j]}}', 'YYYY-MM-DD').format('{if isset($datasql_field_type[{$datasql_table_fields[j]}]["format"])}{$datasql_field_type[{$datasql_table_fields[j]}]["format"]}{else}DD.MM.YYYY{/if}'));
//-->
</script>
{strip}
					{/if}
				{elseif $tmpftype == "datetime"}
					{if $data[i].{$datasql_table_fields[j]} != "" && $data[i].{$datasql_table_fields[j]} != "0000-00-00 00:00:00"}
	{/strip}
	<script type="text/javascript">
	<!--
		document.write(moment('{$data[i].{$datasql_table_fields[j]}}', 'YYYY-MM-DD HH:mm:ss').format('{if isset($datasql_field_type[{$datasql_table_fields[j]}]["format"])}{$datasql_field_type[{$datasql_table_fields[j]}]["format"]}{else}DD.MM.YYYY HH:mm:ss{/if}'));
	//-->
	</script>
	{strip}
					{/if}
				{elseif $tmpftype == "image" || $tmpftype == "file"}
					{assign var="tmpfileinfo" value="{$datasql_table_fields[j]}_fileinfo"}
					{if isset($data[i].$tmpfileinfo)}
							<img class="img-thumbnail" src="{$url}index.php?mod={$module}&a=file&col={$datasql_table_fields[j]}&id={$data[i].id}">
					{/if}
				{else}
					{$data[i].{$datasql_table_fields[j]}}
				{/if}
			{/if}
			</td>
		{/section}
			<td style="white-space: nowrap;">
		{if file_exists("`$path`../private/mod/`$module`/`$module`_main_buttons.tpl.php")}

			{include file="`$path`../private/mod/`$module`/`$module`_main_buttons.tpl.php"}

		{/if}
			{if $apfra_rights[$module]["del"] == 1}
				<a role="button" class="btn btn-sm btn-danger" href="#" data-row="trow_{$smarty.section.i.index}" data-info="
				{section name=j loop=$datasql_edit_field_legend}
						{$data[i].{$datasql_edit_field_legend[j]}}
						{if $smarty.section.j.index<$smarty.section.j.max-1}, {/if}
					{/section}
					" data-href="{$url}index.php?mod={$module}&p={$page}&pp={$perpage}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&a=delete&id={$data[i].id}&rowid={$smarty.section.i.index}" data-toggle="modal" data-target="#Modal-delete"><span class="glyphicon glyphicon-trash"></span></a>
			{/if}
			</td>
		</tr>
	{/section}
	</tbody>
{if $datasql_table_sum > 0}
	<tfoot>
		<tr>
			<td style="white-space: nowrap;">
				&Sigma;
			</td>
		{section name=j loop=$datasql_table_sum_data}
			{assign var="tmpfalign" value="{$datasql_field_type[{$datasql_table_fields[j]}]["align"]|default:""}"}
			<td{if $tmpfalign != ""} align="{$tmpfalign}"{/if}>
				{$datasql_table_sum_data[j]}
			</td>
		{/section}
		<td style="white-space: nowrap;">
			&Sigma;
		</td>
	</tfoot>
SUmme
{/if}

</table>

<div class="pagination pull-right">
	Seite {$page} von {$pages}
</div>
<ul class="pagination">
	{section name=i loop=$pagination}
		<li {if $pagination[i].class != ""}class="{$pagination[i].class}"{/if}><a href="{if $pagination[i].page != 0}{$url}index.php?mod={$module}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}&pp={$perpage}&p={$pagination[i].page}{else}#{/if}">{$pagination[i].text}</a></li>
	{/section}
</ul>

{/strip}
<script type="text/javascript">
<!--

	$('#p').selectpicker();
	$('#pp').selectpicker();

	{section name=i loop=$datasql_table_fields}
		{assign var="tmpdesc" value="{$datasql_table}.{$datasql_table_fields[i]}"}
		$('#filter_{$datasql_table_fields[i]}').popover({
			"html": true,
			"title": 'Filter: {if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$datasql_table_fields[i]}{/if}<button type="button" class="popoverclose close" onClick="$(\'#filter_{$datasql_table_fields[i]}\').trigger(\'click\');">&times;</button>',
			"content": '<form class="form" role="form" action="{$url}index.php" method="get"><input type="hidden" name="mod" value="{$module}"><input type="hidden" name="p" value="1"><input type="hidden" name="pp" value="{$perpage}"><input type="hidden" name="s" value="{$search}"><input type="hidden" name="sort" value="{$sort}"><input type="hidden" name="dir" value="{$dirsort}">{foreach $data_filter as $df_key => $df_value}<input type="hidden" name="ff_{$df_key}" value="{$df_value}">{/foreach}<div class="form-inline"><input id="ff_{$datasql_table_fields[i]}" name="ff_{$datasql_table_fields[i]}" value="{$data_filter["{$datasql_table_fields[i]}"]|default:""}" type="text" class="form-control"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok"></button>{if $data_filter["{$datasql_table_fields[i]}"]|default:"" != ""}<button type="submit" class="btn btn-danger" onClick="document.getElementById(\'ff_{$datasql_table_fields[i]}\').value=\'\';"><span class="glyphicon glyphicon-remove"></span></button>{/if}</div></form>'
		});
{/section}

	var stickyTop = $('#tabinfo').offset().top;
	  $(window).scroll(function(){

		    var windowTop = $(window).scrollTop();
		    if (stickyTop < windowTop) {
		        $('#tabinfo').css({literal}{ position: 'fixed', top: 0, left:0, padding: '0px 15px' }{/literal});
		      }
		      else {
		        $('#tabinfo').css({literal}{ position: 'static', padding: '' }{/literal});
		      }
	});

  $('.table-fixed-header').fixedHeader();

  $('[data-toggle="tooltip"]').tooltip({
	  delay: { "show": 500, "hide": 100 }
  });
 //-->
</script>
{strip}
{/strip}
{if $delarr|@count > 0}
<script type="text/javascript">
<!--
$('#Modal-delete-error').on('show.bs.modal', function(e) {
    $('#trow_{$rowid}').addClass('danger');
    $('#modal-delete-error-btncancel, #modal-delete-error-btnclose').click(function() {
    	$('#trow_{$rowid}').removeClass('danger');
    });
	 $('#Modal-delete-error').keydown(function(ev){
    	var code = ev.keyCode || ev.which;
        if(code == 27){
        	$('#trow_{$rowid}').removeClass('danger');
        }
    });
});
$('#Modal-delete-error').modal('show');
//-->
</script>
{/if}
{strip}
{/strip}
