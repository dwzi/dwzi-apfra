{strip}

{if $apfra_rights[$module]["upd"] == 0}

	{include file="datasql/datasql_a_edit_reference.tpl.php"}

{else}

	{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

	<div class="form-group has-feedback">
		<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} *{/if}</label>
		<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
			<select class="combobox form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" data-error="Bitte füllen Sie dieses Feld aus."
				{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} required="required"{/if}>
				<option></option>
			{section name=x loop=$dataref_{$datasql_edit_fields[ti].row[i].col[j].field}}
				<option value="{$dataref_{$datasql_edit_fields[ti].row[i].col[j].field}[x].id}"{if $dataref_{$datasql_edit_fields[ti].row[i].col[j].field}[x].id == $data.{$datasql_edit_fields[ti].row[i].col[j].field}} selected{/if}>{$dataref_{$datasql_edit_fields[ti].row[i].col[j].field}[x].field}</option>
			{/section}
			</select>
			<div class="help-block with-errors"></div>
		</div>
	</div>

{/strip}
<script type="text/javascript">
<!--
$('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').combobox();
//-->
</script>
{strip}

{/if}

{/strip}
