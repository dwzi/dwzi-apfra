{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group has-feedback">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} *{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
	{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> ""}
		<div class="input-group">
	{/if}
			<input type="text" class="form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}" value="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}" data-error="Bitte f&uuml;llen Sie dieses Feld aus.{if isset($datasql_edit_fields[ti].row[i].col[j].minlength)} (mind. {$datasql_edit_fields[ti].row[i].col[j].minlength} Zeichen){/if}"
				{if isset($datasql_edit_fields[ti].row[i].col[j].minlength)} data-minlength="{$datasql_edit_fields[ti].row[i].col[j].minlength}"{/if}
				{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} required="required"{/if}
				{if $apfra_rights[$module]["upd"] == 0} readonly="readonly"{/if}/>
		{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" == "#www#"}
			<span class="input-group-addon">
				<a href="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}" target="{$datasql_edit_fields[ti].row[i].col[j].field}_{$id}"><span class="glyphicon glyphicon-globe"></span></a>
			</span>
		{elseif $datasql_edit_fields[ti].row[i].col[j].link|default:"" == "#mail#"}
			<span class="input-group-addon">
				<a href="mailto:{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}"><span class="glyphicon glyphicon-envelope"></span></a>
			</span>
		{elseif $datasql_edit_fields[ti].row[i].col[j].link|default:"" == "#phone#"}
			<span class="input-group-addon">
				<a href="callto:{$data.{$datasql_edit_fields[ti].row[i].col[j].field}|regex_replace:"/[^+0-9]/":""}"><span class="glyphicon glyphicon-earphone"></span></a>
			</span>
		{elseif $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> ""}
			<span class="input-group-addon">
				<a href="{$datasql_edit_fields[ti].row[i].col[j].link|replace:'#field#':{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}}" target="{$datasql_edit_fields[ti].row[i].col[j].field}_{$id}"><span class="glyphicon glyphicon-file"></span></a>
			</span>
		{/if}
	{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> ""}
		</div>
	{/if}
		<div class="help-block with-errors"></div>
	</div>
</div>

{/strip}