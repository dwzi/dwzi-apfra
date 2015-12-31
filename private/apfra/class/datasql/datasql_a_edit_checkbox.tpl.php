{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} *{/if}</label>
	<div data-toggle="buttons" class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
		<label class="dtb btn btn-default{if $data.{$datasql_edit_fields[ti].row[i].col[j].field} == 1} active{/if}">
			<input type="checkbox" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" data-error="Bitte markieren Sie dieses Feld." value="1"
				{if $data.{$datasql_edit_fields[ti].row[i].col[j].field} == 1} checked aria-pressed="true"{/if}
				{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} required="required"{/if}
				{if $apfra_rights[$module]["upd"] == 0} readonly="readonly"{/if} autocomplete="off"/>
			<span class="glyphicon glyphicon-ok"></span>
		</label>
		<div class="help-block with-errors"></div>
	</div>
</div>

{/strip}
<script type="text/javascript">
<!--
//$('#dbf_{$datasql_edit_fields[ti].row[i].col[j].field}').button("Text");
//-->
</script>
{strip}
{/strip}
