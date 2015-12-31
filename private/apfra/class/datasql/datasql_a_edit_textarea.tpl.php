{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group has-feedback">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} *{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
	{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> ""}
		<div class="input-group">
	{/if}
			<textarea class="form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}" data-error="Bitte f&uuml;llen Sie dieses Feld aus.{if isset($datasql_edit_fields[ti].row[i].col[j].minlength)} (mind. {$datasql_edit_fields[ti].row[i].col[j].minlength} Zeichen){/if}"
				{if isset($datasql_edit_fields[ti].row[i].col[j].rows)} rows="{$datasql_edit_fields[ti].row[i].col[j].rows}"{/if}
				{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} required="required"{/if}
				{if $apfra_rights[$module]["upd"] == 0} readonly="readonly"{/if}>{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}</textarea>
	{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> ""}
		</div>
	{/if}
		<div class="help-block with-errors"></div>
	</div>
</div>

{/strip}