{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group has-feedback">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} *{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">

		<input type="hidden" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" value="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}" />
		<div class="input-group date" id="datetimepicker_{$datasql_edit_fields[ti].row[i].col[j].field}">
			<input type="text" class="form-control" id="ft_{$datasql_edit_fields[ti].row[i].col[j].field}" name="ft_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}" value=""
				{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} required="required"{/if}
				{if $apfra_rights[$module]["upd"] == 0} readonly="readonly"{/if}/>
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
		</div>
		<div class="help-block with-errors"></div>										
	</div>
</div>

{/strip}
<script type="text/javascript">
<!--
$(function () {

	$('#ft_{$datasql_edit_fields[ti].row[i].col[j].field}').val(moment('{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}', 'YYYY-MM-DD').format('{if isset($datasql_edit_fields[ti].row[i].col[j].format)}{$datasql_edit_fields[ti].row[i].col[j].format}{else}DD.MM.YYYY{/if}'));

	$('#datetimepicker_{$datasql_edit_fields[ti].row[i].col[j].field}').datetimepicker({
		toolbarPlacement: 'top',
		locale: 'de-at',
		calendarWeeks: true,
		sideBySide: true,
		showTodayButton: true,
		showClose: true,
		format: '{if isset($datasql_edit_fields[ti].row[i].col[j].format)}{$datasql_edit_fields[ti].row[i].col[j].format}{else}DD.MM.YYYY{/if}'
	});

	$('#datetimepicker_{$datasql_edit_fields[ti].row[i].col[j].field}').on("dp.change",function (e) {
		$('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').val(moment(e.date).format('YYYY-MM-DD'));
	});
});
//-->
</script>
{strip}
{/strip}
