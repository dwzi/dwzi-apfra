{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group has-feedback">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} *{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
		<div class="input-group">
		{if $apfra_rights[$module]["upd"] == 1}
			<div class="input-group-addon">
				<i id="fti_{$datasql_edit_fields[ti].row[i].col[j].field}" class="glyphicon glyphicon-search"></i>
			</div>
		{/if}
			<input type="text" class="form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}" value="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}" autocomplete="off"
				{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} required="required"{/if}
				{if $apfra_rights[$module]["upd"] == 0} readonly="readonly"{/if}/>
		</div>
		<div class="help-block with-errors"></div>
	</div>
</div>

{/strip}
<script type="text/javascript">
<!--
$('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').typeahead({
    ajax: {
        url: '{$url}index.php?mod={$module}&a=json&id={$id}&ref=lookup_{$datasql_edit_fields[ti].row[i].col[j].field}',
        displayField: '{$datasql_edit_fields[ti].row[i].col[j].ref_value}'
    },
    scrollBar: true
});

$('#fti_{$datasql_edit_fields[ti].row[i].col[j].field}').click(function(event) {
    var $input = $("#f_{$datasql_edit_fields[ti].row[i].col[j].field}");
    $input.typeahead('ajaxLookupAll');
    $input.focus();
});
  
//-->
</script>
{strip}
{/strip}
