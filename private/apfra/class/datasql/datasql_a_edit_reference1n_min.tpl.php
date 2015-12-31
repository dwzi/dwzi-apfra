{strip}

{assign var="tmpdesc" value="{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}</label>

	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">

		<select class="form-control" multiple id="f_{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_key"]}" name="f_{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_key"]}[]" style="width:99%;">
			{section name=x loop=$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}}
			<option value="{$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}[x].id}" selected="selected">{$dataref1n_{$datasql_edit_fields[ti].row[i].col[j].field}[x].{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_valuenorm"]}}</option>
			{/section}
		</select>

	</div>
</div>

{/strip}
<script type="text/javascript">
<!--

$('#f_{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_key"]}').select2({
  ajax: {
	  url: '{$url}index.php?mod={$module}&a=json&id={$id}&ref={$datasql_edit_fields[ti].row[i].col[j].field}',
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
		return {
                results: $.map(data, function (item) {
                    return {
                        text: item.{$datasql_reference11[{$datasql_reference1n[{$datasql_edit_fields[ti].row[i].col[j].field}]["coln_key"]}]["field"]},
                        id: item.id
                    }
                })
            };
    },
    cache: true
  }
});

//-->
</script>
{strip}
{/strip}
