{strip}

{assign var="tmpdesc" value="{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}</label>

	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
	
		<input type="hidden" class="form-control" id="f_{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_key"]}" name="f_{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_key"]}" value="" />
		<table class="table">

	{if $apfra_rights[{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_table"]}]["ins"] == 1}	
		<tr>
			<td>
				<div class="input-group">
					<div class="input-group-addon">
						<i id="fti_{$datasql_edit_fields[ti].row[i].col[j].field}" class="glyphicon glyphicon-search"></i>
					</div>
					<input type="text" class="form-control" id="ft_{$datasql_edit_fields[ti].row[i].col[j].field}" name="ft_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}" value="" autocomplete="off" />
				</div>
			</td>
			<td>&nbsp;</td>
		</tr>
	{/if}

	{section name=x loop=$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}}
		<tr id="trow_{$datasql_edit_fields[ti].tab}_{$smarty.section.x.index}">
			<td>{$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}[x].{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_valuenorm"]}}</td>
			<td>
				<a role="button" class="btn btn-sm btn-default" href="{$url}index.php?mod={$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_table"]}&p={$page}&s={$search}&a={$action}&id={$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}[x].id}" target="{$datasql_edit_fields[ti].row[i].col[j].field}_{$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}[x].id}"><span class="glyphicon glyphicon-pencil"></span></a>
			{if $apfra_rights[{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_table"]}]["del"] == 1}
				&nbsp;
				<a role="button" class="btn btn-sm btn-danger" href="#" data-row="trow_{$datasql_edit_fields[ti].tab}_{$smarty.section.x.index}" data-info="{$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}[0].{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_valuenorm"]}}" data-href="{$url}index.php?mod={$module}&p={$page}&s={$search}&a={$action}&id={$id}&t={$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}&fa=del_{$datasql_edit_fields[ti].row[i].col[j].field}&fr={$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_table"]}&did={$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}[x].dataid}" data-toggle="modal" data-target="#Modal-delete"><span class="glyphicon glyphicon-trash"></span></a>
			{/if}
			</td>
		</tr>
	{/section}

		</table>								
	</div>									
</div>

{/strip}
<script type="text/javascript">
<!--
$('#ft_{$datasql_edit_fields[ti].row[i].col[j].field}').typeahead({
    ajax: {
        url: '{$url}index.php?mod={$module}&a=json&id={$id}&ref={$datasql_edit_fields[ti].row[i].col[j].field}',
        displayField: '{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_valuenorm"]}'
    },
    onSelect: function(item) {
        $('#f_{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_key"]}').val(item.value);
    },
    scrollBar: true
});

$('#fti_{$datasql_edit_fields[ti].row[i].col[j].field}').click(function(event) {
    var $input = $("#ft_{$datasql_edit_fields[ti].row[i].col[j].field}");
    $input.typeahead('ajaxLookupAll');
    $input.focus();
});

//-->
</script>
{strip}
{/strip}
